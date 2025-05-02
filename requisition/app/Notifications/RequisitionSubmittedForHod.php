<?php

namespace App\Notifications;

use App\Models\Requisition; // Import the Requisition model
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Remove 'implements ShouldQueue' if you don't want to queue notifications initially
class RequisitionSubmittedForHod extends Notification /*implements ShouldQueue*/
{
    use Queueable;

    public Requisition $requisition; // Property to hold the requisition

    /**
     * Create a new notification instance.
     */
    public function __construct(Requisition $requisition) // Accept the requisition
    {
        $this->requisition = $requisition;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Choose channels: 'database' stores in DB, 'mail' sends email
        return ['database', 'mail'];
        // You can choose only 'database' if you don't need email yet
        // return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Generate a URL to view the requisition (adjust route name if needed)
        $url = route('hod.requisitions.show', $this->requisition->id);

        return (new MailMessage)
                    ->subject('New Requisition Submitted for Approval')
                    ->greeting('Hello ' . $notifiable->name . ',') // Greet the HOD
                    ->line('A new requisition (ID: ' . $this->requisition->id . ') has been submitted by ' . $this->requisition->user->name . ' for your department.')
                    ->line('Please review it for approval.')
                    ->action('View Requisition', $url) // Button linking to the requisition
                    ->line('Thank you!');
    }

    /**
     * Get the array representation of the notification (for database channel).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        // Data to store in the 'data' column of the notifications table
        return [
            'requisition_id' => $this->requisition->id,
            'submitter_name' => $this->requisition->user->name,
            'message' => 'New requisition #' . $this->requisition->id . ' submitted by ' . $this->requisition->user->name . ' requires your approval.',
            'link' => route('hod.requisitions.show', $this->requisition->id), // Link to view
        ];
    }
}