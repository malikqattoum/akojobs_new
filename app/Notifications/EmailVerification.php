<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $entity;
	protected $entityRef;
	
	public function __construct($entity, $entityRef)
	{
		$this->entity = $entity;
		$this->entityRef = $entityRef;
	}
	
	public function via($notifiable)
	{
		if (!isset($this->entityRef['name'])) {
			return false;
		}
		
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{
		$prefLang = "";
		if($this->entity['pref_lang'] == "ar")
			$prefLang = "ar";
		else
			$prefLang = "en";
		$verificationUrl = lurl('verify/' . $this->entityRef['slug'] . '/email/' . $this->entity->email_token);
		
		return (new MailMessage)
			->subject(trans('mail.email_verification_title', [], $prefLang))
			->greeting(trans('mail.email_verification_content_1', ['userName' => $this->entity->{$this->entityRef['name']},], $prefLang))
			->line(trans('mail.email_verification_content_2', [], $prefLang))
			->action(trans('mail.email_verification_action', [], $prefLang), $verificationUrl)
			->line(trans('mail.email_verification_content_3', ['appName' => config('app.name')], $prefLang));
	}
}
