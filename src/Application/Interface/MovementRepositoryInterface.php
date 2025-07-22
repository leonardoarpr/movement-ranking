<?php

namespace App\Application\Interface;

interface MovementRepositoryInterface
{
    public function getMovementRanking(?int $id, ?string $movement): array;
}