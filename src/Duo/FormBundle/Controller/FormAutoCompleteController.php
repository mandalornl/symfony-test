<?php

namespace Duo\FormBundle\Controller;

use Duo\AdminBundle\Controller\AbstractAutoCompleteController;
use Duo\AdminBundle\Helper\ORM\QueryHelper;
use Duo\FormBundle\Entity\Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route(name="duo_form_autocomplete_form_")
 */
class FormAutoCompleteController extends AbstractAutoCompleteController
{
	/**
	 * Search name action
	 *
	 * @Route("/name", name="name")
	 * @Method({"GET", "POST"})
	 *
	 * @param Request $request
	 *
	 * @return JsonResponse
	 */
	public function searchNameAction(Request $request)
	{
		$repository = $this->getDoctrine()->getRepository(Form::class);

		$builder = $repository
			->createQueryBuilder('e')
			->where('e.name LIKE :keyword')
			->orderBy('e.name', 'ASC')
			->setParameter('keyword', QueryHelper::escapeLike($request->get('q', '')));

		$count = $this->getCount($builder);

		$this->setFirstResultAndMaxResults($request, $builder);

		$result = $builder
			->select('DISTINCT e.id, e.name text')
			->getQuery()
			->getScalarResult();

		return $this->json([
			'result' => $result,
			'count' => $count
		]);
	}
}