<?php

use App\Helpers\NotificationHelper;

if (!function_exists('send_notification')) {
    /**
     * Send a notification to a user via database and email.
     *
     * @param object $user The user object (must have 'email' and 'id' properties).
     * @param string $message The notification message for the database.
     * @param string $subject The email subject.
     * @param string $bladeView The blade view for the email.
     * @param array $viewData The data to pass to the email view.
     * @param string|null $relatedModel The related model name (e.g., 'Ticket', 'Subscription').
     * @param int|string|null $relatedModelId The ID of the related model.
     * @return void
     */
    function send_notification($user, $message, $subject, $bladeView, $viewData = [], $relatedModel = null, $relatedModelId = null)
    {
        try {
            NotificationHelper::sendUserNotification(
                $user,
                $message,
                $subject,
                $relatedModel,
                $relatedModelId,
                $bladeView,
                $viewData
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("Failed to send global notification: " . $e->getMessage());
        }
    }
}
