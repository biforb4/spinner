<?php

namespace GameModel\Model;


class Match
{
  public const DIRECTION_LEFT = 'l';
  public const DIRECTION_RIGHT = 'r';

  private $symbol;
  private $count;
  private $maskId;
  private $direction;

  public function __construct(string $symbol, int $count, int $gameId, string $direction)
  {
    $this->symbol = $symbol;
    $this->count = $count;
    $this->maskId = $gameId;
    $this->direction = $direction;
  }

  public function getSymbol(): string
  {
    return $this->symbol;
  }

  public function getCount(): int
  {
    return $this->count;
  }

  public function getMaskId(): int
  {
    return $this->maskId;
  }

  public function getDirection(): string
  {
    return $this->direction;
  }


}
