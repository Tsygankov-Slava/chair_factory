<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Status;
use App\Model\ArrayResponse;
use App\Model\IdResponse;
use App\Model\StatusArrayItem;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StatusService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly StatusRepository $statusRepository
    ) {
    }

    public function show(string $order, string $orderField, int $limit, int $offset): ArrayResponse
    {
        if (!in_array($order, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(Status::class);

        if (!in_array($orderField, $fields)) {
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
            error_log('No data found');
        } else {
            error_log('Data found: '.print_r($statuses, true));
        }

        return new ArrayResponse(array_map(
            fn (Status $status) => new StatusArrayItem(
                $status->getId(),
                $status->getCode(),
                $status->getDescription(),
                $status->getCreatedAt(),
                $status->getUpdatedAt()
            ),
            $statuses
        ));
    }

    public function create(string $code, string $description): IdResponse
    {
        $status = new Status();
        $status->setCode($code);
        $status->setDescription($description);
        $status->setCreatedAt(new \DateTime());
        $status->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($status);
        $this->entityManager->flush();

        return new IdResponse($status->getId());
    }

    public function update(int $id, ?string $code, ?string $description): IdResponse
    {
        $status = $this->statusRepository->find($id);
        if (null === $status) {
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

        return new IdResponse($status->getId());
    }

    public function delete(int $id): IdResponse
    {
        $status = $this->statusRepository->find($id);
        if (null === $status) {
            throw new NotFoundHttpException('The status was not found.');
        }

        $this->entityManager->remove($status);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
