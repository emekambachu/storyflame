<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthCodeMail extends Mailable
{
	use Queueable, SerializesModels;

	public function __construct(
		public string $authCode,
	)
	{
	}

	public function envelope(): Envelope
	{
		return new Envelope(
			subject: config('app.name') . ' - Authentication Code',
		);
	}

	public function content(): Content
	{
		return new Content(
			markdown: 'emails.auth-code',
		);
	}

	public function attachments(): array
	{
		return [];
	}
}
