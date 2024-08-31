<?php

namespace App\Command;

use App\DataTransferObject\Ai\PromptDto;
use App\OpenAi\Business\OpenAiPromptManager;
use App\Repository\VariantPromptRepository;
use DateTime;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:prompts:run',
    description: 'Run all not done Prompts',
)]
class PromptsRunCommand extends Command
{
    public function __construct(
        protected ParameterBagInterface $parameterBag,
        protected OpenAiPromptManager $aiPromptManager,
        protected VariantPromptRepository $variantPromptRepository,
        protected ?string $name = null
    ) {
        parent::__construct($name);
    }

    protected function configure(): void {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Project environment:' . $this->parameterBag->get('kernel.environment'));

        $io->success('Success');

        $prompts = $this->variantPromptRepository->findBy(['isDone' => false]);

        foreach ($prompts as $prompt) {
            try {
                $requestAt = new DateTime('now');
                $start = microtime(true);

                $dto = new PromptDto($prompt->getPrompt(), $prompt->getPromptTemplate());
                $answer = $this->aiPromptManager->requestPrompt($dto);

                $end = microtime(true);

                $promptRequestExecutionTime = (int) ($end - $start) * 1000;

                $prompt->setPromptAnswer($answer);
                $prompt->setIsDone(true);
                $prompt->setRequestAt($requestAt);
                $prompt->setExecutionMilliseconds($promptRequestExecutionTime);

                $this->variantPromptRepository->add($prompt);
                $this->variantPromptRepository->save();
            } catch (Exception $e) {
                dump($e);
            }
        }

        return Command::SUCCESS;
    }
}
