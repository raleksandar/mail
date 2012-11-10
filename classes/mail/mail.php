<?php defined('SYSPATH') or die('No direct script access.');

require_once Kohana::find_file('vendor', 'swiftmailer/lib/swift_required');

class Mail_Mail
{
	private static $transports = array();

	private $transport;
	private $templates = array();
	private $message;

	public static function create($transport, array $config = array())
	{
		$config = array_merge((array) Kohana::$config->load("mail.transports.$transport", null), $config);

		$key = crc32(json_encode($config));

		if (!isset(self::$transports[$key]))
		{
			$class = 'Mail_Transport_' . ucfirst(strtolower($transport));

			self::$transports[$key] = new $class($config);
		}

		return new self(self::$transports[$key]);
	}

	private function __construct($transport)
	{
		$this->message = new Swift_Message;
		$this->transport($transport);
	}

	public function transport(Mail_Transport $transport = null)
	{
		if ($transport === null)
		{
			return $this->$transport;
		}
		else
		{
			$this->transport = $transport;
			return $this;
		}
	}

	public function body($mime, View $template = null)
	{
		if ($template === null)
		{
			return Arr::get($this->templates, $mime, null);
		}
		else
		{
			$this->templates[$mime] = $template;
			return $this;
		}
	}

	public function subject($subject = null)
	{
		if ($subject === null)
		{
			return $this->message->getSubject();
		}
		else
		{
			$this->message->setSubject($subject);
			return $this;
		}
	}

	public function send($to)
	{
		if (!count($this->message->getFrom()))
		{
			$this->message->setFrom(Kohana::$config->load('mail.from'));
		}

		$this->message->setTo($to);

		$subject = $this->message->getSubject();

		foreach ($this->templates as $mime => $view)
		{
			$view->set('subject', $subject);
			$this->message->addPart($view->render(), $mime);
		}

		return $this->transport->mailer()->send($this->message);
	}

} // End Mail_Mail
