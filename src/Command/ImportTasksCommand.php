<?php

namespace App\Command;

use App\Service\TaskImportService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;

#[AsCommand(
    name: 'app:import-tasks',
    description: 'Imports tasks from a JSON file.',
)]
class ImportTasksCommand extends Command
{
    public function __construct(private readonly TaskImportService $taskImportService)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filePath', InputArgument::REQUIRED, 'The path to the JSON file.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('filePath');

        if (!file_exists($filePath)) {
            $io->error(sprintf('File not found: "%s"', $filePath));

            return Command::FAILURE;
        }

        try {
            $data = file_get_contents($filePath);
            $this->taskImportService->import($data);
            $io->success('Tasks imported successfully.');
        } catch (Throwable $e) {
            $io->error(sprintf('An error occurred: %s', $e->getMessage()));

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
