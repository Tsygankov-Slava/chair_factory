<?php

namespace App\Controller;

use App\Service\ProductOrderService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductOrderController extends AbstractController
{
    public function __construct(private readonly ProductOrderService $productOrderService, private readonly LoggerInterface $logger)
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
     *          @OA\Property(property="offset", type="integer", example="2"),
     *          @OA\Property(property="order_id", type="integer", example="2"),
     *      )
     *  )
     */
    #[Route(path: 'api/products_order', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $this->logger->info('Handling show request for product orders');
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['order']) and isset($requestJSON['order_field']) and isset($requestJSON['limit']) and isset($requestJSON['offset']) and isset($requestJSON['order_id'])) {
                $this->logger->info('All required fields are present');
                $response = $this->productOrderService->show($requestJSON['order'], $requestJSON['order_field'], $requestJSON['limit'], $requestJSON['offset'], $requestJSON['order_id']);
                $this->logger->info('Response generated successfully', ['response' => $response]);

                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "order", "order_field", "limit", "offset" and "order_id" fields are required.'],
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
     *     description="Create information about product in order",
     *
     *     @Model(type=ArrayResponse::class)
     * )
     *
     * @OA\Tag(name="Admin API")
     *
     * @OA\RequestBody(
     *     description="Data about product in order (For creating)",
     *     required=true,
     *
     *     @OA\JsonContent(
     *         type="object",
     *
     *          @OA\Property(property="order_id", type="integer", example=2),
     *          @OA\Property(property="base_id", type="integer", example=2),
     *          @OA\Property(property="materials", type="array", @OA\Items(type="integer", example=1)),
     *          @OA\Property(property="price", type="float", example=230.50),
     *          @OA\Property(property="quantity", type="integer", example=4),
     *     )
     * )
     */
    #[Route(path: 'api/products_order', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->logger->info('Handling create request for product order');
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['order_id']) && isset($requestJSON['base_id'])
                && isset($requestJSON['materials']) && isset($requestJSON['price'])
                && isset($requestJSON['quantity'])) {
                $this->logger->info('All required fields are present');
                $response = $this->productOrderService->create($requestJSON['order_id'], $requestJSON['base_id'],
                    $requestJSON['materials'], $requestJSON['price'], $requestJSON['quantity']);
                $this->logger->info('Response generated successfully', ['response' => $response]);

                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "code" and "description" fields are required.'],
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
}
