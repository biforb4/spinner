<?php
declare(strict_types=1);

namespace Tests\Functional;


use GameModel\Command\Spin;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SpinTest extends TestCase
{
  /** @var CommandTester */
  private $commandTester;

  protected function setUp(): void
  {
    $application = new Application();
    $application->add(new Spin());
    $command = $application->find('game:spin');
    $this->commandTester = new CommandTester($command);
  }


  public function testMatchingSpin(): void
  {
    $this->commandTester->execute(['-s' => 1039521]);

    $expectedResult = '1039521 => 57540
B C B B A 
C C C B A 
C D C C A 
0:C:3:l 
9:C:3:l 
';

    $this->assertEquals($expectedResult, $this->commandTester->getDisplay());

  }

  public function testNonMatchingpin(): void
  {
    $this->commandTester->execute(['-s' => 2759351]);

    $expectedResult = '2759351 => e3k0e
E B G A E 
F B A A F 
F B A A F 
';

    $this->assertEquals($expectedResult, $this->commandTester->getDisplay());

  }

}