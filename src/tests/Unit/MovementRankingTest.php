<?php

namespace App\tests\Unit;

use App\Application\dto\PersonalRecordRankingDTO;
use App\Application\Interface\MovementRepositoryInterface;
use App\Application\UseCase\GetMovementRanking;
use PHPUnit\Framework\TestCase;

class MovementRankingTest extends TestCase
{
    public function testExecuteReturnsCorrectRanking()
    {
        $mockRepository = $this->createMock(MovementRepositoryInterface::class);

        $mockRepository->method('getMovement')
            ->willReturn(['test' => [
                new PersonalRecordRankingDTO(userName: 'Alice', personalRecord: 100.0, rankingPosition: 1, recordDate: '2024-01-01'),
                new PersonalRecordRankingDTO(userName: 'Bob', personalRecord: 100.0, rankingPosition: 2, recordDate: '2024-01-02'),
                new PersonalRecordRankingDTO(userName: 'Carol', personalRecord: 90.0, rankingPosition: 3, recordDate: '2024-01-03'),
                new PersonalRecordRankingDTO(userName: 'Dave', personalRecord: 85.0, rankingPosition: 4, recordDate: '2024-01-04'),
            ]]);

        $useCase = new GetMovementRanking($mockRepository);
        $result = $useCase->getRanking(1, null);

        $this->assertCount(4, $result['test']);
        $this->assertEquals(1, $result['test'][0]->rankingPosition);
        $this->assertEquals(1, $result['test'][1]->rankingPosition);
        $this->assertEquals(2, $result['test'][2]->rankingPosition);
        $this->assertEquals(3, $result['test'][3]->rankingPosition);
    }
}