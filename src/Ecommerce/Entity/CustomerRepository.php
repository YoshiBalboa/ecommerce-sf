<?php

namespace Ecommerce\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Doctrine\ORM\EntityRepository;

class CustomerRepository extends EntityRepository implements UserProviderInterface
{

	public function loadUserByUsername($email)
	{
		$user = $this->createQueryBuilder('u')
			->where('u.email = :email')
			->setParameter('email', $email)
			->getQuery()
			->getOneOrNullResult();

		if(null === $user)
		{
			$message = sprintf(
				'Unable to find an active admin AppBundle:User object identified by "%s".', $email
			);

			throw new UsernameNotFoundException($message);
		}

		return $user;
	}

	public function refreshUser(UserInterface $user)
	{
		$class = get_class($user);
		if(!$this->supportsClass($class))
		{
			throw new UnsupportedUserException(
			sprintf(
				'Instances of "%s" are not supported.', $class
			)
			);
		}

		return $this->find($user->getCustomerId());
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
