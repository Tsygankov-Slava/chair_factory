<?php

namespace App\Service;

use App\Entity\Base;
use App\Model\BasicChairArrayItem;
use App\Model\BasicChairArrayResponse;
use App\Model\IdResponse;
use App\Repository\BaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseService
{
    public function __construct(
        private readonly BaseRepository         $basicChairRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    public function show(): BasicChairArrayResponse
    {
        $basicChairs = $this->basicChairRepository->findAll();
        return new BasicChairArrayResponse(array_map(
            fn (Base $basicChair) => new BasicChairArrayItem(
                $basicChair->getId(),
                $basicChair->getType(),
                $basicChair->getPrice()
            ),
            $basicChairs
        ));
    }

    public function create(string $type, float $price): IdResponse
    {
        $basicChair = new Base();
        $basicChair->setType($type);
        $basicChair->setPrice($price);

        $this->entityManager->persist($basicChair);
        $this->entityManager->flush();

        return new IdResponse($basicChair->getId());
    }

    public function update(int $id, ?string $type, ?float $price): IdResponse
    {
        $basicChair = $this->basicChairRepository->find($id);
        if (null === $basicChair) {
            throw new NotFoundHttpException('The basic chair was not found.');
        }

        if (null !== $type) {
            $basicChair->setType($type);
        }
        if (null !== $price) {
            $basicChair->setPrice($price);
        }
        $this->entityManager->flush();

        return new IdResponse($basicChair->getId());
    }

    public function delete(int $id): IdResponse
    {
        $basicChair = $this->basicChairRepository->find($id);
        if (null === $basicChair) {
            throw new NotFoundHttpException('The basic chair was not found.');
        }

        $this->entityManager->remove($basicChair);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
