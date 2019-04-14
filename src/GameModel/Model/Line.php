<?php

namespace GameModel\Model;


class Line
{
  protected $mask;

  public function __construct(array $mask)
  {
    $this->mask = $mask;
  }

  /**
   * Match line's symbols sequence against provided flat sequence of symbols in the game.
   *
   * @param array $flatSequence A sequence of symbols on the screen concatenated into single array (all rows into one),
   *                            for example: A A A A B A A A B B A A B B C
   * @return Match
   */
  public function match(array $flatSequence): Match
  {
    $lMatch = Match::CreateLeft();
    $rMatch = Match::CreateRight();

    // Check first matching symbol on the left.
    $lMatch->symbol = $flatSequence[$this->mask[0]];
    $lMatch->count = 1;

    // Start from second symbol, as first will always checkMatch.
    for ($i = 1, $iMax = count($this->mask); $i < $iMax; $i++){
      if ($flatSequence[$this->mask[$i]] !== $lMatch->symbol) {
        break;
      }

      $lMatch->count++;
    }

    if ($lMatch->count < 3) {
      $lMatch->reset();
    }

    // Check first matching symbol on the right.
    $rMatch->symbol = $flatSequence[$this->mask[4]];
    $rMatch->count = 1;

    // Start from second symbol, as first will always checkMatch.
    for ($i = count($this->mask) - 2; $i >= 0; $i--){
      if ($flatSequence[$this->mask[$i]] !== $rMatch->symbol) {
        break;
      }

      $rMatch->count++;
    }

    if ($rMatch->count < 3) {
      $rMatch->reset();
    }

    if ($lMatch->isGreater($rMatch)) {
      return $lMatch;
    }

    return $rMatch;
  }

}
