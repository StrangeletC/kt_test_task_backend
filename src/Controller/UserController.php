<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller
 *
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("", methods={"POST"}, name="Create new user")
     *
     * @return JsonResponse
     */
    public function create(): JsonResponse
    {
        return $this->json([
            'message' => 'Create new user',
        ]);
    }

    /**
     * @Route("/{userId}", methods={"DELETE"}, name="Delete user by ID", requirements={"userId"="\d+"})
     *
     * @param int $userId
     * @return Response
     */
    public function delete(int $userId): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{userId}", methods={"PUT"}, name="Update user by ID", requirements={"userId"="\d+"})
     *
     * @param Request $request
     * @param int $userId
     * @return JsonResponse
     */
    public function update(Request $request, int $userId): JsonResponse
    {
        return $this->json([
            'message' => "Update user by ID {$userId}",
            'request' => $request->getContent()
        ]);
    }

    /**
     * @Route("/{userId}", methods={"GET"}, name="Get user by ID", requirements={"userId"="\d+"})
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getById(int $userId): JsonResponse
    {
        return $this->json([
            'message' => "Get user by id {$userId}"
        ]);
    }

    /**
     * @Route("/{username}", methods={"GET"}, name="Get user by Username", requirements={"userId"="\d+"})
     *
     * @param string $username
     * @return JsonResponse
     */
    public function getByUsername(string $username): JsonResponse
    {
        return $this->json([
            'message' => "Get user by username {$username}"
        ]);
    }
}
