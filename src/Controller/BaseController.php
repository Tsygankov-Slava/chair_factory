<?php

namespace App\Controller;

use App\Service\BaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class BaseController extends AbstractController
{
    public function __construct(private readonly BaseService $basicChairService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return information about basic chairs",
     *     @Model(type=BasicChairArrayResponse::class)
     * )
     *
     * @OA\Tag(name="User API")
     */
    #[Route(path: 'api/basic-chairs', methods: ['GET'])]
    public function show(): JsonResponse
    {
        return new JsonResponse($this->basicChairService->show());
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Create information about basic chairs",
     *     @Model(type=BasicChairArrayResponse::class)
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *     description="Data about basic chairs (For creating)",
     *     required=true,
     *     @OA\JsonContent(
     *         type="object",
     *         @OA\Property(property="type", type="string", example="Test"),
     *         @OA\Property(property="price", type="string", example="1000")
     *     )
     * )
     */
    #[Route(path: 'api/basic-chairs', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            if (isset($requestJSON["type"]) && isset($requestJSON["price"])) {
                return new JsonResponse($this->basicChairService->create($requestJSON["type"], $requestJSON["price"]));
            }
            return new JsonResponse(
                ['error' => 'Invalid JSON format. "type" and "price" fields are required.'],
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
     *     description="Update information about basic chairs",
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *      description="Data about basic chairs (For updating)",
     *      required=true,
     *      @OA\JsonContent(
     *          type="object",
     *          @OA\Property(property="type", type="string", example="__New_Test__"),
     *          @OA\Property(property="price", type="string", example="9999")
     *      )
     * )
     */
    #[Route(path: 'api/basic-chairs/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (json_last_error() == JSON_ERROR_NONE) {
            if (isset($requestJSON["type"]) && isset($requestJSON["price"])) {
                return new JsonResponse($this->basicChairService->update($id, $requestJSON["type"], $requestJSON["price"]));
            }
            return new JsonResponse(
                ['error' => 'Invalid JSON format. "type" and "price" fields are required.'],
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
     *     description="Delete information about basic chairs",
     * )
     *
     * @OA\Tag(name="Admin API")
     */
    #[Route(path: 'api/basic-chairs/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return new JsonResponse($this->basicChairService->delete($id));
    }
}
