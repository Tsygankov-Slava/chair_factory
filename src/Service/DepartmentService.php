<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Department;
use App\Model\ArrayResponse;
use App\Model\DepartmentArrayItem;
use App\Model\IdResponse;
use App\Repository\DepartmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentService
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
        private readonly DepartmentRepository $departmentRepository)
    {
    }

    public function show(string $order, string $orderField, int $limit, int $offset): ArrayResponse
    {
        if (!in_array($order, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(Department::class);

        if (!in_array($orderField, $fields)) {
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
            error_log('No data found');
        } else {
            error_log('Data found: '.print_r($departments, true));
        }

        return new ArrayResponse(array_map(
            fn (Department $department) => new DepartmentArrayItem(
                $department->getId(),
                $department->getName(),
                $department->getCode(),
                $department->getCreatedAt(),
                $department->getUpdatedAt()
            ),
            $departments
        ));
    }

    public function create(string $name, int $code): IdResponse
    {
        $department = new Department();
        $department->setName($name);
        $department->setCode($code);
        $department->setCreatedAt(new \DateTime());
        $department->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($department);
        $this->entityManager->flush();

        return new IdResponse($department->getId());
    }

    public function update(int $id, ?string $name, ?int $code): IdResponse
    {
        $department = $this->departmentRepository->find($id);
        if (null === $department) {
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

        return new IdResponse($department->getId());
    }

    public function delete(int $id): IdResponse
    {
        $department = $this->departmentRepository->find($id);
        if (null === $department) {
            throw new NotFoundHttpException('The department was not found.');
        }

        $this->entityManager->remove($department);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
