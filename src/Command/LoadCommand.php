<?php

namespace App\Command;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

#[AsCommand('app:load', 'Load some data so the db isn\'t empty and we can test')]
class LoadCommand
{
	public function __construct(
        private EntityManagerInterface $entityManager,
        private MessageRepository $messageRepository,
        private UserRepository $userRepository,
        private PropertyAccessorInterface $propertyAccessor,
    )
	{
	}


	public function __invoke(
		SymfonyStyle $io,
		#[Option(description: 'purge the database first')]
		?bool $purge = false,
	): int
	{
		if ($purge) {
            foreach ($this->messageRepository->findAll() as $message) {
                $this->entityManager->remove($message);
            }
            foreach ($this->userRepository->findAll() as $user) {
                $this->userRepository->upgradePassword($user, 'new-password');
                $user->eraseCredentials();
                $this->entityManager->remove($user);
            }
            $this->entityManager->flush();
		    $io->writeln("entities purged");
		}

        $user = (new User())
            ->setPassword('random')
            ->setEmail('tacman@gmail.com')
            ->setRoles(['ROLE_USER']);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        // call the getters?   ugh.  Hack for testing
        foreach (['id','email', 'userIdentifier', 'roles'] as $key) {
            $this->propertyAccessor->getValue($user, $key);
        }


        $message = (new Message())
            ->setContent('abc')
        ;
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        // call the getters?   ugh.  Hack for testing
        foreach (['id','content'] as $key) {
            $this->propertyAccessor->getValue($message, $key);
        }
        $count = $this->messageRepository->count([]);
		$io->success(self::class . " success. " . $count);
		return Command::SUCCESS;
	}
}
