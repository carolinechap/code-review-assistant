<?php

namespace App\Command;

use App\Service\FileService;
use App\Service\MistralAIService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:review-code',
)]
class ReviewCodeCommand extends Command
{
    public function __construct(
        private readonly MistralAIService $mistralAIService,
        private readonly FileService $fileService,
    )
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'Path to the PHP file to check');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $filePath = $input->getArgument('file');

        if(!file_exists($filePath)) {
            $io->error(sprintf('File does not exist: %s', $filePath));
            return Command::FAILURE;
        }

        $code = file_get_contents($filePath);

        $includeFixedCode = $io->askQuestion(
            new ConfirmationQuestion(
                'Would you like to include the corrected code in the analysis? (y/n)',
                false
            )
        );

        $prompt = $this->buildPrompt($code, $includeFixedCode);

        try {
            $result = $this->mistralAIService->generate($prompt);
            $codeFile = $this->fileService->generateFile($result);

            $io->title('PHP File analysis');
            $io->text("Analyzed file : <fg=cyan>{$filePath}</>");

            $categories = [
                'Performance improvements' => $result->analysis->performance,
                'Security enhancements'    => $result->analysis->security,
                'Readability improvements' => $result->analysis->readability,
                'Best practises'           => $result->analysis->bestPractices,
            ];

            foreach ($categories as $category => $issues) {
                $io->section($category);
                $io->table(
                    ['Problems', 'Solutions'],
                    array_map(
                        fn($issue) => [$issue->issue, $issue->source, $issue->improvement],
                        $issues
                    )
                );
                $io->newLine();
            }
            if($codeFile){
                $io->section('Notes');
                $io->newLine();
                $io->listing($result->improvedCode->notes);
                $io->text(sprintf('New code generated in the file: %s' , $codeFile));
            }


            $io->success('Analysis completed successfully!');

            return Command::SUCCESS;

        } catch (\Exception $e){
            $io->error("An error has occurred : " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    private function buildPrompt(string $code, bool $includeFixedCode): string
    {
        $prompt = 'You are an expert in Symfony and PHP.
        Analyze this code and suggest improvements in terms of performance, security, readability, and best practices under a "analysis" key. For each of theses thematic, provide a list of issue, the line where the problem has been seen under the key "source" and improvement.
         Be specific and provide examples of corrected code if necessary';

        if($includeFixedCode) {
            $prompt .= 'The code style must be formatted in camel case and comply with PHP, Symfony best practices and PSR conventions. Be specific and provide the corrected code under a global "improved_code" key. Under the "improved_code" key, list the changes under a "notes" key.';
        } else {
            $prompt .= 'DO NOT include corrected code in your response.';
        }

        $prompt .= sprintf('Code to analyze: %s', $code);

        return $prompt;
    }
}
