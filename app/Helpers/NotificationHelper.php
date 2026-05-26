<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\Notification;
use App\Models\Subscription;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Mail;

class NotificationHelper
{



//     NotificationHelper::sendUserNotification(
//     $otherUser,
//     "{$user->name} has accepted your connection request.",
//     'Connection Accepted',
//     'User',
//     $user->id,
//     'emails.notification.connection_accepted_received',
//     [
//         'receiverName'       => $otherUser->name,
//         'profile_picture'   => $user->profile_picture ?? '',
//         'receiverLocation'   => $user->location ?? 'Not specified',
//         'receiverAge'        => $user->age ?? 'N/A',
//         'receiverHeight'     => $user->height ?? 'N/A',
//         'receiverOccupation' => $user->profession ?? 'N/A',
//         'receiverBioSnippet' => \Illuminate\Support\Str::limit($user->bio ?? 'No bio available.', 100),
//         'receiverProfileUrl' => "https://usamarry.com/dashboard/profile/{$user->id}",
//         'senderName'         => $user->name,
//     ]
// );

    public static function sendUserNotification($receiver, $message, $subject = 'Notification', $relatedModel = null, $relatedModelId = null, $bladeView = null, $viewData = [])
    {
        $data = [
            'type' => 'custom',
            'message' => $message,
            'related_model' => $relatedModel,
            'related_model_id' => $relatedModelId,
            'is_read' => false,
        ];

        if ($receiver instanceof \App\Models\Admin) {
            $data['admin_id'] = $receiver->id;
        } else {
            $data['user_id'] = $receiver->id ?? null;
        }

        // Save to database
        try {
            Notification::create($data);
        } catch (\Exception $e) {
            throw $e;
        }



    // Send email
    Mail::to($receiver->email)->send(new class($subject, $bladeView, $viewData) extends \Illuminate\Mail\Mailable {
        public $subjectLine;
        public $bladeView;
        public $viewData;

        public function __construct($subjectLine, $bladeView, $viewData)
        {
            $this->subjectLine = $subjectLine;
            $this->bladeView = $bladeView ?? 'emails.notification.connection'; // fallback view
            $this->viewData = $viewData;
        }

        public function build()
        {
            return $this->view($this->bladeView)
                        ->with($this->viewData)
                        ->subject($this->subjectLine);
        }
    });



}



}
