<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Department;
use App\Model\ArrayResponse;
use App\Model\DepartmentArrayItem;
use App\Model\IdResponse;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
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
        $fields = $helper->getEntityFields(Department::class);

        if (!in_array($orderField, $fields)) {
            $this->logger->error('Invalid order_field parameter', ['orderField' => $orderField]);
            throw new \InvalidArgumentException('Invalid order_field parameter');
        }

        $query = $this->entityManager->createQuery(
            'SELECT department
             FROM App\Entity\Department department
             ORDER BY department.'.$orderField.' '.$order
        )->setMaxResults($limit)
            ->setFirstResult($offset);

        $departments = $query->getResult();

        if (empty($departments)) {
            $this->logger->warning('No data found');
        } else {
            $this->logger->info('Data found', ['data' => $departments]);
        }

        $response = new ArrayResponse(array_map(
            fn (Department $department) => new DepartmentArrayItem(
                $department->getId(),
                $department->getName(),
                $department->getCode(),
                $department->getCreatedAt(),
                $department->getUpdatedAt()
            ),
            $departments
        ));

        $this->logger->info('show method executed successfully', ['response' => $response]);

        return $response;
    }

    public function create(string $name, int $code): IdResponse
    {
        $this->logger->info('Executing create method', ['name' => $name, 'code' => $code]);

        $department = new Department();
        $department->setName($name);
        $department->setCode($code);
        $department->setCreatedAt(new \DateTime());
        $department->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($department);
        $this->entityManager->flush();

        $response = new IdResponse($department->getId());
        $this->logger->info('create method executed successfully', ['response' => $response]);

        return $response;
    }

    public function update(int $id, ?string $name, ?int $code): IdResponse
    {
        $this->logger->info('Executing update method', ['id' => $id, 'name' => $name, 'code' => $code]);

        $department = $this->departmentRepository->find($id);
        if (null === $department) {
            $this->logger->error('Department not found', ['id' => $id]);
            throw new NotFoundHttpException('The department was not found.');
        }

        if (null !== $name) {
            $department->setName($name);
        }
        if (null !== $code) {
            $department->setCode($code);
        }

        $department->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        $response = new IdResponse($department->getId());
        $this->logger->info('update method executed successfully', ['response' => $response]);

        return $response;
    }

    public function delete(int $id): IdResponse
    {
        $this->logger->info('Executing delete method', ['id' => $id]);

        $department = $this->departmentRepository->find($id);
        if (null === $department) {
            $this->logger->error('Department not found', ['id' => $id]);
            throw new NotFoundHttpException('The department was not found.');
        }

        $this->entityManager->remove($department);
        $this->entityManager->flush();

        $response = new IdResponse($id);
        $this->logger->info('delete method executed successfully', ['response' => $response]);

        return $response;
    }
}
