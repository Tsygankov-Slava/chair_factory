<?php

namespace App\Service;

use App\Controller\EntityFieldHelper;
use App\Entity\Category;
use App\Model\ArrayResponse;
use App\Model\CategoryArrayItem;
use App\Model\IdResponse;
use App\Repository\BaseRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
        private readonly BaseRepository $baseRepository,
        private readonly CategoryRepository $categoryRepository)
    {
    }

    public function show(string $order, string $orderField, int $limit, int $offset): ArrayResponse
    {
        if (!in_array($order, ['ASC', 'DESC'])) {
            throw new \InvalidArgumentException('Invalid order parameter');
        }

        $helper = new EntityFieldHelper($this->entityManager);
        $fields = $helper->getEntityFields(Category::class);

        if (!in_array($orderField, $fields)) {
            throw new \InvalidArgumentException('Invalid order_field parameter');
        }

        $query = $this->entityManager->createQuery(
            'SELECT category, base, department
             FROM App\Entity\Category category
             JOIN category.base base
             JOIN base.department department
             ORDER BY category.'.$orderField.' '.$order
        )->setMaxResults($limit)
         ->setFirstResult($offset);

        $categories = $query->getResult();

        if (empty($categories)) {
            error_log('No data found');
        } else {
            error_log('Data found: '.print_r($categories, true));
        }

        return new ArrayResponse(array_map(
            fn (Category $category) => new CategoryArrayItem(
                $category->getId(),
                $category->getTitle(),
                $category->getBase()->getId(),
                $category->getCreatedAt(),
                $category->getUpdatedAt()
            ),
            $categories
        ));
    }

    public function create(string $title, int $baseId): IdResponse
    {
        $category = new Category();
        $category->setTitle($title);
        $base = $this->baseRepository->find($baseId);
        if (null === $base) {
            throw new NotFoundHttpException('Base not found');
        }
        $category->setBase($base);
        $category->setCreatedAt(new \DateTime());
        $category->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return new IdResponse($category->getId());
    }

    public function update(int $id, ?string $title, ?int $baseId): IdResponse
    {
        $category = $this->categoryRepository->find($id);
        if (null === $category) {
            throw new NotFoundHttpException('The category was not found.');
        }

        if (null !== $title) {
            $category->setTitle($title);
        }
        if (null !== $baseId) {
            $base = $this->baseRepository->find($baseId);
            if (null === $base) {
                throw new NotFoundHttpException('Base not found');
            }
            $category->setBase($base);
        }

        $category->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        return new IdResponse($category->getId());
    }

    public function delete(int $id): IdResponse
    {
        $category = $this->categoryRepository->find($id);
        if (null === $category) {
            throw new NotFoundHttpException('The category was not found.');
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
