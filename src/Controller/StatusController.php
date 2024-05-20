<?php

namespace App\Controller;

use App\Service\BaseService;
use App\Service\StatusService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;

class StatusController extends AbstractController
{
    public function __construct(private readonly StatusService $statusService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return information about statuses",
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
    #[Route(path: 'api/statuses', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            if (isset($requestJSON['order']) and isset($requestJSON['order_field']) and isset($requestJSON['limit']) and isset($requestJSON['offset'])) {
                return new JsonResponse($this->statusService->show($requestJSON['order'], $requestJSON['order_field'], $requestJSON['limit'], $requestJSON['offset']));
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
     *     description="Create information about status",
     *
     *     @Model(type=ArrayResponse::class)
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *     description="Data about status (For creating)",
     *     required=true,
     *
     *     @OA\JsonContent(
     *         type="object",
     *
     *         @OA\Property(property="code", type="string", example="Ok"),
     *         @OA\Property(property="description", type="string", example="Заказ готов")
     *     )
     * )
     */
    #[Route(path: 'api/statuses', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            if (isset($requestJSON['code']) && isset($requestJSON['description'])) {
                return new JsonResponse($this->statusService->create($requestJSON['code'], $requestJSON['description']));
            }

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "code" and "description" fields are required.'],
                400,
                [],
                false
            );
        }

        return new JsonResponse(
            ['error' => 'Invalid JSON format.'],
            400,
            [],
            false
        );
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Update information about status",
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *      description="Data about status (For updating)",
     *      required=true,
     *
     *      @OA\JsonContent(
     *          type="object",
     *
     *         @OA\Property(property="code", type="string", example="Ok"),
     *         @OA\Property(property="description", type="string", example="Заказ готов")
     *      )
     * )
     */
    #[Route(path: 'api/statuses/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            if (isset($requestJSON['code']) && isset($requestJSON['description'])) {
                return new JsonResponse($this->statusService->update($id, $requestJSON['code'], $requestJSON['description']));
            }

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "code" and "description" fields are required.'],
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
     *     description="Delete information about status",
     * )
     *
     * @OA\Tag(name="Admin API")
     */
    #[Route(path: 'api/statuses/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        return new JsonResponse($this->statusService->delete($id));
    }
}
