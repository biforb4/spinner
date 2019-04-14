<?php

namespace GameModel\Model;


use GameModel\Dto\MatchesDto;
use PHPUnit\Framework\TestCase;

class MatchCheckerTest extends TestCase
{

  public function testGetLeftToRightMatch():void
  {
    $matcher = new MatchChecker();
    $sequence = ['B', 'C', 'B', 'B', 'A', 'C', 'C', 'C', 'B', 'A', 'C', 'D', 'C', 'C', 'A'];
    $result = $matcher->getMatches($sequence, [[5, 6, 7, 8, 9]]);

    $this->assertInstanceOf(MatchesDto::class, $result);

    /** @var Match $match */
    $match = $result->getMatches()[0];

    $this->assertEquals('l', $match->getDirection());
    $this->assertEquals(0, $match->getMaskId());
    $this->assertEquals(3, $match->getCount());
    $this->assertEquals('C', $match->getSymbol());
  }
  public function testGetRightToLeftMatch():void
  {
    $matcher = new MatchChecker();
    $sequence = ['A', 'F', 'E', 'E', 'E', 'B', 'F', 'E', 'E', 'E', 'B', 'G', 'E', 'F', 'F'];
    $result = $matcher->getMatches($sequence, [[5, 6, 7, 8, 9]]);

    $this->assertInstanceOf(MatchesDto::class, $result);

    /** @var Match $match */
    $match = $result->getMatches()[0];

    $this->assertEquals('r', $match->getDirection());
    $this->assertEquals(0, $match->getMaskId());
    $this->assertEquals(3, $match->getCount());
    $this->assertEquals('E', $match->getSymbol());
  }

  public function testGetNoMatch():void
  {
    $matcher = new MatchChecker();
    $sequence = ['B', 'D', 'B', 'B', 'A', 'D', 'C', 'C', 'B', 'A', 'C', 'D', 'C', 'C', 'A'];
    $result = $matcher->getMatches($sequence, [[5, 6, 7, 8, 9]]);

    $this->assertInstanceOf(MatchesDto::class, $result);
    $this->assertEmpty($result->getMatches());
  }
}
