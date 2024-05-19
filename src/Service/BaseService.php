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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BaseRepository $baseRepository,
        private readonly DepartmentRepository $departmentRepository
    ) {
    }

    public function show(string $order, string $orderField, int $limit, int $offset): ArrayResponse
    {
        if (!in_array($order, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(Base::class);

        if (!in_array($orderField, $fields)) {
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
            error_log('No data found');
        } else {
            error_log('Data found: '.print_r($bases, true));
        }

        return new ArrayResponse(array_map(
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
    }

    public function create(string $type, string $title, float $price, int $departmentId): IdResponse
    {
        $base = new Base();
        $base->setType($type);
        $base->setTitle($title);
        $base->setPrice($price);
        $department = $this->departmentRepository->find($departmentId);
        if (null === $department) {
            throw new NotFoundHttpException('Department not found');
        }
        $base->setDepartment($department);
        $base->setCreatedAt(new \DateTime());
        $base->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($base);
        $this->entityManager->flush();

        return new IdResponse($base->getId());
    }

    public function update(int $id, ?string $type, ?string $title, ?float $price, ?int $departmentId): IdResponse
    {
        $base = $this->baseRepository->find($id);
        if (null === $base) {
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
                throw new NotFoundHttpException('Department not found');
            }
            $base->setDepartment($department);
        }

        $base->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return new IdResponse($base->getId());
    }

    public function delete(int $id): IdResponse
    {
        $base = $this->baseRepository->find($id);
        if (null === $base) {
            throw new NotFoundHttpException('The base was not found.');
        }

        $this->entityManager->remove($base);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
