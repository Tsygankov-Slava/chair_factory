<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Status;
use App\Model\ArrayResponse;
use App\Model\IdResponse;
use App\Model\StatusArrayItem;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StatusService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StatusRepository $statusRepository,
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
        $fields = $helper->getEntityFields(Status::class);

        if (!in_array($orderField, $fields)) {
            $this->logger->error('Invalid order_field parameter', ['orderField' => $orderField]);
            throw new \InvalidArgumentException('Invalid order_field parameter');
        }

        $query = $this->entityManager->createQuery(
            'SELECT status
             FROM App\Entity\Status status
             ORDER BY status.'.$orderField.' '.$order
        )->setMaxResults($limit)
            ->setFirstResult($offset);

        $statuses = $query->getResult();

        if (empty($statuses)) {
            $this->logger->warning('No data found');
        } else {
            $this->logger->info('Data found', ['data' => $statuses]);
        }

        $response = new ArrayResponse(array_map(
            fn (Status $status) => new StatusArrayItem(
                $status->getId(),
                $status->getCode(),
                $status->getDescription(),
                $status->getCreatedAt(),
                $status->getUpdatedAt()
            ),
            $statuses
        ));

        $this->logger->info('show method executed successfully', ['response' => $response]);

        return $response;
    }

    public function create(string $code, string $description): IdResponse
    {
        $this->logger->info('Executing create method', [
            'code' => $code,
            'description' => $description,
        ]);

        $status = new Status();
        $status->setCode($code);
        $status->setDescription($description);
        $status->setCreatedAt(new \DateTime());
        $status->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($status);
        $this->entityManager->flush();

        $response = new IdResponse($status->getId());
        $this->logger->info('create method executed successfully', ['response' => $response]);

        return $response;
    }

    public function update(int $id, ?string $code, ?string $description): IdResponse
    {
        $this->logger->info('Executing update method', [
            'id' => $id,
            'code' => $code,
            'description' => $description,
        ]);

        $status = $this->statusRepository->find($id);
        if (null === $status) {
            $this->logger->error('The status was not found', ['id' => $id]);
            throw new NotFoundHttpException('The status was not found.');
        }

        if (null !== $code) {
            $status->setCode($code);
        }
        if (null !== $description) {
            $status->setDescription($description);
        }

        $status->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        $response = new IdResponse($status->getId());
        $this->logger->info('update method executed successfully', ['response' => $response]);

        return $response;
    }

    public function delete(int $id): IdResponse
    {
        $this->logger->info('Executing delete method', ['id' => $id]);

        $status = $this->statusRepository->find($id);
        if (null === $status) {
            $this->logger->error('The status was not found', ['id' => $id]);
            throw new NotFoundHttpException('The status was not found.');
        }

        $this->entityManager->remove($status);
        $this->entityManager->flush();

        $response = new IdResponse($id);
        $this->logger->info('delete method executed successfully', ['response' => $response]);

        return $response;
    }
}
