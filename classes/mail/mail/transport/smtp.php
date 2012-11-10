<?php defined('SYSPATH') or die('No direct script access.');

class Mail_Mail_Transport_Smtp extends Swift_SmtpTransport implements Mail_Transport
{
	private $mailer = null;

	public function __construct(array $config)
	{
		$host = Arr::get($config, 'host', 'localhost');
		$port = Arr::get($config, 'port', 25);
		$security = Arr::get($config, 'security', null);

		parent::__construct($host, $port, $security);

		if (isset($config['username']))
		{
			$this->setUsername($config['username']);
		}

		if (isset($config['password']))
		{
			$this->setPassword($config['password']);
		}
	}

	public function mailer(Swift_Mailer $mailer = null)
	{
		if ($mailer === null)
		{
			if ($this->mailer === null)
			{
				$this->mailer = new Swift_Mailer($this);
			}

			return $this->mailer;
		}
		else
		{
			$this->mailer = $mailer;
			return $this;
		}
	}

} // End Mail_Transport_Smtp
