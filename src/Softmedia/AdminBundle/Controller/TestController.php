<?php

namespace Softmedia\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Softmedia\AdminBundle\Entity\Page;
use Softmedia\AdminBundle\Entity\Taxonomy;
use Softmedia\AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TestController extends Controller
{
	/**
	 * @Route("/", name="homepage")
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function indexAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		if (($user = $em->getRepository(User::class)->findOneBy(['username' => 'johndoe@softmedia.nl'])) === null)
		{
			$user = (new User())
				->setName('John Doe')
				->setUsername('johndoe@softmedia.nl');

			$em->persist($user);
			$em->flush();
		}

		if (!count($taxonomies = $em->getRepository(Taxonomy::class)->findAll()))
		{
			$taxonomy = new Taxonomy();
			$taxonomy->translate('nl')->setName('Pagina');
			$taxonomy->translate('en')->setName('Page');
			$taxonomy->mergeNewTranslations();

			$em->persist($taxonomy);
			$em->flush();

			$taxonomies[] = $taxonomy;
		}

		$parent = null;
		if (($page = $em->getRepository(Page::class)->findOneBy(['slug' => 'home'])) === null)
		{
			$page = new Page();
			$page->setName('Home');
			$page->setParent($parent);
			$page
				->setPublished(true)
				->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Home')
				->setContent('<p>Dit is de homepagina.</p>');

			$page->translate('en')
				->setTitle('Home')
				->setContent('<p>This is the home page.</p>');

			$page->mergeNewTranslations();

			foreach ($taxonomies as $taxonomy)
			{
				$page->addTaxonomy($taxonomy);
			}

			$em->persist($page);
			$em->flush();

			$parent = $page;
		}

		if (($page = $em->getRepository(Page::class)->findOneBy(['slug' => 'foo'])) === null)
		{
			$page = new Page();
			$page->setName('Foo');
			$page->setParent($parent);
			$page
				->setPublished(true)
				->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Foo')
				->setContent('<p>Dit is foo.</p>');

			$page->translate('en')
				->setTitle('Foo')
				->setContent('<p>This is foo.</p>');

			$page->mergeNewTranslations();

			foreach ($taxonomies as $taxonomy)
			{
				$page->addTaxonomy($taxonomy);
			}

			$em->persist($page);
			$em->flush();

			$parent = $page;
		}

		if (($page = $em->getRepository(Page::class)->findOneBy(['slug' => 'bar'])) === null)
		{
			$page = new Page();
			$page->setName('Bar');
			$page->setParent($parent);
			$page
				->setPublished(true)
				->setCreatedBy($user);

			$page->translate('nl')
				->setTitle('Bar')
				->setContent('<p>Dit is bar.</p>');

			$page->translate('en')
				->setTitle('Bar')
				->setContent('<p>This is bar.</p>');

			$page->mergeNewTranslations();

			foreach ($taxonomies as $taxonomy)
			{
				$page->addTaxonomy($taxonomy);
			}

			$em->persist($page);
			$em->flush();
		}

		return $this->json('Hello world!');
	}
}