<?php

namespace Morebec\Orkestra\Core\Infrastructure\Logger;

use Psr\Log\LogLevel;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Logger\ConsoleLogger as SfConsoleLogger;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleLogger extends SfConsoleLogger
{
    /**
     * @var OutputInterface
     */
    private $coutput;

    public function __construct(OutputInterface $output)
    {
        $verbosityLevelMap = [
            LogLevel::NOTICE => OutputInterface::VERBOSITY_NORMAL,
            LogLevel::INFO   => OutputInterface::VERBOSITY_NORMAL,
        ];

        $formatLevelMap = [
            LogLevel::INFO => 'default'
        ];
        $output->getFormatter()->setStyle('default', new OutputFormatterStyle('white'));
        parent::__construct($output, $verbosityLevelMap, $formatLevelMap);

        $this->coutput = $output;
    }

    public function log($level, $message, array $context = [])
    {
        if($level === LogLevel::INFO) {
            $this->coutput->writeln($message);
            return;
        }

        parent::log($level, $message, $context);
    }
}
