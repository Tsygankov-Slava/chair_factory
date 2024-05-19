<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Material;
use App\Model\ArrayResponse;
use App\Model\IdResponse;
use App\Model\MaterialArrayItem;
use App\Repository\CategoryRepository;
use App\Repository\MaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MaterialService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CategoryRepository $categoryRepository,
        private readonly MaterialRepository $materialRepository
    ) {
    }

    public function show(string $order, string $orderField, int $limit, int $offset): ArrayResponse
    {
        if (!in_array($order, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(Material::class);

        if (!in_array($orderField, $fields)) {
            throw new \InvalidArgumentException('Invalid order_field parameter');
        }

        $query = $this->entityManager->createQuery(
            'SELECT material, category, base, department
             FROM App\Entity\Material material
             JOIN material.category category
             JOIN category.base base
             JOIN base.department department
             ORDER BY base.'.$orderField.' '.$order
        )
            ->setMaxResults($limit)
            ->setFirstResult($offset);

        $materials = $query->getResult();

        if (empty($materials)) {
            error_log('No data found');
        } else {
            error_log('Data found: '.print_r($materials, true));
        }

        return new ArrayResponse(array_map(
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
            $materials
        ));
    }

    public function create(string $type, string $title, float $price, int $categoryCode, int $categoryId): IdResponse
    {
        $material = new Material();
        $material->setType($type);
        $material->setTitle($title);
        $material->setPrice($price);
        $material->setCategoryCode($categoryCode);
        $category = $this->categoryRepository->find($categoryId);
        if (null === $category) {
            throw new NotFoundHttpException('Category not found');
        }
        $material->setCategory($category);
        $material->setCreatedAt(new \DateTime());
        $material->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($material);
        $this->entityManager->flush();

        return new IdResponse($material->getId());
    }

    public function update(int $id, ?string $type, ?string $title, ?float $price, ?int $categoryCode, ?int $categoryId): IdResponse
    {
        $material = $this->materialRepository->find($id);
        if (null === $material) {
            throw new NotFoundHttpException('The material was not found.');
        }

        if (null !== $type) {
            $material->setType($type);
        }
        if (null !== $title) {
            $material->setTitle($title);
        }
        if (null !== $price) {
            $material->setPrice($price);
        }
        if (null !== $categoryCode) {
            $material->setCategoryCode($categoryCode);
        }
        if (null !== $categoryId) {
            $category = $this->categoryRepository->find($categoryId);
            if (null === $category) {
                throw new NotFoundHttpException('Category not found');
            }
            $material->setCategory($category);
        }

        $material->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return new IdResponse($material->getId());
    }

    public function delete(int $id): IdResponse
    {
        $material = $this->materialRepository->find($id);
        if (null === $material) {
            throw new NotFoundHttpException('The material was not found.');
        }

        $this->entityManager->remove($material);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
