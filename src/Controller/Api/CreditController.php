<?php

namespace App\Controller\Api;

use App\Service\ClientService;
use App\Service\CreditService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CreditController extends AbstractController
{
    public function __construct(
        private readonly CreditService $creditService
    ) {}

    /**
     * @OA\Get(
     *     path="/api/v1/credits",
     *     summary="Get list of credits",
     *     description="Retrieves a list of available credits.",
     *     tags={"Credits"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Base"),
     *                 @OA\Property(property="amount", type="integer", example=1000),
     *                 @OA\Property(property="rate", type="number", format="float", example=10),
     *                 @OA\Property(property="term", type="integer", example=5)
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $credits = $this->creditService->getList();

        return $this->json($credits);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/credits/{id}",
     *     summary="Get credit by ID",
     *     description="Retrieves a single credit by its ID.",
     *     tags={"Credits"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="The ID of the credit to retrieve",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Base"),
     *             @OA\Property(property="amount", type="integer", example=1000),
     *             @OA\Property(property="rate", type="number", format="float", example=10),
     *             @OA\Property(property="term", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Credit not found"
     *     )
     * )
     */
    public function get($id): JsonResponse
    {
        try {
            $client = $this->creditService->findOne($id);
            return $this->json($client);
        } catch (\Throwable $e) {
            throw new NotFoundHttpException('Client not found');
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/credits",
     *     summary="Create a new credit",
     *     description="Creates a new credit and returns the created credit data.",
     *     tags={"Credits"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name", "amount", "rate", "term"},
     *             @OA\Property(property="name", type="string", example="Premium"),
     *             @OA\Property(property="amount", type="integer", example=5000),
     *             @OA\Property(property="rate", type="number", format="float", example=9),
     *             @OA\Property(property="term", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Credit created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=2),
     *                 @OA\Property(property="name", type="string", example="Premium"),
     *                 @OA\Property(property="amount", type="integer", example=5000),
     *                 @OA\Property(property="rate", type="number", format="float", example=9),
     *                 @OA\Property(property="term", type="integer", example=10)
     *             ),
     *             @OA\Property(property="message", type="string", example="success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request data"
     *     )
     * )
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data   = json_decode($request->getContent(), true);
            $credit = $this->creditService->create($data);

            return $this->json(['data' => $credit, 'message' => 'success'], 201);
        } catch (\Throwable $e) {
            throw new BadRequestHttpException('Couldn\'t create credit');
        }
    }

    /**
     * @Route("/api/v1/clients/id", name="update_client", methods={"PUT"})
     *
     * @OA\Put(
     *     path="/api/v1/clients/{id}",
     *     summary="Update an existing client",
     *     description="This endpoint allows you to update an existing client using their ID.",
     *     tags={"Client"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="email", type="string", example="my@test.com"),
     *             @OA\Property(property="age", type="integer", example=30),
     *             @OA\Property(property="name", type="string", example="Ivan"),
     *             @OA\Property(property="surname", type="string", example="Smith"),
     *             @OA\Property(property="ssn", type="string", example="000-00-0000"),
     *             @OA\Property(property="address", type="string", example="New York, some street"),
     *             @OA\Property(property="fico_rating", type="integer", example=350),
     *             @OA\Property(property="phone", type="string", example="2454245245"),
     *             @OA\Property(property="salary", type="integer", example=1200),
     *             @OA\Property(property="password", type="string", example="")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client updated successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="email", type="string", example="my@test.com"),
     *             @OA\Property(property="age", type="integer", example=30),
     *             @OA\Property(property="name", type="string", example="Ivan"),
     *             @OA\Property(property="surname", type="string", example="Smith"),
     *             @OA\Property(property="ssn", type="string", example="000-00-0000"),
     *             @OA\Property(property="address", type="string", example="New York, some street"),
     *             @OA\Property(property="fico_rating", type="integer", example=350),
     *             @OA\Property(property="phone", type="string", example="2454245245"),
     *             @OA\Property(property="salary", type="integer", example=1200),
     *             @OA\Property(property="password", type="string", example="")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invalid data")
     *         )
     *     )
     * )
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $data   = json_decode($request->getContent(), true);
            $client = $this->clientService->update($data);

            return $this->json(['data' => $client, 'message' => 'success'], 201);
        } catch (\Throwable $e) {//TODO handle custom exception for throw different messages
            throw new NotFoundHttpException('Client not found');
        }
    }
}
