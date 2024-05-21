<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Order;
use App\Model\ArrayResponse;
use App\Model\IdResponse;
use App\Model\OrderArrayItem;
use App\Repository\OrderRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StatusRepository $statusRepository,
        private readonly OrderRepository $orderRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function show(string $order, string $orderField, int $limit, int $offset): ArrayResponse
    {
        $this->logger->info('Executing show method', [
            'order' => $order,
            'orderField' => $orderField,
            'limit' => $limit,
            'offset' => $offset,
        ]);

        if (!in_array($order, ['ASC', 'DESC'])) {
            $this->logger->error('Invalid order parameter', ['order' => $order]);
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(Order::class);

        if (!in_array($orderField, $fields)) {
            $this->logger->error('Invalid order_field parameter', ['orderField' => $orderField]);
            throw new \InvalidArgumentException('Invalid order_field parameter');
        }

        $query = $this->entityManager->createQuery(
            'SELECT order_, status
             FROM App\Entity\Order order_
             JOIN order_.status status
             ORDER BY order_.'.$orderField.' '.$order
        )
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $orders = $query->getResult();

        if (empty($orders)) {
            $this->logger->warning('No data found');
        } else {
            $this->logger->info('Data found', ['data' => $orders]);
        }

        $response = new ArrayResponse(array_map(
            fn (Order $order) => new OrderArrayItem(
                $order->getId(),
                $order->getTotalPrice(),
                $order->getStatus()->getId(),
                $order->getCreatedAt(),
                $order->getUpdatedAt()
            ),
            $orders
        ));

        $this->logger->info('show method executed successfully', ['response' => $response]);

        return $response;
    }

    public function create(float $totalPrice, int $statusId): IdResponse
    {
        $this->logger->info('Executing create method', [
            'totalPrice' => $totalPrice,
            'statusId' => $statusId,
        ]);

        $order = new Order();
        $order->setTotalPrice($totalPrice);
        $status = $this->statusRepository->find($statusId);
        if (null === $status) {
            $this->logger->error('Status not found', ['statusId' => $statusId]);
            throw new NotFoundHttpException('Status not found');
        }
        $order->setStatus($status);
        $order->setCreatedAt(new \DateTime());
        $order->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        $response = new IdResponse($order->getId());
        $this->logger->info('create method executed successfully', ['response' => $response]);

        return $response;
    }

    public function update(int $id, ?float $totalPrice, ?int $statusId): IdResponse
    {
        $this->logger->info('Executing update method', [
            'id' => $id,
            'totalPrice' => $totalPrice,
            'statusId' => $statusId,
        ]);

        $order = $this->orderRepository->find($id);
        if (null === $order) {
            $this->logger->error('Order not found', ['id' => $id]);
            throw new NotFoundHttpException('The order was not found.');
        }

        if (null !== $statusId) {
            $status = $this->statusRepository->find($statusId);
            if (null === $status) {
                $this->logger->error('Status not found', ['statusId' => $statusId]);
                throw new NotFoundHttpException('Status not found');
            }
            $order->setStatus($status);
        }

        if (null !== $totalPrice) {
            $order->setTotalPrice($totalPrice);
        }

        $order->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        $response = new IdResponse($order->getId());
        $this->logger->info('update method executed successfully', ['response' => $response]);

        return $response;
    }

    public function delete(int $id): IdResponse
    {
        $this->logger->info('Executing delete method', ['id' => $id]);

        $order = $this->orderRepository->find($id);
        if (null === $order) {
            $this->logger->error('Order not found', ['id' => $id]);
            throw new NotFoundHttpException('The order was not found.');
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        $response = new IdResponse($id);
        $this->logger->info('delete method executed successfully', ['response' => $response]);

        return $response;
    }
}
