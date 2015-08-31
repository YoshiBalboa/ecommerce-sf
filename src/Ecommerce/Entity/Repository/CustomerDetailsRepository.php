<?php

namespace Ecommerce\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Ecommerce\Entity\Customer;

class CustomerDetailsRepository extends EntityRepository
{

	/*
	 * Set CustomerDetails default_billing and default_shipping
	 * If at least one address is available, those fields must be set
	 */
	public function checkDefaultAddresses(Customer $customer)
	{
		$customer_details = $this->findOneByCustomer($customer);
		$default_billing = $customer_details->getDefaultBilling();
		$default_shipping = $customer_details->getDefaultShipping();

		//Validate addresses
		if(!empty($default_billing) and $default_billing->getIsActive() === FALSE)
		{
			$default_billing = NULL;
		}

		if(!empty($default_shipping) and $default_shipping->getIsActive() === FALSE)
		{
			$default_shipping = NULL;
		}

		if(empty($default_billing) and empty($default_shipping))
		{
			$address_repository = $this->getEntityManager()->getRepository('Ecommerce:CustomerAddress');
			$active_address = $address_repository->findOneBy(
				array('customer' => $customer, 'isActive' => TRUE));

			//This user doesnt have default addresses yet, set one using the first active address found
			//NULL will be returned if no address was found
			$customer_details->setDefaultBilling($active_address);
			$customer_details->setDefaultShipping($active_address);
		}
		elseif(!empty($default_billing) and empty($default_shipping))
		{
			//Set the default shipping address using default billing address
			$customer_details->setDefaultShipping($default_billing);
		}
		elseif(empty($default_billing) and ! empty($default_shipping))
		{
			//Set the default billing address using default shipping address
			$customer_details->setDefaultBilling($default_shipping);
		}
		else
		{
			//the default addresses are set properly
		}

		$em = $this->getEntityManager();
		$em->flush();
	}

	public function supportsClass($class)
	{
		return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
	}

}
