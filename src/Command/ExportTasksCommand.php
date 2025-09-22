<?php

namespace App\Command;

use App\Repository\TaskRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

#[AsCommand(
    name: 'app:export-tasks',
    description: 'Exports tasks to a JSON file.',
)]
class ExportTasksCommand extends Command
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly SerializerInterface $serializer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('filePath', InputArgument::OPTIONAL, 'The path to the JSON file.', 'tasks.json')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('filePath');

        try {
            $tasks = $this->taskRepository->findAll();
            $data = $this->serializer->serialize($tasks, 'json', ['groups' => 'task:export']);
            file_put_contents($filePath, $data);
            $io->success('Tasks exported successfully.');
        } catch (Throwable $e) {
            $io->error(sprintf('An error occurred: %s', $e->getMessage()));

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
