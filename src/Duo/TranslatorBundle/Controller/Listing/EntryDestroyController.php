<?php

namespace Duo\TranslatorBundle\Controller\Listing;

use Duo\AdminBundle\Controller\Listing\AbstractDestroyController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings/translation", name="duo_translator_listing_entry_")
 */
class EntryDestroyController extends AbstractDestroyController
{
	use EntryConfigurationTrait;

	/**
	 * {@inheritDoc}
	 *
	 * @Route(
	 *     path="/destroy/{id}.{_format}",
	 *     name="destroy",
	 *     requirements={ "id" = "\d+", "_format" = "html|json" },
	 *     defaults={ "_format" = "html" },
	 *     methods={ "GET", "POST" }
	 * )
	 */
	public function destroyAction(Request $request, int $id = null): Response
	{
		return $this->doDestroyAction($request, $id);
	}
}
