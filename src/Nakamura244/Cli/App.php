<?php

namespace Nakamura244\Phpfu\Cli;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @codeCoverageIgnore
 */
final class App extends Application
{
    public function __construct()
    {
        parent::__construct('phpfu', '0.1.0');
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();
        $inputDefinition->setArguments();
        return $inputDefinition;
    }

    public function doRun(InputInterface $input, OutputInterface $output): void
    {
        if (!$input->hasParameterOption('--quiet')) {
            $output->write(
                \sprintf(
                    "phpfu %s by nakamura244.\n\n",
                    $this->getVersion()
                )
            );
        }
        if ($input->hasParameterOption('--version') ||
            $input->hasParameterOption('-V')) {
            exit;
        }
        if (!$input->getFirstArgument()) {
            $input = new ArrayInput(['--help']);
        }
        parent::doRun($input, $output);
    }

    protected function getCommandName(InputInterface $input)
    {
        return 'phpfu';
    }

    protected function getDefaultCommands()
    {
        $defaultCommands = parent::getDefaultCommands();
        $defaultCommands[] = new Command;
        return $defaultCommands;
    }
}
