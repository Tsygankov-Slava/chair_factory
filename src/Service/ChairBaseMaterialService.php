<?php

namespace App\Service;

use App\Entity\ChairBaseMaterial;
use App\Model\ChairMaterialArrayItem;
use App\Model\ChairMaterialArrayResponse;
use App\Model\IdResponse;
use App\Repository\ChairBaseMaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChairBaseMaterialService
{
    public function __construct(private ChairBaseMaterialRepository $chairBaseMaterialRepository,
                                private EntityManagerInterface $entityManager)
    {
    }

    public function show(): ChairMaterialArrayResponse
    {
        $chairBaseMaterials = $this->chairBaseMaterialRepository->findAll();
        return new ChairMaterialArrayResponse(array_map(
            fn (ChairBaseMaterial $chairBaseMaterial) => new ChairMaterialArrayItem(
                $chairBaseMaterial->getId(),
                $chairBaseMaterial->getName(),
                $chairBaseMaterial->getPrice()
            ), $chairBaseMaterials
        ));
    }

    public function create(string $name, float $price): IdResponse
    {
        $chairBaseMaterial = new ChairBaseMaterial();
        $chairBaseMaterial->setName($name);
        $chairBaseMaterial->setPrice($price);

        $this->entityManager->persist($chairBaseMaterial);
        $this->entityManager->flush();

        return new IdResponse($chairBaseMaterial->getId());
    }

    public function update(int $id, ?string $name, ?float $price): IdResponse
    {
        $chairBaseMaterial = $this->chairBaseMaterialRepository->find($id);
        if (null === $chairBaseMaterial) {
            throw new NotFoundHttpException('The chair base material was not found.');
        }

        if (null !== $name) {
            $chairBaseMaterial->setName($name);
        }
        if (null !== $price) {
            $chairBaseMaterial->setPrice($price);
        }
        $this->entityManager->flush();

        return new IdResponse($chairBaseMaterial->getId());
    }

    public function delete(int $id): IdResponse
    {
        $chairBaseMaterial = $this->chairBaseMaterialRepository->find($id);
        if (null === $chairBaseMaterial) {
            throw new NotFoundHttpException('The chair base material was not found.');
        }

        $this->entityManager->remove($chairBaseMaterial);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
