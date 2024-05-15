<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class OrderController extends AbstractController
{
    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return information about orders for user",
     *     @Model(type=OrderArrayResponse::class)
     * )
     *
     * @OA\Tag(name="User API")
     */
    #[Route(path: 'api/orders/{userId}', methods: ['GET'])]
    public function show(int $userId): JsonResponse
    {
        return new JsonResponse($this->orderService->show($userId));
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create new order",
     * )
     *
     * @OA\Tag(name="User API")
     *
     * @OA\RequestBody(
     *         description="Data about chair upholstery materials (For creating)",
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="userId", type="int", example="1"),
     *             @OA\Property(property="status", type="string", example="OK"),
     *             @OA\Property(property="basicChairIdArray", type="array", example={1, 2, 3},
     *                 @OA\Items(type="integer")),
     *             @OA\Property(property="chairBaseMaterialIdArray", type="array", example={1, 2, 3},
     *                 @OA\Items(type="integer")),
     *             @OA\Property(property="chairUpholsteryMaterialArray", type="array", example={1, 2, 3},
     *                 @OA\Items(type="integer")),
     *             @OA\Property(property="chairsQuantityArray", type="array", example={1, 2, 3},
     *                 @OA\Items(type="integer"))
     *         )
     *   )
     */
    #[Route(path: 'api/orders', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            if (isset($requestJSON["userId"]) && isset($requestJSON["status"]) && isset($requestJSON["basicChairIdArray"])
                && isset($requestJSON["chairBaseMaterialIdArray"]) && isset($requestJSON["chairUpholsteryMaterialArray"])
                && isset($requestJSON["chairsQuantityArray"])) {
                return new JsonResponse($this->orderService->create(
                    $requestJSON["userId"],
                    $requestJSON["status"],
                    $requestJSON["basicChairIdArray"],
                    $requestJSON["chairBaseMaterialIdArray"],
                    $requestJSON["chairUpholsteryMaterialArray"],
                    $requestJSON["chairsQuantityArray"]
                ));
            }
            return new JsonResponse(
                ['error' => 'Invalid JSON format. "userId", "status", "basicChairIdArray", "chairBaseMaterialIdArray",
                 "chairUpholsteryMaterialArray" and "chairsQuantityArray" fields are required.'],
                400,
                [],
                true
            );
        }
        return new JsonResponse(
            ['error' => 'Invalid JSON format.'],
            400,
            [],
            true
        );
    }
}
