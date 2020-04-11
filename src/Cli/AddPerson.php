<?php

declare(strict_types=1);

namespace App\Cli;

use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Command\CreatePersonCommand;
use App\Command\CreatePersonCommandHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddPerson extends Command
{
    private CreatePersonCommandHandler $createPersonCommandHandler;

    private ValidatorInterface $validator;

    public function __construct(
        CreatePersonCommandHandler $createPersonCommandHandler,
        ValidatorInterface $validator
    ) {
        parent::__construct(null);
        $this->createPersonCommandHandler = $createPersonCommandHandler;
        $this->validator                  = $validator;
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:person:add')
            ->setDescription('Create a new person')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of the person.')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command = new CreatePersonCommand();

        $command->id   = Uuid::uuid4()->toString();
        $command->name = $input->getArgument('name');

        $this->validator->validate($command);

        ($this->createPersonCommandHandler)($command);

        return 0;
    }
}
