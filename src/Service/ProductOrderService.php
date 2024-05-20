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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductOrderService
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
        private readonly OrderRepository $orderRepository,
        private readonly BaseRepository $baseRepository,
        private readonly MaterialRepository $materialRepository)
    {
    }

    public function show(string $order, string $orderField, int $limit, int $offset, int $orderId): ArrayResponse
    {
        if (!in_array($order, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(ProductOrder::class);

        if (!in_array($orderField, $fields)) {
            throw new \InvalidArgumentException('Invalid order_field parameter');
        }

        $orderInDB = $this->orderRepository->find($orderId);
        if (null === $orderInDB) {
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
            error_log('No data found');
        } else {
            error_log('Data found: '.print_r($productsOrder, true));
        }

        // TODO: Generate ArrayResponse
        return new ArrayResponse(array_map(function ($productOrder) {}, $productsOrder));
    }

    public function create(int $order_id, int $base_id, array $materials, float $price, int $quantity): IdResponse
    {
        $productOrder = new ProductOrder();
        $order = $this->orderRepository->find($order_id);
        if (null === $order) {
            throw new NotFoundException('Order not found');
        }
        $productOrder->setOrder($order);
        $base = $this->baseRepository->find($base_id);
        if (null === $base) {
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
                $base->getUpdatedAt()));

        $materialObjects = [];
        foreach ($materials as $materialId) {
            $material = $this->materialRepository->find($materialId);
            if (null === $material) {
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

        return new IdResponse($productOrder->getId());
    }
}
