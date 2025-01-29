<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../NotificationObserver.php';
require_once __DIR__ . '/../Person.php';
require_once __DIR__ . '/../NotificationFactory.php';
require_once __DIR__ . '/../NotificationAdapter.php';
require_once __DIR__ . '/../Notification.php';

class NotificationObserverTest extends TestCase {
    private $observer;
    private $file = 'test.json';

    protected function setUp(): void {
        $this->observer = new NotificationObserver();
        $this->observer->setFile($this->file);

        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    protected function tearDown(): void {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
    }

    public function testSubscribe() {
        $person = new Person();
        $person->id = 1;
        $person->imie = 'Jan';
        $person->nazwisko = 'Kowalski';
        $person->email = 'jan@example.com';
        $person->telefon = '123456789';

        $this->observer->subscribe($person, 'email');

        $subscriptions = json_decode(file_get_contents($this->file), true);
        $this->assertCount(1, $subscriptions);
        $this->assertEquals('email', $subscriptions[0]['type']);
    }

    public function testUnsubscribe() {
        $person = new Person();
        $person->id = 1;
        $person->imie = 'Jan';
        $person->nazwisko = 'Kowalski';
        $person->email = 'jan@example.com';
        $person->telefon = '123456789';

        $this->observer->subscribe($person, 'email');
        $this->observer->unsubscribe($person, 'email');

        $subscriptions = json_decode(file_get_contents($this->file), true);
        $this->assertCount(0, $subscriptions);
    }
}