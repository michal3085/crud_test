<?php
class NotificationAdapter {
    private $notification;

    public function __construct(Notification $notification) {
        $this->notification = $notification;
    }

    public function send($message) {
        $this->notification->send($message);
    }
}