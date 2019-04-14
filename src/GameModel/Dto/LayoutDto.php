<?php
declare(strict_types=1);

namespace GameModel\Dto;


class LayoutDto
{
  private $row;
  private $reel;
  private $position;

  public function __construct(int $row, int $reel, int $position)
  {
    $this->row = $row;
    $this->reel = $reel;
    $this->position = $position;
  }

  public function getRow(): int
  {
    return $this->row;
  }

  public function getReel(): int
  {
    return $this->reel;
  }

  public function getPosition(): int
  {
    return $this->position;
  }

}