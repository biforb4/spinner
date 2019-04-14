<?php
declare(strict_types=1);

namespace GameModel\Model;


use GameModel\Dto\MatchesDto;

interface MatchCheckerInterface
{
  public function getMatches(array $sequence, array $winningMasks): ?MatchesDto;
}