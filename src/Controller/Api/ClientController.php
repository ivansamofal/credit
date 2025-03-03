<?php

namespace App\Controller\Api;

use App\Service\ClientService;
use App\Service\CreditService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientController extends AbstractController
{
    public function __construct(
        private readonly ClientService $clientService,
        private readonly CreditService $creditService
    ) {}

    /**
     * @Route("/api/v1/clients", name="get_clients", methods={"GET"})
     *
     * @OA\Get(
     *     path="/api/v1/clients",
     *     summary="Get a list of all clients",
     *     description="This endpoint returns a list of all clients in the system.",
     *     tags={"Client"},
     *     @OA\Response(
     *         response=200,
     *         description="A list of clients",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Ivan"),
     *                 @OA\Property(property="surname", type="string", example="Smith"),
     *                 @OA\Property(property="age", type="integer", example=30),
     *                 @OA\Property(property="address", type="string", example="New York, some street"),
     *                 @OA\Property(property="email", type="string", example="my@test.com"),
     *                 @OA\Property(property="ssn", type="string", example="000-00-0000"),
     *                 @OA\Property(property="ficoRating", type="integer", example=350),
     *                 @OA\Property(property="phone", type="string", example="2454245245")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Invalid data")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="No clients found")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        //TODO add limits/offsets pagination
        $profiles = $this->clientService->getList();

        return $this->json($profiles);
    }

    /**
     * @Route("/api/v1/clients/{id}", name="get_client", methods={"GET"})
     *
     * @OA\Get(
     *     path="/api/v1/clients/{id}",
     *     summary="Get a client by ID",
     *     description="This endpoint returns a client by their ID.",
     *     tags={"Client"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the client to fetch",
     *         required=true,
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Client details",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=10),
     *             @OA\Property(property="name", type="string", example="Ivan"),
     *             @OA\Property(property="surname", type="string", example="Smith"),
     *             @OA\Property(property="age", type="integer", example=30),
     *             @OA\Property(property="address", type="string", example="New York, some street"),
     *             @OA\Property(property="email", type="string", example="my@test.com"),
     *             @OA\Property(property="ssn", type="string", example="000-00-0000"),
     *             @OA\Property(property="ficoRating", type="integer", example=350),
     *             @OA\Property(property="phone", type="string", example="2454245245")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Client not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Client not found")
     *         )
     *     )
     * )
     */
    public function get($id): JsonResponse
    {
        try {
            $client = $this->clientService->findOne($id);
            return $this->json($client);
        } catch (\Throwable $e) {
            throw new NotFoundHttpException('Client not found');
        }
    }

    /**
     * @Route("/api/v1/clients", name="create_client", methods={"POST"})
     *
     * @OA\Post(
     *     path="/api/v1/clients",
     *     summary="Create a new client",
     *     description="This endpoint allows you to create a new client.",
     *     tags={"Client"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
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
     *         description="Client created successfully",
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
    public function create(Request $request): JsonResponse
    {
        try {
            $data   = json_decode($request->getContent(), true);
            $client = $this->clientService->create($data);

            return $this->json(['data' => $client, 'message' => 'success'], 201);
        } catch (\Throwable $e) {
            throw new BadRequestHttpException($e->getMessage());
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
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    /**
     * @Route("/api/v1/clients/{id}/permission", name="check_permission", methods={"GET"})
     */
    public function checkPermission(int $clientId): JsonResponse
    {
        $client = $this->clientService->findOne($clientId);
        $result = $this->clientService->checkPermissionForCredit($client);

        return $this->json(['result' => $result]);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/clients/{clientId}/credit/{creditId}",
     *     summary="Assign a credit to a client",
     *     description="Adds a credit to the specified client and returns a success message.",
     *     operationId="assignCreditToClient",
     *     tags={"Credits"},
     *
     *     @OA\Parameter(
     *         name="clientId",
     *         in="path",
     *         required=true,
     *         description="The ID of the client",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Parameter(
     *         name="creditId",
     *         in="path",
     *         required=true,
     *         description="The ID of the credit",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Credit successfully assigned to the client",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Credit has been added successfully to this client")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Invalid request or missing parameters",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Invalid client ID or credit ID")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Client or credit not found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Client not found")
     *         )
     *     )
     * )
     */
    public function giveCredit(int $clientId, int $creditId): JsonResponse
    {
        try {
            $client = $this->clientService->findOne($clientId);
            $credit = $this->creditService->findOne($creditId);
            $this->clientService->giveCredit($client, $credit);

            return $this->json(['message' => 'Credit has added successfully to this client']);
        } catch (\Throwable $e) {
            return $this->json(['message' => $e->getMessage()], 500);
        }
    }
}
