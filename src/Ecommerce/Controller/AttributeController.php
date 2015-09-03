<?php

namespace Ecommerce\Controller;

use Ecommerce\Entity\Repository\AttributeTypeRepository;
use Ecommerce\Form\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AttributeController extends Controller
{

	/**
	 * Create a new brand attribute
	 * @return Response
	 */
	public function addBrandAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('brand_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		//@TODO
		die('Line:' . __LINE__);

		$response = array(
			'success' => TRUE,
		);

		return new JsonResponse($response);
	}

	/**
	 * Create a new category attribute
	 * @return Response
	 */
	public function addCategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$category_form = $this->createForm(new Type\CategoryType(), array(), array());
		$category_form->handleRequest($request);

		if(!$category_form->isValid())
		{
			$this->addFlash('warning', $this->get('translator')->trans('flash.invalid-request'));
			return $this->redirectToRoute('attribute_display_category');
		}

		$data = $category_form->getData();

		//Search for the category label

		$attr_value_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeValue');
		if($attr_value_repository->existsLabel(AttributeTypeRepository::TYPE_CATEGORY, $data['category_fr'], 'fr')
			or $attr_value_repository->existsLabel(AttributeTypeRepository::TYPE_CATEGORY, $data['category_en'], 'en'))
		{
			$this->addFlash('danger', $this->get('translator')->trans('flash.label-exists'));
			return $this->redirectToRoute('attribute_display_category');
		}

		$em = $this->getDoctrine()->getEntityManager();

		$attr_type_repository = $this->getDoctrine()->getRepository('Ecommerce:AttributeType');
		$category_type = $attr_type_repository->findOneByTypeId(AttributeTypeRepository::TYPE_CATEGORY);

		//Create a new attribute_label
		$attr_label = new \Ecommerce\Entity\AttributeLabel();
		$attr_label->setType($category_type);

		$em->persist($attr_label);
		$em->flush();

		//Create a new category
		$category = new \Ecommerce\Entity\Category();
		$category->setType($category_type);
		$category->setLabel($attr_label);
		$category->setIsActive(TRUE);

		$em->persist($category);
		$em->flush();

		//Create a new attribute_value for FR locale
		$attr_value_fr = new \Ecommerce\Entity\AttributeValue();
		$attr_value_fr->setLabel($attr_label);
		$attr_value_fr->setLocale('fr');
		$attr_value_fr->setName($data['category_fr']);
		$attr_value_fr->setHash($data['category_fr']);
		$attr_value_fr->setUrlkey('category_' . $category->getCategoryId());

		$em->persist($attr_value_fr);
		$em->flush();

		//Create a new attribute_value for EN locale
		$attr_value_en = new \Ecommerce\Entity\AttributeValue();
		$attr_value_en->setLabel($attr_label);
		$attr_value_en->setLocale('en');
		$attr_value_en->setName($data['category_en']);
		$attr_value_en->setHash($data['category_en']);
		$attr_value_en->setUrlkey('category_' . $category->getCategoryId());

		$em->persist($attr_value_en);
		$em->flush();

		$this->addFlash('success', $this->get('translator')->trans('flash.attribute-created'));
		return $this->redirectToRoute('attribute_display_category');
	}

	/**
	 * Create a new color attribute
	 * @return Response
	 */
	public function addColorAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('color_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		//@TODO
		die('Line:' . __LINE__);

		$response = array(
			'success' => TRUE,
		);

		return new JsonResponse($response);
	}

	/**
	 * Create a new material attribute
	 * @return Response
	 */
	public function addMaterialAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('material_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		//@TODO
		die('Line:' . __LINE__);

		$response = array(
			'success' => TRUE,
		);

		return new JsonResponse($response);
	}

	/**
	 * Create a new subcategory attribute
	 * @return Response
	 */
	public function addSubcategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('subcategory_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		//@TODO
		die('Line:' . __LINE__);

		$response = array(
			'success' => TRUE,
		);

		return new JsonResponse($response);
	}

	/**
	 * Set inactive a brand attribute
	 * @return Response
	 */
	public function deleteBrandAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('brand_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		//@TODO
		die('Line:' . __LINE__);

		$response = array(
			'success' => TRUE,
		);

		return new JsonResponse($response);
	}

	/**
	 * Set inactive a category attribute
	 * @return Response
	 */
	public function deleteCategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('category_id')) or null === $request->request->get('is_active'))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$category_repository = $this->getDoctrine()->getRepository('Ecommerce:Category');
		$category = $category_repository->findOneByCategoryId($request->request->get('category_id'));

		if(empty($category))
		{
			return new Response($this->get('translator')->trans('flash.error-try-again'), Response::HTTP_INTERNAL_SERVER_ERROR, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		$category->setIsActive((bool) $request->request->get('is_active'));
		$em = $this->getDoctrine()->getEntityManager();
		$em->flush();

		$category_repository->disableSubCategories($category);

		$response = array(
			'success'	 => TRUE,
			'message'	 => $this->get('translator')->trans('flash.attribute-updated'),
		);

		return new JsonResponse($response);
	}

	/**
	 * Set inactive a color attribute
	 * @return Response
	 */
	public function deleteColorAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('color_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		//@TODO
		die('Line:' . __LINE__);

		$response = array(
			'success' => TRUE,
		);

		return new JsonResponse($response);
	}

	/**
	 * Set inactive a material attribute
	 * @return Response
	 */
	public function deleteMaterialAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('material_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		//@TODO
		die('Line:' . __LINE__);

		$response = array(
			'success' => TRUE,
		);

		return new JsonResponse($response);
	}

	/**
	 * Set inactive a subcategory attribute
	 * @return Response
	 */
	public function deleteSubcategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('subcategory_id')))
		{
			return new Response($this->get('translator')->trans('flash.invalid-request'), Response::HTTP_BAD_REQUEST, array(
				'Content-Type', 'application/json; charset=utf-8'));
		}

		//@TODO
		die('Line:' . __LINE__);

		$response = array(
			'success' => TRUE,
		);

		return new JsonResponse($response);
	}

	/**
	 * Display brands attributes list
	 * @return Response
	 */
	public function displayBrandAction()
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$view = array(
			'head_title' => $this->get('translator')->trans('head_title.attribute.display-brand'),
			'h1_title'	 => $this->get('translator')->trans('h1_title.attribute.display-brand'),
		);

		return $this->render('attribute/default.html.twig', $view);
	}

	/**
	 * Display categories attributes list
	 * @return Response
	 */
	public function displayCategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$form_attributes = array(
			'action' => $this->generateUrl('attribute_add_category'),
			'method' => 'POST',
		);

		$category_form = $this->createForm(new Type\CategoryType(), array(), $form_attributes);
		$attr_value_form = $this->createForm(new Type\AttributeValueType(), array(), array());

		$category_repository = $this->getDoctrine()->getRepository('Ecommerce:Category');
		$categories = $category_repository->getCategories();

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
	 * Display colors attributes list
	 * @return Response
	 */
	public function displayColorAction()
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$view = array(
			'head_title' => $this->get('translator')->trans('head_title.attribute.display-color'),
			'h1_title'	 => $this->get('translator')->trans('h1_title.attribute.display-color'),
		);

		return $this->render('attribute/default.html.twig', $view);
	}

	/**
	 * Display materials attributes list
	 * @return Response
	 */
	public function displayMaterialAction()
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$view = array(
			'head_title' => $this->get('translator')->trans('head_title.attribute.display-material'),
			'h1_title'	 => $this->get('translator')->trans('h1_title.attribute.display-material'),
		);

		return $this->render('attribute/default.html.twig', $view);
	}

	/**
	 * Display subcategories attributes list
	 * @return Response
	 */
	public function displaySubcategoryAction()
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$view = array(
			'head_title' => $this->get('translator')->trans('head_title.attribute.display-subcategory'),
			'h1_title'	 => $this->get('translator')->trans('h1_title.attribute.display-subcategory'),
		);

		return $this->render('attribute/default.html.twig', $view);
	}

	/**
	 * Edit an attribute_value row on POST
	 * @return Response
	 */
	public function editValueAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$attr_value_form = $this->createForm(new Type\AttributeValueType(), array(), array());
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
