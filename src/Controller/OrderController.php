<?php

namespace App\Controller;

use App\Service\OrderService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    public function __construct(private readonly OrderService $orderService, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return information about order",
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
    #[Route(path: 'api/orders', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $this->logger->info('Handling show request for orders');
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['order']) and isset($requestJSON['order_field']) and isset($requestJSON['limit']) and isset($requestJSON['offset'])) {
                $this->logger->info('All required fields are present');
                $response = $this->orderService->show($requestJSON['order'], $requestJSON['order_field'], $requestJSON['limit'], $requestJSON['offset']);
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
     *     description="Create information about order",
     *
     *     @Model(type=ArrayResponse::class)
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *     description="Data about order (For creating)",
     *     required=true,
     *
     *     @OA\JsonContent(
     *         type="object",
     *
     *         @OA\Property(property="total_price", type="float", example="1000"),
     *         @OA\Property(property="status_id", type="integer", example="2")
     *     )
     * )
     */
    #[Route(path: 'api/orders', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->logger->info('Handling create request for order');
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['total_price']) && isset($requestJSON['status_id'])) {
                $this->logger->info('All required fields are present');
                $response = $this->orderService->create($requestJSON['total_price'], $requestJSON['status_id']);
                $this->logger->info('Response generated successfully', ['response' => $response]);
                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "total_price" and "status_id" fields are required.'],
                400,
                [],
                false
            );
        }

        $this->logger->error('Invalid JSON format received');

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
     *     description="Update information about order",
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *      description="Data about order (For updating)",
     *      required=true,
     *
     *      @OA\JsonContent(
     *          type="object",
     *
     *         @OA\Property(property="total_price", type="float", example="1000"),
     *         @OA\Property(property="status_id", type="integer", example="2")
     *      )
     * )
     */
    #[Route(path: 'api/orders/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $this->logger->info('Handling update request for order', ['id' => $id]);
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['total_price']) && isset($requestJSON['status_id'])) {
                $this->logger->info('All required fields are present');
                $response = $this->orderService->update($id, $requestJSON['total_price'], $requestJSON['status_id']);
                $this->logger->info('Response generated successfully', ['response' => $response]);
                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "total_price" and "status_id" fields are required.'],
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
     *     description="Delete information about order",
     * )
     *
     * @OA\Tag(name="Admin API")
     */
    #[Route(path: 'api/orders/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->logger->info('Handling delete request for order', ['id' => $id]);
        $response = $this->orderService->delete($id);
        $this->logger->info('Response generated successfully', ['response' => $response]);
        return new JsonResponse($response);
    }
}
