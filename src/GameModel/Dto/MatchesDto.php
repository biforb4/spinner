<?php
declare(strict_types=1);

namespace GameModel\Dto;


use GameModel\Model\Match;

class MatchesDto
{
  private $matches = [];

  public function addMatch(Match $match): void
  {
    $this->matches[] = $match;
  }

  public function getMatches(): array
  {
    return $this->matches;
  }
}