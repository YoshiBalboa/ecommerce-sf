<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\EntityRepository;

class CustomerGroupRepository extends EntityRepository
{

	const GROUP_DEFAULT = 1;
	const GROUP_UNSUBSCRIBE = 2;
	const GROUP_PRO = 3;
	const GROUP_ADMIN = 4;

	public function getDefaultGroup()
	{
		$group = $this->createQueryBuilder('cg')
			->where('cg.groupId = :group')
			->setParameter('group', self::GROUP_DEFAULT)
			->getQuery()
			->getOneOrNullResult();

		return $group;
	}

	public function getUnsubscribeGroup()
	{
		$group = $this->createQueryBuilder('cg')
			->where('cg.group_id = :group')
			->setParameter('group', self::GROUP_UNSUBSCRIBE)
			->getQuery()
			->getOneOrNullResult();

		return $group;
	}

	public function getProfessionalGroup()
	{
		$group = $this->createQueryBuilder('cg')
			->where('cg.group_id = :group')
			->setParameter('group', self::GROUP_PRO)
			->getQuery()
			->getOneOrNullResult();

		return $group;
	}

	public function getAdminGroup()
	{
		$group = $this->createQueryBuilder('cg')
			->where('cg.group_id = :group')
			->setParameter('group', self::GROUP_ADMIN)
			->getQuery()
			->getOneOrNullResult();

		return $group;
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
