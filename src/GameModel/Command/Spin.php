<?php

namespace GameModel\Command;


use GameModel\Model\Game;
use GameModel\Model\Match;
use GameModel\Model\MatchChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Spin extends Command
{
  private $game;

  public function __construct($name = null)
  {
    parent::__construct($name);

    $this->game = new Game(new MatchChecker());
  }

  protected function configure(): void
  {
    $this
      ->setName("game:spin")
      ->setDescription("Spin slot game.")
      ->setHelp("This command generates random game result...")
    ;

    $this
      ->addOption(
        'sequence-id',
        's',
        InputOption::VALUE_OPTIONAL,
        "Uses provided sequence ID (omits randomisation)."
      );

  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $id = $input->getOption("sequence-id");

    if ($id !== null) {
      $this->game->setId($id);
    }

    $baseId = $this->game->convertIdToBaseLength();
    $output->writeln("$id => $baseId");
    $screen = $this->game->getScreen();
    $this->printScreen($output, $screen);


    $matchesDto = $this->game->getMatches();
    $this->printMatches($output, $matchesDto->getMatches());

  }

  protected function printScreen(OutputInterface $output, array $screen): void
  {
    foreach ($screen as $rowValue) {
      foreach ($rowValue as $reelValue) {
        $output->write($reelValue);
        $output->write(" ");
      }
      $output->writeln("");
    }
  }

  protected function printMatches(OutputInterface $output, array $matches): void
  {
    /**
     * @var Match[] $matches
     */
    foreach ($matches as $match) {
      $output->writeln(sprintf("%s:%s:%d:%s ", $match->getMaskId(), $match->getSymbol(), $match->getCount(), $match->getDirection()));
    }
  }
}
