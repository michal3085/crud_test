<?php
class NotificationObserver {
    private $subscriptions = [];
    private $file = 'subscriptions.json';

    public function setFile($file) {
        $this->file = $file;
    }

    private function loadSubscriptions() {
        if (file_exists($this->file)) {
            $data = file_get_contents($this->file);
            return json_decode($data, true) ?? [];
        }
        return [];
    }

    private function saveSubscriptions($subscriptions) {
        file_put_contents($this->file, json_encode($subscriptions));
    }

    public function subscribe($person, $type) {
        $subscriptions = $this->loadSubscriptions();

        $exists = array_filter($subscriptions, function($sub) use ($person, $type) {
            return $sub['id'] === $person->id && $sub['type'] === $type;
        });

        if (empty($exists)) {
            $subscriptions[] = [
                'id' => $person->id,
                'imie' => $person->imie,
                'nazwisko' => $person->nazwisko,
                'email' => $person->email,
                'telefon' => $person->telefon,
                'type' => $type
            ];
            $this->saveSubscriptions($subscriptions);
        }
    }

    public function unsubscribe($person, $type) {
        $subscriptions = $this->loadSubscriptions();
        $subscriptions = array_filter($subscriptions, function($sub) use ($person, $type) {
            return !($sub['id'] === $person->id && $sub['type'] === $type);
        });
        $this->saveSubscriptions(array_values($subscriptions)); // Zresetuj klucze tablicy
    }

    public function notify($message) {
        $subscriptions = $this->loadSubscriptions();

        foreach ($subscriptions as $sub) {
            $notification = NotificationFactory::createNotification($sub['type'], $sub['email'] ?? $sub['telefon']);
            $adapter = new NotificationAdapter($notification);
            $adapter->send($message);
        }
    }


    public function getSubscriptions() {
        return $this->subscriptions;
    }

}