<?php

namespace GameModel\Command;


use GameModel\Model\Game;
use GameModel\Model\Match;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Spin extends Command
{
  private $game = null;

  public function __construct($name = null)
  {
    parent::__construct($name);

    $this->game = new Game();
  }

  protected function configure()
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
        "Uses provided sequence ID (omits randomisation).",
        null
      );

  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $id = $input->getOption("sequence-id");

    if ($id === null) {
      $id = $this->game->spin();
    }

    $baseId = $this->game->toBaseLength($id);
    $output->writeln("$id => $baseId");
    $screen = $this->game->getScreen($id);
    $this->printScreen($output, $screen);


    $matches = $this->game->getMatches($id);
    $this->printMatches($output, $matches);

  }

  protected function printScreen(OutputInterface $output, array $screen) {
    for ($row = 0; $row < count($screen); $row++) {
      for ($reel = 0; $reel < count($screen[$row]); $reel++) {
        $output->write($screen[$row][$reel], false);
        $output->write(" ", false);
      }
      $output->writeln("");
    }
  }

  protected function printMatches(OutputInterface $output, array $matches) {
    /**
     * @var Match[] $matches
     */
    foreach ($matches as $match) {
      $output->writeln(sprintf("%s:%s:%d:%s ", $match->id, $match->symbol, $match->count, $match->direction));
    }
  }
}
