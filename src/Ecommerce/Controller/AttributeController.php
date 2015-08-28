<?php

namespace Ecommerce\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AttributeController extends Controller
{

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

	public function addCategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('category_id')))
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

	public function deleteCategoryAction(Request $request)
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		if(empty($request->request->get('category_id')))
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

	public function displayCategoryAction()
	{
		if(!$this->isAdmin())
		{
			return $this->redirectToRoute('home');
		}

		$view = array(
			'head_title' => $this->get('translator')->trans('head_title.attribute.display-category'),
			'h1_title'	 => $this->get('translator')->trans('h1_title.attribute.display-category'),
		);

		return $this->render('attribute/default.html.twig', $view);
	}

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
