<?php

namespace Duo\AdminBundle\Helper;

use Twig\Environment;

class MailerHelper
{
	/**
	 * @var Environment
	 */
	private $twig;

	/**
	 * @var string
	 */
	private $from;

	/**
	 * @var array
	 */
	private $dkimConfig;

	/**
	 * MailerHelper constructor
	 *
	 * @param Environment $twig
	 * @param string $from
	 * @param array $dkimConfig [optional]
	 */
	public function __construct(Environment $twig, string $from, array $dkimConfig = null)
	{
		$this->twig = $twig;
		$this->from = $from;
		$this->dkimConfig = $dkimConfig;
	}

	/**
	 * Prepare message
	 *
	 * @param string $template
	 * @param array $parameters [optional]
	 * @param array|\Swift_Attachment[] $attachments [optional]
	 *
	 * @return \Swift_Message
	 *
	 * @throws \Throwable
	 */
	public function prepare(string $template, array $parameters = [], array $attachments = []): \Swift_Message
	{
		$parameters = $this->twig->mergeGlobals($parameters);
		$template = $this->twig->load($template);

		$subject = $template->renderBlock('subject', $parameters);
		$bodyText = $template->renderBlock('body_text', $parameters);
		$bodyHtml = $template->renderBlock('body_html', $parameters);

		$message = (new \Swift_Message())
			->setFrom($this->from)
			->setSubject($subject)
			->setBody($bodyHtml, 'text/html', 'utf-8')
			->addPart($bodyText, 'text/plain', 'utf-8');

		foreach ($attachments as $attachment)
		{
			if ($attachment instanceof \Swift_Attachment)
			{
				$message->attach($attachment);

				continue;
			}

			$message->attach(
				new \Swift_Attachment(
					$attachment['data'],
					$attachment['filename'],
					$attachment['contentType'] ?? null
				)
			);
		}

		if ($this->dkimConfig !== null &&
			!empty($this->dkimConfig['keyFile']) &&
			!empty($this->dkimConfig['domain']) &&
			!empty($this->dkimConfig['selector']) &&
			is_readable($this->dkimConfig['keyFile']))
		{
			$message->attachSigner(new \Swift_Signers_DKIMSigner(
				file_get_contents($this->dkimConfig['keyFile']),
				$this->dkimConfig['domain'],
				$this->dkimConfig['selector']
			));
		}

		return $message;
	}
}
