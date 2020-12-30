<?php

namespace App\Command;

use App\Entity\GroupEntity;
use App\Entity\SourceEntity;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Yaml\Yaml;

class ImportGroupCommand extends Command
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ValidatorInterface */
    private $validator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('group:import')
            ->setDescription('Imports a group structure')
            ->setHelp('This command allows you to import a group structure...')
            ->addOption(
                'file',
                'f',
                InputOption::VALUE_REQUIRED,
                'The import file'
            )
            ->addOption(
                'group',
                'g',
                InputOption::VALUE_REQUIRED,
                'The group id'
            )
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');

        if (empty($input->getOption('file'))) {
            $question = new Question('Please enter a file: ');
            $input->setOption('file', $helper->ask($input, $output, $question));
        }

        if (empty($input->getOption('group'))) {
            $question = new Question('Please enter the group id: ');
            $input->setOption('group', $helper->ask($input, $output, $question));
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var GroupRepository $groupRepository */
        $groupRepository = $this->entityManager->getRepository(GroupEntity::class);
        /** @var GroupEntity $group */
        $group = $groupRepository->find($input->getOption('group'));

        if (!$group) {
            $output->writeln('<error>Error:</error> Group ' . $input->getOption('group') . ' does not exist');
            return Command::FAILURE;
        }

        $fs = new Filesystem();
        if (!$fs->exists($input->getOption('file'))) {
            $output->writeln('<error>Error:</error> File ' . $input->getOption('file') . ' does not exist');
            return Command::FAILURE;
        }

        $rawContent = file_get_contents($input->getOption('file'));
        $parsedContent = Yaml::parse($rawContent);

        foreach ($parsedContent as $parsedSource) {
            if (empty($parsedSource['name']) || empty($parsedSource['unit'])) {
                $output->writeln('<error>Error:</error> Invalid file structure');
                return Command::FAILURE;
            }

            $source = new SourceEntity();
            $source->setName($parsedSource['name']);
            $source->setUnit($parsedSource['unit']);
            $source->setGroup($group);

            $errors = $this->validator->validate($source);
            if (count($errors) > 0) {
                $output->writeln('<error>Error:</error> Validation failed');
                $output->writeln($errors);
                return Command::FAILURE;
            }

            $this->entityManager->persist($source);
        }

        $this->entityManager->flush();
        $output->writeln('<info>Success:</info> Imported ' . count($parsedContent) . ' source(s)');

        return Command::SUCCESS;
    }
}
