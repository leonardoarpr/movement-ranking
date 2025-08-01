<?php
namespace App\Infrastructure\Http\Controller;

use App\Domain\UseCase\GetMovementRanking;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

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
        } catch (\Exception) {
            return (new JsonResponse(['error' => 'Wild error appear'], 500))->send();
        }
    }
}