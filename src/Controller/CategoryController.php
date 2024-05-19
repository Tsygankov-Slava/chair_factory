<?php

namespace App\Controller;

use App\Service\CategoryService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    public function __construct(private readonly CategoryService $categoryService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return information about category",
     *
     *     @Model(type=ArrayResponse::class)
     * )
     *
     * @OA\Tag(name="User API")
     *
     * @OA\RequestBody(
     *      description="Data for show settings",
     *      required=true,
     *
     *      @OA\JsonContent(
     *          type="object",
     *
     *          @OA\Property(property="order", type="string", example="DESC"),
     *          @OA\Property(property="order_field", type="string", example="price"),
     *          @OA\Property(property="limit", type="integer", example="10"),
     *          @OA\Property(property="offset", type="integer", example="2")
     *      )
     *  )
     */
    #[Route(path: 'api/categories', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            if (isset($requestJSON['order']) and isset($requestJSON['order_field']) and isset($requestJSON['limit']) and isset($requestJSON['offset'])) {
                return new JsonResponse($this->categoryService->show($requestJSON['order'], $requestJSON['order_field'], $requestJSON['limit'], $requestJSON['offset']));
            }

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "order", "order_field", "limit" and "offset" fields are required.'],
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
     *     description="Create information about category",
     *
     *     @Model(type=ArrayResponse::class)
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *     description="Data about category (For creating)",
     *     required=true,
     *
     *     @OA\JsonContent(
     *         type="object",
     *
     *         @OA\Property(property="title", type="string", example="Test"),
     *         @OA\Property(property="base_id", type="integer", example="10"),
     *     )
     * )
     */
    #[Route(path: 'api/categories', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            if (isset($requestJSON['title']) && isset($requestJSON['base_id'])) {
                return new JsonResponse($this->categoryService->create($requestJSON['title'], $requestJSON['base_id']));
            }

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "title" and "base_id" fields are required.'],
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
     *     description="Update information about category",
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *      description="Data about category (For updating)",
     *      required=true,
     *
     *      @OA\JsonContent(
     *          type="object",
     *
     *          @OA\Property(property="title", type="string", example="__New_Test__"),
     *          @OA\Property(property="base_id", type="integer", example="9999")
     *      )
     * )
     */
    #[Route(path: 'api/categories/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            if (isset($requestJSON['title']) && isset($requestJSON['base_id'])) {
                return new JsonResponse($this->categoryService->update($id, $requestJSON['title'], $requestJSON['base_id']));
            }

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "title" and "base_id" fields are required.'],
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
     *     description="Delete information about category",
     * )
     *
     * @OA\Tag(name="Admin API")
     */
    #[Route(path: 'api/categories/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return new JsonResponse($this->categoryService->delete($id));
    }
}
