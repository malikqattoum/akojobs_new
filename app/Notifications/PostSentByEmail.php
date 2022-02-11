<?php


namespace App\Notifications;

use App\Helpers\ArrayHelper;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class PostSentByEmail extends Notification implements ShouldQueue
{
	use Queueable;
	
	protected $post;
	protected $mailData;
	
	public function __construct(Post $post, $mailData)
	{
		$this->post = $post;
		$this->mailData = (is_array($mailData)) ? ArrayHelper::toObject($mailData) : $mailData;
	}
	
	public function via($notifiable)
	{
		return ['mail'];
	}
	
	public function toMail($notifiable)
	{
		$attr = ['slug' => slugify($this->post->title), 'id' => $this->post->id];
		$postUrl = lurl($this->post->uri, $attr);
		
		return (new MailMessage)
			->replyTo($this->mailData->sender_email, $this->mailData->sender_email)
			->subject(trans('mail.post_sent_by_email_title', [
				'appName'     => config('app.name'),
				'countryCode' => $this->post->country_code
			]))
			->line(trans('mail.post_sent_by_email_content_1', ['senderEmail' => $this->mailData->sender_email]))
			->line(trans('mail.post_sent_by_email_content_2'))
			->line(trans('mail.Job URL') . ':  <a href="' . $postUrl . '">' . $postUrl . '</a>');
	}
}
