<?php

namespace App\Application\Interface;

interface MovementRepositoryInterface
{
    public function getMovement(?int $id, ?string $movement): array;
}