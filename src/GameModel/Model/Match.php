<?php
namespace GameModel\Model;


class Match {
  public $symbol = null;
  public $count = 0;
  public $id = null;
  public $direction = null;

  public const Left = "l";
  public const Right = "r";

  public static function CreateLeft() {
    $match = new self();
    $match->direction = Match::Left;
    return $match;
  }

  public static function CreateRight() {
    $match = new self();
    $match->direction = Match::Right;
    return $match;
  }

  public function isMatch():bool {
    return ($this->symbol != null && $this->count > 0);
  }

  public function reset():void {
    $this->symbol = null;
    $this->count = 0;
    $this->direction = null;
  }

  public function isGreater(Match $match) :bool {
    return $this->count > $match->count;
  }
}
