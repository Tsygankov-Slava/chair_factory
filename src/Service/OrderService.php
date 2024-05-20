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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StatusRepository $statusRepository,
        private readonly OrderRepository $orderRepository
    ) {
    }

    public function show(string $order, string $orderField, int $limit, int $offset): ArrayResponse
    {
        if (!in_array($order, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(Order::class);

        if (!in_array($orderField, $fields)) {
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
            error_log('No data found');
        } else {
            error_log('Data found: '.print_r($orders, true));
        }

        return new ArrayResponse(array_map(
            fn (Order $order) => new OrderArrayItem(
                $order->getId(),
                $order->getTotalPrice(),
                $order->getStatus()->getId(),
                $order->getCreatedAt(),
                $order->getUpdatedAt()
            ),
            $orders
        ));
    }

    public function create(float $totalPrice, int $statusId): IdResponse
    {
        // TODO: Сhecksums process
        $order = new Order();
        $order->setTotalPrice($totalPrice);
        $status = $this->statusRepository->find($statusId);
        if (null === $status) {
            throw new NotFoundHttpException('Status not found');
        }
        $order->setStatus($status);
        $order->setCreatedAt(new \DateTime());
        $order->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new IdResponse($order->getId());
    }

    public function update(int $id, ?float $totalPrice, ?int $statusId): IdResponse
    {
        $order = $this->orderRepository->find($id);
        if (null === $order) {
            throw new NotFoundHttpException('The order was not found.');
        }

        if (null !== $statusId) {
            $status = $this->statusRepository->find($statusId);
            if (null === $status) {
                throw new NotFoundHttpException('Status not found');
            }
            $order->setStatus($status);
        }

        if (null !== $totalPrice) {
            $order->setTotalPrice($totalPrice);
        }

        $order->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return new IdResponse($order->getId());
    }

    public function delete(int $id): IdResponse
    {
        $order = $this->orderRepository->find($id);
        if (null === $order) {
            throw new NotFoundHttpException('The order was not found.');
        }

        $this->entityManager->remove($order);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
