<?php

declare(strict_types=1);

namespace BitBag\OpenMarketplace\App\Command;

use BitBag\OpenMarketplace\App\Parser\PartsGeekParser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:parse')]
class ParsingCommand extends Command
{
    public function __construct(protected HttpClientInterface $client, string $name = null)
    {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('shop', InputArgument::REQUIRED, 'Which shop?')
            ->addArgument('code', InputArgument::REQUIRED, 'Which code?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $code = $input->getArgument('code');

        switch ($input->getArgument('shop')) {
            case '1':
                $partsGeek = new PartsGeekParser($this->client);
                $elements = $partsGeek->execute($code);
                $output->writeln($elements);
                break;
            case '2':
                $output->writeln('Whoa 2222!');
                break;
        }

        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
