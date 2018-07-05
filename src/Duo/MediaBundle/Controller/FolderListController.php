<?php

namespace Duo\MediaBundle\Controller;

use Duo\AdminBundle\Configuration\Field\Field;
use Duo\AdminBundle\Configuration\Filter\DateTimeFilter;
use Duo\AdminBundle\Configuration\Filter\StringFilter;
use Duo\AdminBundle\Controller\AbstractListController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/media/folder", name="duo_media_listing_folder_")
 */
class FolderListController extends AbstractListController
{
	use FolderConfigurationTrait;

	/**
	 * {@inheritdoc}
	 */
	protected function defineFields(): void
	{
		$this
			->addField(new Field('name', 'duo.media.listing.field.name'))
			->addField(new Field('createdAt', 'duo.media.listing.field.created_at'))
			->addField(new Field('modifiedAt', 'duo.media.listing.field.modified_at'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function defineFilters(): void
	{
		$this
			->addFilter(new StringFilter('name', 'duo.media.listing.filter.name'))
			->addFilter(new DateTimeFilter('createdAt', 'duo.media.listing.filter.created'))
			->addFilter(new DateTimeFilter('modifiedAt', 'duo.media.listing.filter.modified'));
	}

	/**
	 * {@inheritdoc}
	 *
	 * @Route("/", name="index", methods={ "GET" })
	 */
	public function indexAction(Request $request): Response
	{
		return $this->doIndexAction($request);
	}
}