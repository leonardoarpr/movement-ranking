<?php

namespace App\Infrastructure\Repository;

use App\Application\dto\PersonalRecordRankingDTO;
use App\Application\Interface\MovementRepositoryInterface;
use App\Domain\Entity\PersonalRecord;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Support\Collection;

class MovementRepository implements MovementRepositoryInterface
{
    public function getMovement(?int $id, ?string $movement): array
    {
        $query = PersonalRecord::with(['user', 'movement'])
            ->select('id', 'user_id', 'movement_id', Capsule::raw('MAX(value) as max_value'), 'date');

        if ($id) {
            $query->where('movement_id', $id);
        }

        if ($movement) {
            $query->whereHas('movement', function ($query) use ($movement) {
                $query->where('name', $movement);
            });
        }

        $records = $query->groupBy('user_id')
            ->orderByDesc('max_value')
            ->get();

        return $this->rankingToDTO($records);
    }

    private function rankingToDTO(Collection $records): array
    {
        $result = [];
        foreach ($records as $i => $record) {
            $result[$record->movement->name][] = new PersonalRecordRankingDTO(
                userName: $record->user->name,
                personalRecord: $record->max_value,
                rankingPosition: ++$i,
                recordDate: $record->date);
        }

        return $result;
    }
}