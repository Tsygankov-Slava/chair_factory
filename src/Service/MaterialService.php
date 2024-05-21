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
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MaterialService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly CategoryRepository $categoryRepository,
        private readonly MaterialRepository $materialRepository,
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
        $fields = $helper->getEntityFields(Material::class);

        if (!in_array($orderField, $fields)) {
            $this->logger->error('Invalid order_field parameter', ['orderField' => $orderField]);
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
            $this->logger->warning('No data found');
        } else {
            $this->logger->info('Data found', ['data' => $materials]);
        }

        $response = new ArrayResponse(array_map(
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

        $this->logger->info('show method executed successfully', ['response' => $response]);

        return $response;
    }

    public function create(string $type, string $title, float $price, int $categoryCode, int $categoryId): IdResponse
    {
        $this->logger->info('Executing create method', [
            'type' => $type,
            'title' => $title,
            'price' => $price,
            'categoryCode' => $categoryCode,
            'categoryId' => $categoryId,
        ]);

        $material = new Material();
        $material->setType($type);
        $material->setTitle($title);
        $material->setPrice($price);
        $material->setCategoryCode($categoryCode);
        $category = $this->categoryRepository->find($categoryId);
        if (null === $category) {
            $this->logger->error('Category not found', ['categoryId' => $categoryId]);
            throw new NotFoundHttpException('Category not found');
        }
        $material->setCategory($category);
        $material->setCreatedAt(new \DateTime());
        $material->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($material);
        $this->entityManager->flush();

        $response = new IdResponse($material->getId());
        $this->logger->info('create method executed successfully', ['response' => $response]);

        return $response;
    }

    public function update(int $id, ?string $type, ?string $title, ?float $price, ?int $categoryCode, ?int $categoryId): IdResponse
    {
        $this->logger->info('Executing update method', [
            'id' => $id,
            'type' => $type,
            'title' => $title,
            'price' => $price,
            'categoryCode' => $categoryCode,
            'categoryId' => $categoryId,
        ]);

        $material = $this->materialRepository->find($id);
        if (null === $material) {
            $this->logger->error('Material not found', ['id' => $id]);
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
                $this->logger->error('Category not found', ['categoryId' => $categoryId]);
                throw new NotFoundHttpException('Category not found');
            }
            $material->setCategory($category);
        }

        $material->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        $response = new IdResponse($material->getId());
        $this->logger->info('update method executed successfully', ['response' => $response]);

        return $response;
    }

    public function delete(int $id): IdResponse
    {
        $this->logger->info('Executing delete method', ['id' => $id]);

        $material = $this->materialRepository->find($id);
        if (null === $material) {
            $this->logger->error('Material not found', ['id' => $id]);
            throw new NotFoundHttpException('The material was not found.');
        }

        $this->entityManager->remove($material);
        $this->entityManager->flush();

        $response = new IdResponse($id);
        $this->logger->info('delete method executed successfully', ['response' => $response]);

        return $response;
    }
}
