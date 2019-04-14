<?php

namespace GameModel\Command;


use GameModel\Model\Game;
use GameModel\Model\Match;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SpinAll extends Command
{
  protected $game = null;
  protected $stats = [];

  public function __construct(?string $name = null)
  {
    parent::__construct($name);

    $this->game = new Game();
  }

  protected function configure()
  {
    $this
      ->setName("game:spin-all")
      ->setDescription("Spin slot game.")
      ->setHelp("This command generates random game result...")
    ;

    $this
      ->addOption(
        'from',
        'f',
        InputOption::VALUE_OPTIONAL,
        "Sequence ID to start from. Default is 0",
        0
      );

    $this
      ->addOption(
        'count',
        'c',
        InputOption::VALUE_OPTIONAL,
        "Number of sequences to check. Default is 0 - All",
        0
      );

    $this
      ->addOption(
        'print-empty',
        'e',
        InputOption::VALUE_OPTIONAL,
        "Print empty lines. Otherwise just skip them.",
        null
      );

    $this
      ->addOption(
        'print-sequence',
        's',
        InputOption::VALUE_OPTIONAL,
        "Print sequence. Otherwise just skip it.",
        1
      );

  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    $from = $input->getOption("from");
    $count = $input->getOption("count");
    $printEmpty = $input->getOption("print-empty") != false;
    $printSequence = $input->getOption("print-sequence") != false;

    // Number of rows not provided, maximum value.
    if ($count == 0) {
      $count = $this->game->getMax() + 1;
    }

    // Make sure we won't go over the limit
    if ($from + $count > $this->game->getMax() + 1) {
      $count = $this->game->getMax() - $from + 1;
    }

    for ($i = $from; $i < $from + $count; $i++) {
      $matches = $this->game->getMatches($i);
      $line = $this->printLine($matches);
      if (strlen($line) > 0 || $printEmpty) {
        if ($printSequence) {
          $sequence = implode("", $this->game->flatten($i));
          $output->writeln("$i, $sequence, $line");
        } else {
          $output->writeln("$i, $line");
        }
      }
    }

    //print_r($this->stats);

  }

  protected function printLine(array $matches):string {
    /**
     * @var Match[] $matches
     */

    $line = "";

    if (count($matches) == 0) {
      return $line;
    }

    $symbol1 = $matches[0]->symbol;
    $symbol2 = null;
    $s1wins3 = 0;
    $s1wins4 = 0;
    $s1wins5 = 0;
    $s2wins3 = 0;
    $s2wins4 = 0;
    $s2wins5 = 0;


    foreach ($matches as $match){
      if (!isset($this->stats[$match->symbol][$match->count])) {
        $this->stats[$match->symbol][$match->count] = 0;
      }
      $this->stats[$match->symbol][$match->count]++;

      if ($match->symbol == $symbol1) {
        // Get variable name that we're modifying.
        $var = "s1wins{$match->count}";
        ${$var}++;
      } else {
        // There can be only one two winning symbols, wo we can safely overwrite it.
        // Note: this is true only with our assumptions as for the reels setup.
        $symbol2 = $match->symbol;
        // Get variable name that we're modifying.
        $var = "s2wins{$match->count}";
        ${$var}++;
      }
    }

    $line .= "$symbol1, $s1wins3, $s1wins4, $s1wins5";

    if ($symbol2 !== null) {
      $line .= ", $symbol2, $s2wins3, $s2wins4, $s2wins5";
    }

    return $line;
  }

  protected function printScreen(OutputInterface $output, array $screen): void {
    for ($row = 0; $row < count($screen); $row++) {
      for ($reel = 0; $reel < count($screen[$row]); $reel++) {
        $output->write($screen[$row][$reel], false);
        $output->write(" ", false);
      }
      $output->writeln("");
    }
  }

  protected function printMatches(OutputInterface $output, array $matches): void {
    /**
     * @var Match[] $matches
     */
    foreach ($matches as $match) {
      $output->writeln(sprintf("%s:%s:%d:%s ", $match->id, $match->symbol, $match->count, $match->direction));
    }
  }
}
