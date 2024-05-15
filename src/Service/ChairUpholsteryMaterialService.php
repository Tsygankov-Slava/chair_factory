<?php

namespace App\Service;

use App\Entity\ChairBaseMaterial;
use App\Entity\ChairUpholsteryMaterial;
use App\Model\ChairMaterialArrayItem;
use App\Model\ChairMaterialArrayResponse;
use App\Model\IdResponse;
use App\Repository\ChairUpholsteryMaterialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ChairUpholsteryMaterialService
{
    public function __construct(private readonly ChairUpholsteryMaterialRepository $chairUpholsteryMaterialRepository,
                                private readonly EntityManagerInterface            $entityManager)
    {
    }

    public function show(): ChairMaterialArrayResponse
    {
        $chairBaseMaterials = $this->chairUpholsteryMaterialRepository->findAll();
        return new ChairMaterialArrayResponse(array_map(
            fn (ChairUpholsteryMaterial $chairUpholsteryMaterial) => new ChairMaterialArrayItem(
                $chairUpholsteryMaterial->getId(),
                $chairUpholsteryMaterial->getName(),
                $chairUpholsteryMaterial->getPrice()
            ), $chairBaseMaterials));
    }

    public function create(string $name, float $price): IdResponse
    {
        $chairBaseMaterial = new ChairUpholsteryMaterial();
        $chairBaseMaterial->setName($name);
        $chairBaseMaterial->setPrice($price);

        $this->entityManager->persist($chairBaseMaterial);
        $this->entityManager->flush();

        return new IdResponse($chairBaseMaterial->getId());
    }

    public function update(int $id, ?string $name, ?float $price): IdResponse
    {
        $chairBaseMaterial = $this->chairUpholsteryMaterialRepository->find($id);
        if (null === $chairBaseMaterial) {
            throw new NotFoundHttpException('The chair upholstery material was not found.');
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
        $chairBaseMaterial = $this->chairUpholsteryMaterialRepository->find($id);
        if (null === $chairBaseMaterial) {
            throw new NotFoundHttpException('The chair upholstery material was not found.');
        }

        $this->entityManager->remove($chairBaseMaterial);
        $this->entityManager->flush();

        return new IdResponse($id);
    }
}
