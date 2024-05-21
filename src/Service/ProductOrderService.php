<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Material;
use App\Entity\ProductOrder;
use App\Exception\NotFoundException;
use App\Model\ArrayResponse;
use App\Model\BaseArrayItem;
use App\Model\IdResponse;
use App\Model\MaterialArrayItem;
use App\Model\ProductOrderArrayItem;
use App\Repository\BaseRepository;
use App\Repository\MaterialRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductOrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly OrderRepository $orderRepository,
        private readonly BaseRepository $baseRepository,
        private readonly MaterialRepository $materialRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function show(string $order, string $orderField, int $limit, int $offset, int $orderId): ArrayResponse
    {
        $this->logger->info('Executing show method', [
            'order' => $order,
            'orderField' => $orderField,
            'limit' => $limit,
            'offset' => $offset,
            'orderId' => $orderId
        ]);

        if (!in_array($order, ['ASC', 'DESC'])) {
            $this->logger->error('Invalid order parameter', ['order' => $order]);
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(ProductOrder::class);

        if (!in_array($orderField, $fields)) {
            $this->logger->error('Invalid order_field parameter', ['orderField' => $orderField]);
            throw new \InvalidArgumentException('Invalid order_field parameter');
        }

        $orderInDB = $this->orderRepository->find($orderId);
        if (null === $orderInDB) {
            $this->logger->error('Order not found', ['orderId' => $orderId]);
            throw new NotFoundHttpException('Order not found');
        }

        $productOrderRepository = $this->entityManager->getRepository(ProductOrder::class);

        $productsOrder = $productOrderRepository->createQueryBuilder('productOrder')
            ->select('productOrder.id', 'productOrder.price', 'productOrder.quantity', 'productOrder.totalPrice', 'productOrder.base', 'productOrder.createdAt', 'productOrder.updatedAt')
            ->join('productOrder.order', 'ord')
            ->join('ord.status', 'status')
            ->where('ord.id = :orderId')
            ->setParameter('orderId', $orderId)
            ->orderBy('productOrder.'.$orderField, $order)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();

        if (empty($productsOrder)) {
            $this->logger->warning('No data found');
        } else {
            $this->logger->info('Data found', ['data' => $productsOrder]);
        }

        $response = new ArrayResponse(array_map(function ($productOrder) {
            return new ProductOrderArrayItem(
                $productOrder['id'],
                $productOrder['price'],
                $productOrder['quantity'],
                $productOrder['totalPrice'],
                new BaseArrayItem(
                    $productOrder['base']->getId(),
                    $productOrder['base']->getType(),
                    $productOrder['base']->getTitle(),
                    $productOrder['base']->getPrice(),
                    $productOrder['base']->getDepartment()->getId(),
                    $productOrder['base']->getCreatedAt(),
                    $productOrder['base']->getUpdatedAt()
                ),
                $productOrder['createdAt'],
                $productOrder['updatedAt']
            );
        }, $productsOrder));

        $this->logger->info('show method executed successfully', ['response' => $response]);

        return $response;
    }

    public function create(int $order_id, int $base_id, array $materials, float $price, int $quantity): IdResponse
    {
        $this->logger->info('Executing create method', [
            'order_id' => $order_id,
            'base_id' => $base_id,
            'materials' => $materials,
            'price' => $price,
            'quantity' => $quantity
        ]);

        $productOrder = new ProductOrder();
        $order = $this->orderRepository->find($order_id);
        if (null === $order) {
            $this->logger->error('Order not found', ['order_id' => $order_id]);
            throw new NotFoundException('Order not found');
        }
        $productOrder->setOrder($order);
        $base = $this->baseRepository->find($base_id);
        if (null === $base) {
            $this->logger->error('Base not found', ['base_id' => $base_id]);
            throw new NotFoundException('Base not found');
        }
        $productOrder->setBase(
            new BaseArrayItem(
                $base_id,
                $base->getType(),
                $base->getTitle(),
                $base->getPrice(),
                $base->getDepartment()->getId(),
                $base->getCreatedAt(),
                $base->getUpdatedAt()
            )
        );

        $materialObjects = [];
        foreach ($materials as $materialId) {
            $material = $this->materialRepository->find($materialId);
            if (null === $material) {
                $this->logger->error("Material not found", ['materialId' => $materialId]);
                throw new NotFoundException("Material with $materialId not found");
            }
            $materialObjects[] = $material;
        }
        $productOrder->setMaterial(array_map(
            fn (Material $material) => new MaterialArrayItem(
                $material->getId(),
                $material->getType(),
                $material->getTitle(),
                $material->getPrice(),
                $material->getCategory()->getId(),
                $material->getCategoryCode(),
                $material->getCreatedAt(),
                $material->getUpdatedAt()
            ),
            $materialObjects
        ));

        $productOrder->setPrice($price);
        $productOrder->setQuantity($quantity);
        $productOrder->setTotalPrice($price * $quantity);
        $productOrder->setCreatedAt(new \DateTime());
        $productOrder->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($productOrder);
        $this->entityManager->flush();

        $response = new IdResponse($productOrder->getId());
        $this->logger->info('create method executed successfully', ['response' => $response]);

        return $response;
    }
}
