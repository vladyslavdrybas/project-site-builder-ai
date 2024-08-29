<?php

namespace App\Command;

use App\Builder\VariantBuilder;
use App\DataTransferObject\Ai\PromptDto;
use App\Repository\VariantRepository;
use Exception;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'app:prompts:variant-meta',
    description: 'Generate variant meta from prompt answer.',
)]
class VariantMetaFromPromptCommand extends Command
{
    public function __construct(
        protected readonly ParameterBagInterface $parameterBag,
        protected readonly VariantRepository $variantRepository,
        protected readonly VariantBuilder $variantBuilder,
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

        $variants = $this->variantRepository->findBy([
            'isAiMade' => true,
            'meta' => null,
        ]);

        $io->info('Found entities to update: ' . count($variants));

        foreach ($variants as $variant) {
            try {
                $prompt = $variant->getPrompt();
                $data = $prompt->getPromptAnswer()['choices'][0]['message']['content'] ?? null;

                if (null === $data) {
                    dump('cannot find meta in the prompt answer.');
                    continue;
                }

                $data = json_decode($data, true);

                $variant = $this->variantBuilder->buildVariantMetaFromPromptArray($variant, $data);

                if (null !== $data['parts']['hero']['data']['headline'] ?? null) {
                    $variant->setDescription(
                        sprintf(
                            "%s\n%s\n%s",
                            $data['parts']['hero']['data']['headline'],
                            $data['parts']['hero']['data']['subheadline'],
                            $data['parts']['hero']['data']['callToActionButton']['text']
                        )
                    );
                }

                $this->variantRepository->add($variant);
                $this->variantRepository->save();
            } catch (Exception $e) {
                dump($e);
            }
        }

        return Command::SUCCESS;
    }
}
