<?php

namespace App\Controller;

use App\Service\MaterialService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class MaterialController extends AbstractController
{
    public function __construct(private readonly MaterialService $chairBaseMaterialService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return information about chair base materials",
     *     @Model(type=ChairMaterialArrayResponse::class)
     * )
     *
     * @OA\Tag(name="User API")
     */
    #[Route(path: 'api/chair-base-materials', methods: ['GET'])]
    public function show(): JsonResponse
    {
        return new JsonResponse($this->chairBaseMaterialService->show());
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create information about chair base materials",
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *       description="Data about chair base materials (For creating)",
     *       required=true,
     *       @OA\JsonContent(
     *           type="object",
     *           @OA\Property(property="name", type="string", example="Test"),
     *           @OA\Property(property="price", type="string", example="1000")
     *       )
     * )
     */
    #[Route(path: 'api/chair-base-materials', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            if (isset($requestJSON["name"]) && isset($requestJSON["price"])) {
                return new JsonResponse($this->chairBaseMaterialService->create($requestJSON["name"], $requestJSON["price"]));
            }
            return new JsonResponse(
                ['error' => 'Invalid JSON format. "name" and "price" fields are required.'],
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

    /**
     * @OA\Response(
     *     response=200,
     *     description="Update information about chair base materials",
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *        description="Data about chair base materials (For updating)",
     *        required=true,
     *        @OA\JsonContent(
     *            type="object",
     *            @OA\Property(property="name", type="string", example="__New_Test__"),
     *            @OA\Property(property="price", type="string", example="9999")
     *        )
     * )
     */
    #[Route(path: 'api/chair-base-materials/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            if (isset($requestJSON["name"]) && isset($requestJSON["price"])) {
                return new JsonResponse($this->chairBaseMaterialService->update($id, $requestJSON["name"], $requestJSON["price"]));
            }
            return new JsonResponse(
                ['error' => 'Invalid JSON format. "name" and "price" fields are required.'],
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

    /**
     * @OA\Response(
     *     response=200,
     *     description="Delete information about chair base materials",
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     */
    #[Route(path: 'api/chair-base-materials/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return new JsonResponse($this->chairBaseMaterialService->delete($id));
    }
}
