<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

use App\Models\Menu;

class NewMenuNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $menu;

    /**
     * Create a new message instance.
     *
     * @param Menu $menu
     */
    public function __construct(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Check Out Our New Menu Item!'
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_menu_notification',
            with: [
                'name' => $this->menu->name,
                'price' => $this->menu->price,
                'description' => $this->menu->description,
                'image' => asset('storage/' . $this->menu->image),
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}