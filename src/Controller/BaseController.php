<?php

namespace App\Controller;

use App\Service\BaseService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BaseController extends AbstractController
{
    public function __construct(private readonly BaseService $baseService, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return information about bases",
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
    #[Route(path: 'api/bases', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $this->logger->info('Handling show request');
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['order']) and isset($requestJSON['order_field']) and isset($requestJSON['limit']) and isset($requestJSON['offset'])) {
                $this->logger->info('All required fields are present');
                $response = $this->baseService->show($requestJSON['order'], $requestJSON['order_field'], $requestJSON['limit'], $requestJSON['offset']);
                $this->logger->info('Response generated successfully', ['response' => $response]);
                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "order", "order_field", "limit" and "offset" fields are required.'],
                400,
                [],
                true
            );
        }

        $this->logger->error('Invalid JSON format received');

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
     *     description="Create information about base",
     *
     *     @Model(type=ArrayResponse::class)
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *     description="Data about base (For creating)",
     *     required=true,
     *
     *     @OA\JsonContent(
     *         type="object",
     *
     *         @OA\Property(property="type", type="string", example="Test"),
     *         @OA\Property(property="title", type="string", example="Test"),
     *         @OA\Property(property="price", type="integer", example="1000"),
     *         @OA\Property(property="department_id", type="integer", example="10"),
     *     )
     * )
     */
    #[Route(path: 'api/bases', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->logger->info('Handling create request');
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['type']) && isset($requestJSON['title']) && isset($requestJSON['price']) && isset($requestJSON['department_id'])) {
                $this->logger->info('All required fields are present');
                $response = $this->baseService->create($requestJSON['type'], $requestJSON['title'], $requestJSON['price'], $requestJSON['department_id']);
                $this->logger->info('Response generated successfully', ['response' => $response]);
                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "type" and "price" fields are required.'],
                400,
                [],
                true
            );
        }

        $this->logger->error('Invalid JSON format received');

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
     *
     *      @OA\JsonContent(
     *          type="object",
     *
     *          @OA\Property(property="type", type="string", example="__New_Test__"),
     *          @OA\Property(property="title", type="string", example="__New_Test__"),
     *          @OA\Property(property="price", type="integer", example="9999"),
     *          @OA\Property(property="department_id", type="integer", example="9999")
     *      )
     * )
     */
    #[Route(path: 'api/bases/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $this->logger->info('Handling update request', ['id' => $id]);
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['type']) && isset($requestJSON['title']) && isset($requestJSON['price']) && isset($requestJSON['department_id'])) {
                $this->logger->info('All required fields are present');
                $response = $this->baseService->update($id, $requestJSON['type'], $requestJSON['title'], $requestJSON['price'], $requestJSON['department_id']);
                $this->logger->info('Response generated successfully', ['response' => $response]);
                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "type", "title", "price" and "department_id" fields are required.'],
                400,
                [],
                true
            );
        }

        $this->logger->error('Invalid JSON format received');

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
     *     description="Delete information about base",
     * )
     *
     * @OA\Tag(name="Admin API")
     */
    #[Route(path: 'api/bases/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->logger->info('Handling delete request', ['id' => $id]);
        $response = $this->baseService->delete($id);
        $this->logger->info('Response generated successfully', ['response' => $response]);
        return new JsonResponse($response);
    }
}
