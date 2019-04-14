<?php

namespace GameModel\Model;


class Match
{
  public $symbol;
  public $count = 0;
  public $id;
  public $direction;

  public const Left = "l";
  public const Right = "r";

  public static function CreateLeft(): Match
  {
    $match = new self();
    $match->direction = self::Left;
    return $match;
  }

  public static function CreateRight(): Match
  {
    $match = new self();
    $match->direction = self::Right;
    return $match;
  }

  public function isMatch(): bool
  {
    return ($this->symbol !== null && $this->count > 0);
  }

  public function reset(): void
  {
    $this->symbol = null;
    $this->count = 0;
    $this->direction = null;
  }

  public function isGreater(Match $match): bool
  {
    return $this->count > $match->count;
  }
}
