<?php

namespace App\Domain\UseCase;

use App\Application\dto\PersonalRecordRankingDTO;
use App\Application\Interface\MovementRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetMovementRanking
{
    public function __construct(
        private MovementRepositoryInterface $repository
    )
    {
    }

    public function getRanking(?int $id, ?string $movement): array
    {
        /** @param PersonalRecordRankingDTO[] $ranking */
        $ranking = $this->repository->getMovementRanking($id, $movement);
        if (empty($ranking)) {
            throw new NotFoundHttpException('No records found for the given movement name or id');
        }

        $count = 1;
        $key = array_key_first($ranking);
        foreach ($ranking[$key] as $i => &$record) {

            if ($i == 0) continue;
            if ($record->personalRecord === $ranking[$key][$i - 1]->personalRecord) {
                $record->rankingPosition = $record->rankingPosition - 1;
                continue;
            }
            $record->rankingPosition = ++$count;
        }

        return $ranking;
    }
}