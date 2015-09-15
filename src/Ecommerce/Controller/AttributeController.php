<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\Repository\AttributeTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AttributeController extends Controller
{

	/**
	 * Create a new category attribute
	 * @param Request $request
	 * @return Response
	 */
	public function addCategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$category_form = $this->createForm('e_category', array(), array());
		$category_form->handleRequest($request);

		if(!$category_form->isValid())
		{
			$this->addFlash('warning', $this->get('translator')->trans('flash.invalid-request'));
			return $this->redirectToRoute('attribute_display_category');
		}

		$data = $category_form->getData();

		//Search for duplicate label

		$attr_value_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeValue');
		if($attr_value_repository->existsLabel(AttributeTypeRepository::TYPE_CATEGORY, $data['value_fr'], 'fr')
			or $attr_value_repository->existsLabel(AttributeTypeRepository::TYPE_CATEGORY, $data['value_en'], 'en'))
		{
			$this->addFlash('danger', $this->get('translator')->trans('flash.label-exists'));
			return $this->redirectToRoute('attribute_display_category');
		}

		$em = $this->getDoctrine()->getEntityManager();

		$attr_type_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeType');
		$attr_type = $attr_type_repository->findOneByTypeId(AttributeTypeRepository::TYPE_CATEGORY);

		//Create a new attribute_label
		$attr_label = new \Ecommerce\Entity\AttributeLabel();
		$attr_label->setType($attr_type);

		$em->persist($attr_label);
		$em->flush();

		//Create a new category
		$category = new \Ecommerce\Entity\Category();
		$category->setType($attr_type);
		$category->setLabel($attr_label);
		$category->setIsActive(TRUE);

		$em->persist($category);
		$em->flush();

		$urlkey = 'category_' . $category->getCategoryId();

		//Create a new attribute_value for FR locale
		$attr_value_repository->createAttributeValue($attr_label, 'fr', $data['value_fr'], $urlkey);

		//Create a new attribute_value for EN locale
		$attr_value_repository->createAttributeValue($attr_label, 'en', $data['value_en'], $urlkey);

		$this->addFlash('success', $this->get('translator')->trans('flash.attribute-created'));
		return $this->redirectToRoute('attribute_display_category');
	}

	/**
	 * Create a new subcategory attribute
	 * @param Request $request
	 * @return Response
	 */
	public function addSubcategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$category_repository = $this->getDoctrine()->getRepository('Ecommerce:Category');

		$initial_data = array(
			'categories' => $category_repository->getFormChoiceCategories($request->getLocale()),
		);

		$subcategory_form = $this->createForm('e_subcategory', $initial_data, array());
		$subcategory_form->handleRequest($request);

		if(!$subcategory_form->isValid())
		{
			$this->addFlash('warning', $this->get('translator')->trans('flash.invalid-request'));
			return $this->redirectToRoute('attribute_display_category');
		}

		$data = $subcategory_form->getData();


		//Search for duplicate label

		$attr_value_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeValue');
		if($attr_value_repository->existsLabel(AttributeTypeRepository::TYPE_SUBCATEGORY, $data['value_fr'], 'fr')
			or $attr_value_repository->existsLabel(AttributeTypeRepository::TYPE_SUBCATEGORY, $data['value_en'], 'en'))
		{
			$this->addFlash('danger', $this->get('translator')->trans('flash.label-exists'));
			return $this->redirectToRoute('attribute_display_category');
		}

		$em = $this->getDoctrine()->getEntityManager();

		$attr_type_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeType');
		$attr_type = $attr_type_repository->findOneByTypeId(AttributeTypeRepository::TYPE_SUBCATEGORY);

		//Create a new attribute_label
		$attr_label = new \Ecommerce\Entity\AttributeLabel();
		$attr_label->setType($attr_type);

		$em->persist($attr_label);
		$em->flush();

		//Create a new category
		$subcategory = new \Ecommerce\Entity\Subcategory();
		$subcategory->setType($attr_type);
		$subcategory->setLabel($attr_label);
		$subcategory->setCategory($data['category']);
		$subcategory->setIsActive(TRUE);

		$em->persist($subcategory);
		$em->flush();

		$urlkey = 'subcategory_' . $subcategory->getSubcategoryId();

		//Create a new attribute_value for FR locale
		$attr_value_repository->createAttributeValue($attr_label, 'fr', $data['value_fr'], $urlkey);

		//Create a new attribute_value for EN locale
		$attr_value_repository->createAttributeValue($attr_label, 'en', $data['value_en'], $urlkey);

		$this->addFlash('success', $this->get('translator')->trans('flash.attribute-created'));
		return $this->redirectToRoute('attribute_display_subcategory');
	}

	/**
	 * Create a new attribute
	 * @param Request $request
	 * @return Response
	 */
	public function createAttributeAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$attribute_form = $this->createForm('e_attribute', array(), array());
		$attribute_form->handleRequest($request);

		if(!$attribute_form->isValid())
		{
			$data = $attribute_form->getData();
			$this->addFlash('warning', $this->get('translator')->trans('flash.invalid-request'));
			return $this->redirectToRoute('attribute_display_type', array('type_id' => empty($data['type'] ? 0 : $data['type']->getTypeId())));
		}

		$data = $attribute_form->getData();

		//Search for duplicate label

		$attr_value_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeValue');
		if($attr_value_repository->existsLabel($data['type']->getTypeId(), $data['value_fr'], 'fr')
			or $attr_value_repository->existsLabel($data['type']->getTypeId(), $data['value_en'], 'en'))
		{
			$this->addFlash('danger', $this->get('translator')->trans('flash.label-exists'));
			return $this->redirectToRoute('attribute_display_type', array('type_id' => $data['type']->getTypeId()));
		}

		$em = $this->getDoctrine()->getEntityManager();


		//Create a new attribute_label
		$attr_label = new \Ecommerce\Entity\AttributeLabel();
		$attr_label->setType($data['type']);

		$em->persist($attr_label);
		$em->flush();

		//Create a new attribute
		$attribute = new \Ecommerce\Entity\Attribute();
		$attribute->setType($data['type']);
		$attribute->setLabel($attr_label);
		$attribute->setIsActive(TRUE);

		$em->persist($attribute);
		$em->flush();

		$urlkey = $data['type']->getCode() . '_' . $attribute->getAttributeId();

		//Create a new attribute_value for FR locale
		$attr_value_repository->createAttributeValue($attr_label, 'fr', $data['value_fr'], $urlkey);

		//Create a new attribute_value for EN locale
		$attr_value_repository->createAttributeValue($attr_label, 'en', $data['value_en'], $urlkey);

		$this->addFlash('success', $this->get('translator')->trans('flash.attribute-created'));
		return $this->redirectToRoute('attribute_display_type', array('type_id' => $data['type']->getTypeId()));
	}

	/**
	 * Set active/inactive a category attribute
	 * @param Request $request
	 * @return Response
	 */
	public function setIsActiveCategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('attribute_id')) or null === $request->request->get('is_active'))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$category_repository = $this->getDoctrine()->getRepository('Ecommerce:Category');
		$category = $category_repository->findOneByCategoryId($request->request->get('attribute_id'));

		if(empty($category))
		{
			return new Response($this->get('translator')->trans('flash.error-try-again'), Response::HTTP_INTERNAL_SERVER_ERROR, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$category->setIsActive((bool) $request->request->get('is_active'));
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();

		if($category->getIsActive())
		{
			$category_repository->enableSubCategories($category);
		}
		else
		{
			$category_repository->disableSubCategories($category);
		}

		$response = array(
			'success'	 => TRUE,
			'message'	 => $this->get('translator')->trans('flash.attribute-updated'),
		);

		return new JsonResponse($response);
	}

	/**
	 * Set active/inactive a subcategory attribute
	 * @param Request $request
	 * @return Response
	 */
	public function setIsActiveSubcategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('attribute_id')) or null === $request->request->get('is_active'))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$subcategory_repository = $this->getDoctrine()->getRepository('Ecommerce:Subcategory');
		$subcategory = $subcategory_repository->findOneBySubcategoryId($request->request->get('attribute_id'));

		if(empty($subcategory))
		{
			return new Response($this->get('translator')->trans('flash.error-try-again'), Response::HTTP_INTERNAL_SERVER_ERROR, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$subcategory->setIsActive((bool) $request->request->get('is_active'));
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();

		$response = array(
			'success'	 => TRUE,
			'message'	 => $this->get('translator')->trans('flash.attribute-updated'),
		);

		return new JsonResponse($response);
	}

	/**
	 * Set active/inactive an attribute
	 * @param Request $request
	 * @return Response
	 */
	public function setIsActiveAttributeAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('attribute_id')) or null === $request->request->get('is_active'))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$attribute_repository = $this->getDoctrine()->getRepository('Ecommerce:Attribute');
		$attribute = $attribute_repository->findOneByAttributeId($request->request->get('attribute_id'));

		if(empty($attribute))
		{
			return new Response($this->get('translator')->trans('flash.error-try-again'), Response::HTTP_INTERNAL_SERVER_ERROR, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$attribute->setIsActive((bool) $request->request->get('is_active'));
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();

		$response = array(
			'success'	 => TRUE,
			'message'	 => $this->get('translator')->trans('flash.attribute-updated'),
		);

		return new JsonResponse($response);
	}

	/**
	 * Display categories attributes list
	 * @return Response
	 */
	public function displayCategoryAction()
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$form_attributes = array(
			'action' => $this->generateUrl('attribute_add_category'),
			'method' => 'POST',
		);

		$category_form = $this->createForm('e_category', array(), $form_attributes);
		$attr_value_form = $this->createForm('e_attr_value', array(), array());

		$category_repository = $this->getDoctrine()->getRepository('Ecommerce:Category');
		$categories = $category_repository->getViewCategories();

		$view = array(
			'head_title'		 => $this->get('translator')->trans('head_title.attribute.display-category'),
			'h1_title'			 => $this->get('translator')->trans('h1_title.attribute.display-category'),
			'categories'		 => $categories,
			'category_form'		 => $category_form->createView(),
			'attr_value_form'	 => $attr_value_form->createView(),
		);

		return $this->render('attribute/category.html.twig', $view);
	}

	/**
	 * Display subcategories attributes list
	 * @param Request $request
	 * @return Response
	 */
	public function displaySubcategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$category_repository = $this->getDoctrine()->getRepository('Ecommerce:Category');
		$categories = $category_repository->getFormChoiceCategories($request->getLocale());

		$initial_data = array(
			'categories' => $categories,
		);

		$form_attributes = array(
			'action' => $this->generateUrl('attribute_add_subcategory'),
			'method' => 'POST',
		);

		$subcategory_form = $this->createForm('e_subcategory', $initial_data, $form_attributes);
		$attr_value_form = $this->createForm('e_attr_value', array(), array());

		$subcategory_repository = $this->getDoctrine()->getRepository('Ecommerce:Subcategory');
		$subcategories = $subcategory_repository->getViewSubcategories($request->getLocale());

		$view = array(
			'head_title'		 => $this->get('translator')->trans('head_title.attribute.display-subcategory'),
			'h1_title'			 => $this->get('translator')->trans('h1_title.attribute.display-subcategory'),
			'categories'		 => $categories,
			'subcategories'		 => $subcategories,
			'subcategory_form'	 => $subcategory_form->createView(),
			'attr_value_form'	 => $attr_value_form->createView(),
		);

		return $this->render('attribute/subcategory.html.twig', $view);
	}

	/**
	 * Display attributes list
	 * @param int $type_id
	 * @return Response
	 */
	public function displayTypeAction($type_id)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$type_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeType');
		$type = $type_repository->findOneByTypeId($type_id);

		$initial_data = array(
			'type'	 => empty($type) ? NULL : $type,
		);

		$form_attributes = array(
			'action' => $this->generateUrl('attribute_create'),
			'method' => 'POST',
		);

		$attribute_form = $this->createForm('e_attribute', $initial_data, $form_attributes);
		$attr_value_form = $this->createForm('e_attr_value', array(), array());

		$view = array(
			'head_title'		 => $this->get('translator')->trans('head_title.attribute.display-attribute'),
			'h1_title'			 => $this->get('translator')->trans('h1_title.attribute.display-attribute'),
			'attribute_types'	 => $type_repository->getTypes(),
			'attribute_form'	 => $attribute_form->createView(),
			'attr_value_form'	 => $attr_value_form->createView(),
		);

		if(!empty($type))
		{
			if($type->getTypeId() == AttributeTypeRepository::TYPE_CATEGORY)
			{
				return $this->redirectToRoute('attribute_display_category');
			}
			elseif($type->getTypeId() == AttributeTypeRepository::TYPE_SUBCATEGORY)
			{
				return $this->redirectToRoute('attribute_display_subcategory');
			}
			else
			{
				$view['type'] = $type;

				$attr_repository = $this->getDoctrine()->getRepository('Ecommerce:Attribute');
				$view['attributes'] = $attr_repository->getViewAttributes($type);
			}
		}

		return $this->render('attribute/default.html.twig', $view);
	}

	/**
	 * Edit an attribute_value row on POST
	 * @param Request $request
	 * @return Response
	 */
	public function editValueAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return new Response($this->get('translator')->trans('flash.wrong-role'), Response::HTTP_UNAUTHORIZED, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$attr_value_form = $this->createForm('e_attr_value', array(), array());
		$attr_value_form->handleRequest($request);

		if(!$attr_value_form->isValid())
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$data = $attr_value_form->getData();

		$attr_value_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeValue');
		$attr_value = $attr_value_repository->findOneByValueId($data['value_id']);

		if(empty($attr_value))
		{
			return new Response($this->get('translator')->trans('flash.error-try-again'), Response::HTTP_INTERNAL_SERVER_ERROR, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		if($attr_value_repository->existsLabel($data['type_id'], $data['name'], $data['locale'], $attr_value))
		{
			return new Response($this->get('translator')->trans('flash.label-exists'), Response::HTTP_INTERNAL_SERVER_ERROR, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$attr_value->setName($data['name']);
		$attr_value->setUrlkey($data['urlkey']);
		$attr_value->setHash($data['name']);

		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();

		$response = array(
			'success'	 => TRUE,
			'message'	 => $this->get('translator')->trans('flash.attribute-updated'),
		);

		return new JsonResponse($response);
	}

	/**
	 *
	 * @param Request $request
	 * @return Response
	 */
	public function switchCategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return new Response($this->get('translator')->trans('flash.wrong-role'), Response::HTTP_UNAUTHORIZED, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		if(empty($request->request->get('category_id')) or empty($request->request->get('subcategory_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$category_repository = $this->getDoctrine()->getRepository('Ecommerce:Category');
		$subcategory_repository = $this->getDoctrine()->getRepository('Ecommerce:Subcategory');

		$category = $category_repository->findOneByCategoryId($request->request->get('category_id'));
		$subcategory = $subcategory_repository->findOneBySubcategoryId($request->request->get('subcategory_id'));

		if(empty($category) or empty($subcategory))
		{
			return new Response($this->get('translator')->trans('flash.error-try-again'), Response::HTTP_INTERNAL_SERVER_ERROR, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$subcategory->setCategory($category);
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();

		$response = array(
			'success'	 => TRUE,
			'message'	 => $this->get('translator')->trans('flash.attribute-updated'),
		);

		return new JsonResponse($response);
	}

	/**
	 * Check if the user is logged in and has permissions
	 * @return boolean
	 */
	private function isAdmin()
	{
		if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')
			and $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN'))
		{
			return TRUE;
		}
		else
		{
			$this->addFlash('warning', $this->get('translator')->trans('flash.wrong-role'));
			return FALSE;
		}
	}

}
