<?php

namespace Duo\FormBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractDestroyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/form-submission", name="duo_form_listing_submission_")
 */
class FormSubmissionDestroyController extends AbstractDestroyController
{
	use FormSubmissionConfigurationTrait;

	/**
	 * {@inheritDoc}
	 *
	 * @Route(
	 *     path="/destroy/{id}.{_format}",
	 *     name="destroy",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *	   defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function destroyAction(Request $request, int $id = null): Response
	{
		return $this->doDestroyAction($request, $id);
	}
}
