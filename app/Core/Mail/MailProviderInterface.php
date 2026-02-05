<?php
namespace App\Core\Mail;

interface MailProviderInterface {
    /**
     * Send an email
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body Email HTML body
     * @return bool True on success, False on failure
     */
    public function send($to, $subject, $body);

    /**
     * Return last error message if available
     */
    public function getLastError(): ?string;
}
