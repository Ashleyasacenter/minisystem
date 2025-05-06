<?php
class NotificationController {
    public function sendEmailNotification($to, $subject, $message) {
        $headers = "From: no-reply@example.com\r\n";
        $headers .= "Reply-To: no-reply@example.com\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        if (mail($to, $subject, $message, $headers)) {
            return ['success' => true, 'message' => 'Email sent successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to send email.'];
        }
    }
}
?>
