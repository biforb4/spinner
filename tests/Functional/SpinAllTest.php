<?php
declare(strict_types=1);

namespace Tests\Functional;


use GameModel\Command\SpinAll;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class SpinAllTest extends TestCase
{
  /** @var CommandTester */
  private $commandTester;

  protected function setUp(): void
  {
    $application = new Application();
    $application->add(new SpinAll());
    $command = $application->find('game:spin-all');
    $this->commandTester = new CommandTester($command);
  }

  public function testGenerateFirstSequence(): void
  {
    $this->commandTester->execute(['-f' => 0, '-c' => 1]);

    $expectedResult = '0, AAAAAAAAAAAAAAA, A, 0, 0, 10
';
    $this->assertEquals($expectedResult, $this->commandTester->getDisplay());
  }



}