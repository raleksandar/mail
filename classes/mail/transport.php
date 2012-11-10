<?php defined('SYSPATH') or die('No direct script access.');

interface Mail_Transport
{
	public function __construct(array $config);
	public function mailer(Swift_Mailer $mailer = null);

} // End Mail_Transport
