<?php

namespace Nakamura244\Phpfu\Cli;

use Nakamura244\Phpfu\Analyser;
use Symfony\Component\Console\Command\Command as AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @codeCoverageIgnore
 */
final class Command extends AbstractCommand
{
    protected function configure(): void
    {
        $this->setName('phpfu');
        $this->addArgument('filename', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $php = new Analyser;
        $filename = $input->getArgument('filename');
        $lines = $php->execute($filename);
        if (empty($lines)) {
            $output->writeln('Did not find unuse code.');
            exit;
        }
        $output->writeln('Unuse code?: ');
        $output->writeln('');
        foreach ($lines as $k => $line) {
            $output->writeln('method: ' . $k);
            $output->writeln('bytecode: ');
            foreach ($line as $val) {
                $output->writeln($val);
            }
            $output->writeln('');
        }
    }
}