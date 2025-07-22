<?php
namespace App\Infrastructure\Http\Controller;

use App\Application\UseCase\GetMovementRanking;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovementController extends AbstractController
{
    public function __construct(
        private readonly GetMovementRanking $useCase
    ) {}
    #[Route('/ranking', name: 'ranking', methods: ['GET'])]
    public function ranking(): JsonResponse
    {
        $movement = $_GET['movement'] ?? null;
        $id = $_GET['id'] ?? null;

        if (!$movement && !$id) {
            return (new JsonResponse(['error' => 'Missing movement or id parameter'], 400))->send();
        }

        try {
            $response = $this->useCase->getRanking($id, $movement);
            return (new JsonResponse($response))->send();
        } catch (NotFoundHttpException $e) {
            return (new JsonResponse(['error' => $e->getMessage()], 404))->send();
        } catch (\Exception $e) {
            return (new JsonResponse(['error' => $e->getMessage()], 500))->send();
        }
    }
}