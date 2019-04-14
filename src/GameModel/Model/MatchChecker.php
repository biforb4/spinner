<?php


namespace GameModel\Model;


use GameModel\Dto\MatchesDto;

class MatchChecker implements MatchCheckerInterface
{
  public function getMatches(array $sequence, array $winningMasks): ?MatchesDto
  {
    $leftToRightMatchesDto = new MatchesDto();
    $rightToLeftMatchesDto = new MatchesDto();
    foreach ($winningMasks as $id => $winningMask) {
      $leftToRightMatch = $this->getLeftToRightMatch($sequence, $id, $winningMask);
      if($leftToRightMatch instanceof Match) {
        $leftToRightMatchesDto->addMatch($leftToRightMatch);
      }

      $rightToLeftMatch = $this->getRightToLeftMatch($sequence, $id, $winningMask);
      if($rightToLeftMatch instanceof Match) {
        $rightToLeftMatchesDto->addMatch($rightToLeftMatch);
      }
    }

    $leftToRightMatchesCount = count($leftToRightMatchesDto->getMatches());
    $rightToLeftMatchesCount = count($rightToLeftMatchesDto->getMatches());

    if($leftToRightMatchesCount > $rightToLeftMatchesCount) {
      return $leftToRightMatchesDto;
    }

    return $rightToLeftMatchesDto;
  }

  private function getLeftToRightMatch(array $sequence, int $maskId, array $mask): ?Match
  {
    $symbol = $sequence[$mask[0]];
    $matchCount = 1;

    // Start from second symbol, as first will always checkMatch.
    for ($i = 1, $iMax = count($mask); $i < $iMax; $i++){
      if ($sequence[$mask[$i]] !== $symbol) {
        break;
      }

      $matchCount++;
    }

    if($matchCount < 3) {
      return null;
    }

    return new Match($symbol, $matchCount, $maskId, Match::DIRECTION_LEFT);
  }

  private function getRightToLeftMatch(array $sequence, int $maskId, array $mask): ?Match
  {
    $symbol = $sequence[$mask[4]];
    $matchCount = 1;

    // Start from second symbol, as first will always checkMatch.
    for ($i = count($mask) - 2; $i >= 0; $i--){
      if ($sequence[$mask[$i]] !== $symbol) {
        break;
      }

      $matchCount++;
    }

    if($matchCount < 3) {
      return null;
    }

    return new Match($symbol, $matchCount, $maskId, Match::DIRECTION_RIGHT);
  }
}