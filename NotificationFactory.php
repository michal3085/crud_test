<?php
class NotificationFactory {
    public static function createNotification($type, $contact) {
        switch ($type) {
            case 'email':
                return new EmailNotification($contact);
            case 'sms':
                return new SMSNotification($contact);
            default:
                throw new Exception("Nieznany typ powiadomienia");
        }
    }
}
?>