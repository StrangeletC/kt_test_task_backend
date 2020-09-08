<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TaskController
 * @package App\Controller
 *
 * @Route("/task")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("", methods={"POST"}, name="Create new task")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        return $this->json([
            'message' => 'Create new task',
        ]);
    }

    /**
     * @Route("/{taskId}", methods={"PUT"}, name="Edit task by ID", requirements={"taskId"="\d+"})
     *
     * @param Request $request
     * @param int $taskId
     * @return JsonResponse
     */
    public function edit(Request $request, int $taskId): JsonResponse
    {
        return $this->json([
            'message' => "Edit task by ID {$taskId}",
            'request' => $request->getContent()
        ]);
    }

    /**
     * @Route("/{taskId}", methods={"DELETE"}, name="Delete task by ID", requirements={"taskId"="\d+"})
     *
     * @param int $taskId
     * @return Response
     */
    public function delete(int $taskId): Response
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/{taskId}", methods={"GET"}, name="Get one task by ID", requirements={"taskId"="\d+"})
     * @param int $taskId
     * @return JsonResponse
     */
    public function getOneTaskById(int $taskId): JsonResponse
    {
        return $this->json([
            'message' => "Get one task by ID {$taskId}",
        ]);
    }

    /**
     * @Route("/user/{userId}", methods={"GET"}, name="Get all tasks by user ID", requirements={"userId"="\d+"})
     *
     * @param int $userId
     * @return JsonResponse
     */
    public function getAllTasksByUserId(int $userId): JsonResponse
    {
        return $this->json([
            'message' => "Get all tasks by user ID {$userId}",
        ]);
    }

    /**
     * @Route("/search", methods={"GET"}, name="Search tasks by filter")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function searchByFilter(Request $request): JsonResponse
    {
        return $this->json([
            'message' => "Find by filter",
            'request' => $request->getContent()
        ]);
    }
}
