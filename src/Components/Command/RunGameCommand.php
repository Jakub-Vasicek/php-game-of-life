<?php declare(strict_types = 1);

namespace BE\GoL\Components\Command;

use BE\GoL\Components\XmlReader\Exception\InvalidInputException;
use BE\GoL\Components\XmlReader\XmlFileReader;
use BE\GoL\Components\XmlWriter\XmlFileWriter;
use BE\GoL\Model\Cell\Exception\CellDoesNotExistException;
use BE\GoL\Model\Game\GameExecutor;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function is_string;

final class RunGameCommand extends Command
{
    protected function configure(): void
    {
        $this->setName('game:run');
        $this->setDescription('use input file [-i] and produce output file [-o]');
        $this->addOption('input', 'i', InputOption::VALUE_OPTIONAL, 'Input file', 'input.xml');
        $this->addOption('output', 'o', InputOption::VALUE_OPTIONAL, 'Output file', 'output.xml');
    }

    /**
     * @throws CellDoesNotExistException
     * @throws InvalidInputException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputFile = $input->getOption('input');
        $outputFile = $input->getOption('output');

        if (!is_string($inputFile)) {
            throw new RuntimeException("Input file name must be a string");
        }
        if (!is_string($outputFile)) {
            throw new RuntimeException("Output file name must be a string");
        }

        $gameData = (new XmlFileReader($inputFile))->loadFileAsGameData();

        $gameExecutor = new GameExecutor();
        $evolvedWorld = $gameExecutor->run($gameData->getWorld(), $gameData->getIterationsCount());

        (new XmlFileWriter($outputFile))->saveWorld($evolvedWorld);
        $output->writeln('File ' . $outputFile . ' was saved.');

        return 0;
    }
}
