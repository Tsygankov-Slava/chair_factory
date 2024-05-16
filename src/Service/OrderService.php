<?php

namespace App\Service;

use App\Entity\Order;
use App\Exception\NotFoundException;
use App\Model\ChairInOrder;
use App\Model\IdResponse;
use App\Model\OrderArrayItem;
use App\Model\OrderArrayResponse;
use App\Repository\BaseRepository;
use App\Repository\MaterialRepository;
use App\Repository\ChairUpholsteryMaterialRepository;
use App\Repository\OrderRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;

class OrderService
{
    public function __construct(
        private readonly OrderRepository                   $orderRepository,
        private readonly BaseRepository                    $basicChairRepository,
        private readonly MaterialRepository                $chairBaseMaterialRepository,
        private readonly EntityManagerInterface            $entityManager
    ) {
    }

    public function show(int $userId): OrderArrayResponse|NotFoundException
    {
        $orders = $this->orderRepository->findBy(['userId' => $userId]);
        if ($orders) {
            return new NotFoundException("This user doesn't have any orders");
        }
        return new OrderArrayResponse(array_map(
            fn (Order $order) => new OrderArrayItem(
                $order->getId(),
                $order->getStatus(),
                $this->getChairInOrderArray(
                    $order->getBasicChairIdArray(),
                    $order->getChairBaseMaterialIdArray(),
                    $order->getChairUpholsteryMaterialArray(),
                    $order->getChairsQuantityArray()
                ),
                $order->getPrice(),
                $order->getCreatedAt(),
                $order->getUpdatedAt()
            ),
            $orders
        ));
    }

    public function create(
        int $userId,
        string $status,
        array $basicChairIdArray,
        array $chairBaseMaterialIdArray,
        array $chairUpholsteryMaterialIdArray,
        array $chairsQuantityArray
    ): IdResponse {
        $order = new Order();
        $order->setUserId($userId);
        $order->setStatus($status);
        $order->setBasicChairIdArray($basicChairIdArray);
        $order->setChairBaseMaterialIdArray($chairBaseMaterialIdArray);
        $order->setChairUpholsteryMaterialArray($chairUpholsteryMaterialIdArray);
        $order->setChairsQuantityArray($chairsQuantityArray);
        $order->setPrice($this->countPrice(
            $basicChairIdArray,
            $chairBaseMaterialIdArray,
            $chairUpholsteryMaterialIdArray,
            $chairsQuantityArray
        ));
        $timeNow = new DateTime();
        $order ->setCreatedAt($timeNow);
        $order ->setUpdatedAt($timeNow);


        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return new IdResponse($order->getId());
    }

    private function countPrice(
        array $basicChairIdArray,
        array $chairBaseMaterialIdArray,
        array $chairUpholsteryMaterialIdArray,
        array $chairsQuantityArray
    ): float {
        $price = 0;
        for ($i = 0; $i < count($basicChairIdArray); $i++) {
            $basicChairId = $basicChairIdArray[$i];
            $basicChairPrice = ($this->basicChairRepository->find($basicChairId))->getPrice();

            $chairBaseMaterialId = $chairBaseMaterialIdArray[$i];
            $chairBaseMaterialPrice = ($this->chairBaseMaterialRepository->find($chairBaseMaterialId))->getPrice();

            $chairUpholsteryMaterialId = $chairUpholsteryMaterialIdArray[$i];
            $chairUpholsteryMaterialPrice = ($this->chairUpholsteryMaterialRepository->find($chairUpholsteryMaterialId))->getPrice();

            $chairsQuantity = $chairsQuantityArray[$i];

            $price += ($basicChairPrice + $chairBaseMaterialPrice + $chairUpholsteryMaterialPrice) * $chairsQuantity;
        }
        return $price;
    }

    /*
     * @return ChairInOrder[]
     */
    private function getChairInOrderArray(
        array $basicChairIdArray,
        array $chairBaseMaterialIdArray,
        array $chairUpholsteryMaterialArray,
        array $chairsQuantityArray
    ): array {
        /*
         * @var ChairInOrder[] $result
         */
        $result = [];
        for ($i = 0; $i < count($basicChairIdArray); $i++) {
            $basicChairId = $basicChairIdArray[$i];
            $basicChair = $this->basicChairRepository->find($basicChairId);

            $chairBaseMaterialId = $chairBaseMaterialIdArray[$i];
            $chairBaseMaterial = $this->chairBaseMaterialRepository->find($chairBaseMaterialId);

            $chairUpholsteryMaterialId = $chairUpholsteryMaterialArray[$i];
            $chairUpholsteryMaterial = $this->chairUpholsteryMaterialRepository->find($chairUpholsteryMaterialId);

            $chairsQuantityId = $chairsQuantityArray[$i];
            $result[] = new ChairInOrder($basicChair->getType(), $chairBaseMaterial->getName(), $chairUpholsteryMaterial->getName(), $chairsQuantityId);
        }
        return $result;
    }
}
