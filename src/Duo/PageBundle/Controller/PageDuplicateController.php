<?php

namespace Duo\PageBundle\Controller;

use Duo\AdminBundle\Controller\AbstractDuplicateController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/page", name="duo_page_listing_page_")
 */
class PageDuplicateController extends AbstractDuplicateController
{
	use PageConfigurationTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/duplicate/{id}", name="duplicate", requirements={ "id" = "\d+" }, methods={ "GET", "POST" })
	 */
	public function duplicateAction(Request $request, int $id): Response
	{
		return $this->doDuplicateAction($request, $id);
	}
}