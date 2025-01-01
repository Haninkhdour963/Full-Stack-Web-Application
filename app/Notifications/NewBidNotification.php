<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\JobBid;

class NewBidNotification extends Notification
{
    use Queueable;

    protected $bid;

    /**
     * Create a new notification instance.
     *
     * @param JobBid $bid
     */
    public function __construct(JobBid $bid)
    {
        $this->bid = $bid;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // يمكن إضافة 'mail' إذا كنت تريد إرسال بريد إلكتروني
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'You have a new bid on your job posting.',
            'bid_id' => $this->bid->id,
            'job_id' => $this->bid->job_id,
            'technician_id' => $this->bid->technician_id,
            'bid_amount' => $this->bid->bid_amount,
            'status' => 'pending', // Default status
        ];
    }
}