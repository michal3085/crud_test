<?php
interface Notification {
    public function send($message);
}

class EmailNotification implements Notification {
    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function send($message) {
        echo "Wysyłanie email do {$this->email} z wiadomością: {$message}\n";
    }
}

class SMSNotification implements Notification {
    private $telefon;

    public function __construct($telefon) {
        $this->telefon = $telefon;
    }

    public function send($message) {
        echo "Wysyłanie SMS do {$this->telefon} z wiadomością: {$message}\n";
    }
}
?>