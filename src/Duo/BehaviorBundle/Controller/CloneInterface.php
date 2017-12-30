<?php

namespace Duo\BehaviorBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface CloneInterface
{
	/**
	 * Duplicate entity
	 *
	 * @param Request $request
	 * @param int $id
	 *
	 * @return Response|RedirectResponse|JsonResponse
	 */
	public function duplicateAction(Request $request, int $id);
}