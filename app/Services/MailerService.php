<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\Log;

class MailerService
{
    public function sendMail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = env('MAIL_HOST'); // SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = env('MAIL_USERNAME'); // SMTP username
            $mail->Password = env('MAIL_PASSWORD'); // SMTP password
            $mail->SMTPSecure = env('MAIL_ENCRYPTION', 'tls'); // Encryption
            $mail->Port = env('MAIL_PORT'); // TCP port

            // Sender info
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));

            // Recipient
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            return true;
        } catch (Exception $e) {
            Log::error('Mail error: ' . $mail->ErrorInfo);
            return false;
        }
    }
}
