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
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BaseRepository $baseRepository,
        private readonly CategoryRepository $categoryRepository,
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
        $fields = $helper->getEntityFields(Category::class);

        if (!in_array($orderField, $fields)) {
            $this->logger->error('Invalid order_field parameter', ['orderField' => $orderField]);
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
            $this->logger->warning('No data found');
        } else {
            $this->logger->info('Data found', ['data' => $categories]);
        }

        $response = new ArrayResponse(array_map(
            fn (Category $category) => new CategoryArrayItem(
                $category->getId(),
                $category->getTitle(),
                $category->getBase()->getId(),
                $category->getCreatedAt(),
                $category->getUpdatedAt()
            ),
            $categories
        ));

        $this->logger->info('show method executed successfully', ['response' => $response]);

        return $response;
    }

    public function create(string $title, int $baseId): IdResponse
    {
        $this->logger->info('Executing create method', ['title' => $title, 'baseId' => $baseId]);

        $category = new Category();
        $category->setTitle($title);
        $base = $this->baseRepository->find($baseId);
        if (null === $base) {
            $this->logger->error('Base not found', ['baseId' => $baseId]);
            throw new NotFoundHttpException('Base not found');
        }
        $category->setBase($base);
        $category->setCreatedAt(new \DateTime());
        $category->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $response = new IdResponse($category->getId());
        $this->logger->info('create method executed successfully', ['response' => $response]);

        return $response;
    }

    public function update(int $id, ?string $title, ?int $baseId): IdResponse
    {
        $this->logger->info('Executing update method', ['id' => $id, 'title' => $title, 'baseId' => $baseId]);

        $category = $this->categoryRepository->find($id);
        if (null === $category) {
            $this->logger->error('Category not found', ['id' => $id]);
            throw new NotFoundHttpException('The category was not found.');
        }

        if (null !== $title) {
            $category->setTitle($title);
        }
        if (null !== $baseId) {
            $base = $this->baseRepository->find($baseId);
            if (null === $base) {
                $this->logger->error('Base not found', ['baseId' => $baseId]);
                throw new NotFoundHttpException('Base not found');
            }
            $category->setBase($base);
        }

        $category->setUpdatedAt(new \DateTime());

        $this->entityManager->flush();

        $response = new IdResponse($category->getId());
        $this->logger->info('update method executed successfully', ['response' => $response]);

        return $response;
    }

    public function delete(int $id): IdResponse
    {
        $this->logger->info('Executing delete method', ['id' => $id]);

        $category = $this->categoryRepository->find($id);
        if (null === $category) {
            $this->logger->error('Category not found', ['id' => $id]);
            throw new NotFoundHttpException('The category was not found.');
        }

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $response = new IdResponse($id);
        $this->logger->info('delete method executed successfully', ['response' => $response]);

        return $response;
    }
}
