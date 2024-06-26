<?php

namespace App\Controller;

use App\Service\DepartmentService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DepartmentController extends AbstractController
{
    public function __construct(private readonly DepartmentService $departmentService, private readonly LoggerInterface $logger)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Return information about departments",
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
    #[Route(path: 'api/departments', methods: ['GET'])]
    public function show(Request $request): JsonResponse
    {
        $this->logger->info('Handling show request for departments');
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['order']) and isset($requestJSON['order_field']) and isset($requestJSON['limit']) and isset($requestJSON['offset'])) {
                $this->logger->info('All required fields are present');
                $response = $this->departmentService->show($requestJSON['order'], $requestJSON['order_field'], $requestJSON['limit'], $requestJSON['offset']);
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
     *     description="Create information about department",
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
     *         @OA\Property(property="name", type="string", example="Name"),
     *         @OA\Property(property="code", type="integer", example=201),
     *     )
     * )
     */
    #[Route(path: 'api/departments', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $this->logger->info('Handling create request for department');
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['name']) && isset($requestJSON['code'])) {
                $this->logger->info('All required fields are present');
                $response = $this->departmentService->create($requestJSON['name'], $requestJSON['code']);
                $this->logger->info('Response generated successfully', ['response' => $response]);
                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "name" and "code" fields are required.'],
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
     *      description="Data about department (For updating)",
     *      required=true,
     *
     *      @OA\JsonContent(
     *          type="object",
     *
     *          @OA\Property(property="name", type="string", example="__New_Test__"),
     *          @OA\Property(property="code", type="integer", example="101"),
     *      )
     * )
     */
    #[Route(path: 'api/departments/{id}', methods: ['PUT'])]
    public function update(Request $request, int $id): JsonResponse
    {
        $this->logger->info('Handling update request for department', ['id' => $id]);
        $requestJSON = json_decode($request->getContent(), true);
        if (JSON_ERROR_NONE == json_last_error()) {
            $this->logger->info('Valid JSON received', ['data' => $requestJSON]);
            if (isset($requestJSON['name']) && isset($requestJSON['code'])) {
                $this->logger->info('All required fields are present');
                $response = $this->departmentService->update($id, $requestJSON['name'], $requestJSON['code']);
                $this->logger->info('Response generated successfully', ['response' => $response]);
                return new JsonResponse($response);
            }

            $this->logger->warning('Missing required fields in JSON', ['data' => $requestJSON]);

            return new JsonResponse(
                ['error' => 'Invalid JSON format. "name" and "code" fields are required.'],
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
     *     description="Delete information about department",
     * )
     *
     * @OA\Tag(name="Admin API")
     */
    #[Route(path: 'api/departments/{id}', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->logger->info('Handling delete request for department', ['id' => $id]);
        $response = $this->departmentService->delete($id);
        $this->logger->info('Response generated successfully', ['response' => $response]);
        return new JsonResponse($response);
    }
}
