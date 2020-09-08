<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="Default route")
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Default route',
        ]);
    }
}
