<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Base;
use App\Model\ArrayResponse;
use App\Model\BaseArrayItem;
use App\Model\IdResponse;
use App\Repository\BaseRepository;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BaseRepository $baseRepository,
        private readonly DepartmentRepository $departmentRepository,
        private readonly LoggerInterface $logger
    ) {
    }

    public function show(string $order, string $orderField, int $limit, int $offset): ArrayResponse
    {
        $this->logger->info('Executing show method', ['order' => $order, 'orderField' => $orderField, 'limit' => $limit, 'offset' => $offset]);

        if (!in_array($order, ['ASC', 'DESC'])) {
            $this->logger->error('Invalid order parameter', ['order' => $order]);
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(Base::class);

        if (!in_array($orderField, $fields)) {
            $this->logger->error('Invalid order_field parameter', ['orderField' => $orderField]);
            throw new \InvalidArgumentException('Invalid order_field parameter');
        }

        $query = $this->entityManager->createQuery(
            'SELECT base, department
             FROM App\Entity\Base base
             JOIN base.department department
             ORDER BY base.'.$orderField.' '.$order
        )
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $bases = $query->getResult();

        if (empty($bases)) {
            $this->logger->warning('No data found');
        } else {
            $this->logger->info('Data found', ['data' => $bases]);
        }

        $response = new ArrayResponse(array_map(
            fn (Base $base) => new BaseArrayItem(
                $base->getId(),
                $base->getType(),
                $base->getTitle(),
                $base->getPrice(),
                $base->getDepartment()->getId(),
                $base->getCreatedAt(),
                $base->getUpdatedAt()
            ),
            $bases
        ));

        $this->logger->info('show method executed successfully', ['response' => $response]);

        return $response;
    }

    public function create(string $type, string $title, float $price, int $departmentId): IdResponse
    {
        $this->logger->info('Executing create method', ['type' => $type, 'title' => $title, 'price' => $price, 'departmentId' => $departmentId]);

        $base = new Base();
        $base->setType($type);
        $base->setTitle($title);
        $base->setPrice($price);

        $department = $this->departmentRepository->find($departmentId);
        if (null === $department) {
            $this->logger->error('Department not found', ['departmentId' => $departmentId]);
            throw new NotFoundHttpException('Department not found');
        }
        $base->setDepartment($department);
        $base->setCreatedAt(new \DateTime());
        $base->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($base);
        $this->entityManager->flush();

        $response = new IdResponse($base->getId());
        $this->logger->info('create method executed successfully', ['response' => $response]);

        return $response;
    }

    public function update(int $id, ?string $type, ?string $title, ?float $price, ?int $departmentId): IdResponse
    {
        $this->logger->info('Executing update method', ['id' => $id, 'type' => $type, 'title' => $title, 'price' => $price, 'departmentId' => $departmentId]);

        $base = $this->baseRepository->find($id);
        if (null === $base) {
            $this->logger->error('Base not found', ['id' => $id]);
            throw new NotFoundHttpException('The base was not found.');
        }

        if (null !== $type) {
            $base->setType($type);
        }
        if (null !== $title) {
            $base->setTitle($title);
        }
        if (null !== $price) {
            $base->setPrice($price);
        }
        if (null !== $departmentId) {
            $department = $this->departmentRepository->find($departmentId);
            if (null === $department) {
                $this->logger->error('Department not found', ['departmentId' => $departmentId]);
                throw new NotFoundHttpException('Department not found');
            }
            $base->setDepartment($department);
        }

        $base->setUpdatedAt(new \DateTime());
        $this->entityManager->flush();

        $response = new IdResponse($base->getId());
        $this->logger->info('update method executed successfully', ['response' => $response]);

        return $response;
    }

    public function delete(int $id): IdResponse
    {
        $this->logger->info('Executing delete method', ['id' => $id]);

        $base = $this->baseRepository->find($id);
        if (null === $base) {
            $this->logger->error('Base not found', ['id' => $id]);
            throw new NotFoundHttpException('The base was not found.');
        }

        $this->entityManager->remove($base);
        $this->entityManager->flush();

        $response = new IdResponse($id);
        $this->logger->info('delete method executed successfully', ['response' => $response]);

        return $response;
    }
}
