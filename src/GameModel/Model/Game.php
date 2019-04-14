<?php

namespace GameModel\Model;


class Game
{

  protected $reels = [];
  protected $reelCount = 5;
  protected $reelLength = 21;
  protected $rows = 3;
  protected $ranges = [];
  /**
   * @var Line[]
   */
  protected $lines = [];

  protected $maxId;

  public function __construct()
  {
    $ranges = [];

    for ($i = 0; $i < $this->reelCount; $i++) {
      $ranges[$i] = $this->reelLength ** ($i + 1);
    }

    $this->ranges = $ranges;
    $this->maxId = $ranges[$this->reelCount - 1] - 1;

    $this->generateReels();
    $this->generateLines();
  }

  protected function generateLines(): void
  {
    // Winning masks.
    // Warning this is hardcoded for now!!!
    // It will work only if we have 5 reels and 3 rows!
    // Order of items is important as it represents the winning sequence!
    $winningMasks = [
      [5, 6, 7, 8, 9],
      // *****
      // -----
      // *****
      [0, 1, 2, 3, 4],
      // -----
      // *****
      // *****
      [10, 11, 12, 13, 14],
      // *****
      // *****
      // -----
      [0, 6, 12, 8, 4],
      // \***/
      // *\*/*
      // **V**
      [9, 6, 2, 8, 14],
      // **A**
      // */*\*
      // /***\
      [0, 1, 7, 3, 4],
      // --*--
      // **V**
      // *****
      [10, 11, 7, 13, 14],
      // *****
      // **A**
      // --*--
      [5, 11, 12, 13, 9],
      // *****
      // \***/
      // *\-/*
      [5, 1, 2, 3, 9],
      // */-\*
      // /***\
      // *****
      [5, 1, 7, 3, 9],
      // *A*A*
      // /*V*\
      // *****
    ];

    foreach ($winningMasks as $mask) {
      $this->lines[] = new Line($mask);
    }
  }

  protected function generateReels(): void
  {
    $reels = [];
    for ($i = 0; $i < $this->reelCount; $i++) {
      for ($r = 0; $r < 21; $r++) {
        $reels[$i][$r] = chr(65 + floor($r / 3));
      }
    }

    $this->reels = $reels;
  }

  public function getMax(): int
  {
    return $this->maxId;
  }

  public function spin(): int
  {
    return random_int(0, $this->maxId);
  }

  public function toBaseLength(int $id): string
  {
    // Convert the sequence id to base 21 number (assuming we have 21 symbols in the reel).
    // This is expressed by $reelLength.
    // The result will be a string where each letter represents position of each reel.
    $map = base_convert($id, 10, $this->reelLength);
    // Pad the result with zeros to always have a full length number.
    $map = str_pad($map, $this->reelCount, "0", STR_PAD_LEFT);

    return $map;
  }

  public function getLayout(int $id): array
  {
    $mapBase = $this->toBaseLength($id);

    $map = [];

    // Get positions of each reel.
    for ($r = 0, $rMax = strlen($mapBase); $r < $rMax; $r++) {
      $map[$r] = base_convert($mapBase[$r], $this->reelLength, 10);
    }

    return $map;
  }

  public function getScreen(int $id): array
  {

    $screen = [];
    $layout = $this->getLayout($id);

    for ($row = 0; $row < $this->rows; $row++) {
      for ($reel = 0; $reel < $this->reelCount; $reel++) {
        // Make sure we won't try to print symbols out of range
        $position = ($layout[$reel] + $row) % $this->reelLength;
        $screen[$row][$reel] = $this->reels[$reel][$position];
      }
    }

    return $screen;

  }

  /**
   * Convert two dimensional layout to flat array for easier matching with winning masks.
   *
   * @param int $id ID of the sequence to flatten
   * @return array
   */
  public function flatten(int $id): array
  {

    $flat = [];
    $layout = $this->getLayout($id);

    for ($row = 0; $row < $this->rows; $row++) {
      for ($reel = 0; $reel < $this->reelCount; $reel++) {
        // Make sure we won't try to print symbols out of range
        $position = ($layout[$reel] + $row) % $this->reelLength;
        $screen[$row][$reel] = $this->reels[$reel][$position];
        $flat[] = $this->reels[$reel][$position];
      }
    }

    return $flat;
  }

  public function getMatches(int $id): array
  {
    $flatSequence = $this->flatten($id);

    $winning = [];

    // Check matches
    foreach ($this->lines as $l => $lValue) {
      $match = $lValue->match($flatSequence);

      if ($match->isMatch()) {
        $match->id = $l;
        $winning[] = $match;
      }
    }

    return $winning;
  }

}
