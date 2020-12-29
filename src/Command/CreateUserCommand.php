<?php

namespace App\Command;

use App\Entity\UserEntity;
use App\Exception\OptionNotFoundException;
use App\Service\Command\OptionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserCommand extends Command
{
    /** @var OptionService  */
    private $option;

    /** @var UserPasswordEncoderInterface */
    private $encoder;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var ValidatorInterface */
    private $validator;

    /**
     * @param OptionService $option
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @param ValidatorInterface $validator
     */
    public function __construct(
        OptionService $option,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator)
    {
        $this->option = $option;
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('user:create')
            ->setDescription('Creates a new user')
            ->setHelp('This command allows you to create a user...')
            ->addOption(
                'email',
                'u',
                InputOption::VALUE_REQUIRED,
            'The user e-mail address'
            )
            ->addOption(
                'password',
                'p',
                InputOption::VALUE_REQUIRED,
                'The user password'
            )
            ->addOption(
                'role',
                'r',
                InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY,
                'Add role to user'
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

        if (empty($input->getOption('email'))) {
            $question = new Question('Please enter the user e-mail: ');
            $input->setOption('email', $helper->ask($input, $output, $question));
        }

        if (empty($input->getOption('password'))) {
            $question = new Question('Please enter the user password: ');
            $question->setHidden(true);
            $question->setHiddenFallback(false);
            $input->setOption('password', $helper->ask($input, $output, $question));
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->option->checkForRequiredOptions($input, ['email', 'password']);
        } catch (OptionNotFoundException $exception) {
            $options = $exception->getMissingOptions();
            $output->writeln('<error>Error:</error> Missing value(s) for <info>' . implode(', ', $options) . '</info>');
            return Command::FAILURE;
        }

        $user = new UserEntity();
        $user->setEmail($input->getOption('email'));
        $hash = $this->encoder->encodePassword($user, $input->getOption('password'));
        $user->setPassword($hash);
        $user->setRoles($input->getOption('role'));

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $output->writeln('<error>Error:</error> Validation failed');
            $output->writeln($errors);
            return Command::FAILURE;
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('<info>Success:</info> User ' . $user->getEmail() . ' was created');

        return Command::SUCCESS;
    }
}
