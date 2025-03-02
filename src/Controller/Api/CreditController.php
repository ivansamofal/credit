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

    public function index(): JsonResponse
    {
        $credits = $this->creditService->getList();//todo

        return $this->json($credits);
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
            var_dump($e->getMessage());//todo
            die;
            throw new BadRequestHttpException('Couldn\'t create client');
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
