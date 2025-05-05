<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Use if you want background queueing
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Requisition; // Make sure this points to your Requisition model
use App\Models\User;       // Make sure this points to your User model

// class RequisitionActionNotification extends Notification implements ShouldQueue // Uncomment for queueing
class RequisitionActionNotification extends Notification
{
    use Queueable;

    protected Requisition $requisition;
    protected string $action; // 'approved' or 'rejected'
    protected User $actingHod;

    /**
     * Create a new notification instance.
     */
    public function __construct(Requisition $requisition, string $action, User $actingHod)
    {
        $this->requisition = $requisition;
        $this->action = $action; // Should be 'approved' or 'rejected'
        $this->actingHod = $actingHod;
    }

    /**
     * Get the notification's delivery channels.
     * We want to store it in the database for the web UI.
     * @param  mixed  $notifiable The entity being notified (e.g., a User model)
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Store in the 'notifications' table
        // You could add 'mail' here too if you want email notifications
        // return ['database', 'mail'];
    }

    /**
     * Get the array representation of the notification for database storage.
     * This is stored in the 'data' column as JSON.
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase(object $notifiable): array
    {
        $actionPastTense = ($this->action === 'approved') ? 'approved' : 'rejected';
        $requisitionId = $this->requisition->id; // Or use a specific identifier if needed
        $hodDepartment = $this->actingHod->department->name ?? $this->actingHod->name; // Example: Get dept name or just HOD name

        // Construct a descriptive message
        $message = sprintf(
            "Requisition #%d was %s by HOD %s.",
            $requisitionId,
            $actionPastTense,
            $hodDepartment // Use the HOD's name or department
        );

        return [
            'requisition_id' => $requisitionId,
            'action'         => $this->action,
            'acting_hod_id'  => $this->actingHod->id,
            'acting_hod_name'=> $this->actingHod->name, // Store for display convenience
            'message'        => $message, // Pre-generate the message
             // Generate a URL to view the specific requisition (adjust route name)
            'link'           => route('hod.requisitions.show', $this->requisition->id),
        ];
    }

    /**
    * (Optional) Get the mail representation of the notification.
    * Only needed if you include 'mail' in the via() method.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\MailMessage
    */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     $details = $this->toDatabase($notifiable); // Reuse data array
    //     $url = $details['link'];

    //     return (new MailMessage)
    //                 ->subject('Requisition Status Update')
    //                 ->line($details['message'])
    //                 ->action('View Requisition', $url)
    //                 ->line('Thank you!');
    // }

    /**
     * Get the array representation of the notification (required).
     * Often used for broadcasting, can be same as toDatabase if not broadcasting.
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable); // Reuse the database format
    }
}