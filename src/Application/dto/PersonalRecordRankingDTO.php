<?php

namespace App\Application\dto;

class PersonalRecordRankingDTO
{
    public function __construct(
        public readonly string $userName,
        public readonly float  $personalRecord,
        public int             $rankingPosition,
        public readonly string $recordDate)
    {
    }
}