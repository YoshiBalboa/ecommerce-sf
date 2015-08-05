<?php

namespace Ecommerce\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * 	@ORM\NamedNativeQueries({
 * 		@ORM\NamedNativeQuery(
 * 			name = "fetchCustomerDetails",
 * 			resultSetMapping = "mappingCustomerDetails",
 * 			query = "
 * 				SELECT c.customer_id, c.email, c.group_id, c.created_at, c.updated_at
 * 					, cd.prefix, cd.firstname, cd.lastname, cd.locale, cd.default_billing_id, cd.default_shipping_id
 * 				FROM customer AS c
 * 				INNER JOIN customer_details AS cs ON cd.customer_id = c.customer_id
 * 				WHERE c.is_active = 1
 * 				AND c.customer_id = ?"
 * 		),
 * 	})
 * 	@ORM\SqlResultSetMappings({
 * 		@ORM\SqlResultSetMapping(
 * 			name = "mappingCustomerDetails",
 * 			entities= {
 * 				@ORM\EntityResult(
 * 					entityClass = "__CLASS__",
 * 					fields = {
 * 						@ORM\FieldResult(name = "customer_id"),
 * 						@ORM\FieldResult(name = "email"),
 * 						@ORM\FieldResult(name = "group_id"),
 * 						@ORM\FieldResult(name = "created_at"),
 * 						@ORM\FieldResult(name = "updated_at"),
 * 						@ORM\FieldResult(name = "prefix"),
 * 						@ORM\FieldResult(name = "firstname"),
 * 						@ORM\FieldResult(name = "lastname"),
 * 						@ORM\FieldResult(name = "locale"),
 * 						@ORM\FieldResult(name = "default_billing_id"),
 * 						@ORM\FieldResult(name = "default_shipping_id"),
 * 					}
 * 				)
 * 			}
 * 		)
 * 	})
 */
class chocolat
{

	/** @ORM\Id @ORM\Column(type="integer") @ORM\GeneratedValue(strategy="AUTO") */
	protected $customer_id;

	/** @ORM\Column(type="string", unique=true) */
	protected $email;

	/** @ORM\Column(type="smallint") */
	protected $group_id;

	/** @ORM\Column(type="datetime") */
	protected $created_at;

	/** @ORM\Column(type="datetime") */
	protected $updated_at;

	/** @ORM\Column(type="string", length=5) */
	protected $prefix;

	/** @ORM\Column(type="string") */
	protected $firstname;

	/** @ORM\Column(type="string") */
	protected $lastname;

	/** @ORM\Column(type="string", length=5) */
	protected $locale;

	/** @ORM\Column(type="integer") */
	protected $default_billing_id;

	/** @ORM\Column(type="integer") */
	protected $default_shipping_id;

}
