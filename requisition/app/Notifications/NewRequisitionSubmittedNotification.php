<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue; // Optional: Use for background queueing
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Requisition; // Your Requisition model
use App\Models\User;       // Your User model

// class NewRequisitionSubmittedNotification extends Notification implements ShouldQueue // Uncomment for queueing
class NewRequisitionSubmittedNotification extends Notification
{
    use Queueable;

    protected Requisition $requisition;
    protected User $submitter;

    /**
     * Create a new notification instance.
     *
     * @param Requisition $requisition The newly submitted requisition
     * @param User $submitter The user who submitted the requisition (LRC)
     */
    public function __construct(Requisition $requisition, User $submitter)
    {
        $this->requisition = $requisition;
        $this->submitter = $submitter;
    }

    /**
     * Get the notification's delivery channels.
     * We want to store it in the database for the web UI.
     * @param  mixed  $notifiable The entity being notified (the HOD User model)
     * @return array
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Store in the 'notifications' table
    }

    /**
     * Get the array representation of the notification for database storage.
     * This is stored in the 'data' column as JSON.
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase(object $notifiable): array
    {
        $requisitionId = $this->requisition->id;
        // Attempt to get department name safely
        $departmentName = $this->requisition->department->name ?? 'the relevant department';

        // Construct a descriptive message
        $message = sprintf(
            "New Requisition #%d submitted by %s for %s requiring your review.",
            $requisitionId,
            $this->submitter->name ?? 'a user', // Submitter's name
            $departmentName
        );

        return [
            'requisition_id'   => $requisitionId,
            'submitter_id'     => $this->submitter->id,
            'submitter_name'   => $this->submitter->name ?? 'Unknown Submitter',
            'department_id'    => $this->requisition->department_id,
            'department_name'  => $this->requisition->department->name ?? 'N/A', // Store for convenience
            'message'          => $message, // Pre-generate the display message
             // IMPORTANT: Link to the HOD's view route for this requisition
            'link'             => route('hod.requisitions.show', $this->requisition->id),
        ];
    }

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