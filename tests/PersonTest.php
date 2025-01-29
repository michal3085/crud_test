<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../Person.php';
require_once __DIR__ . '/../db.php';

class PersonTest extends TestCase {
    private $pdo;

    protected function setUp(): void {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('CREATE TABLE osoby (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            imie VARCHAR(20),
            nazwisko VARCHAR(60),
            email VARCHAR(100),
            telefon VARCHAR(12)
        )');
    }

    public function testAddPerson() {
        Person::add($this->pdo, 'Jan', 'Kowalski', 'jan@example.com', '123456789');
        $stmt = $this->pdo->query('SELECT * FROM osoby');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(1, $result);
        $this->assertEquals('Jan', $result[0]['imie']);
        $this->assertEquals('Kowalski', $result[0]['nazwisko']);
    }

    public function testUpdatePerson() {
        Person::add($this->pdo, 'Jan', 'Kowalski', 'jan@example.com', '123456789');
        Person::update($this->pdo, 1, 'Janek', 'Nowak', 'janek@example.com', '987654321');

        $stmt = $this->pdo->query('SELECT * FROM osoby WHERE id = 1');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Janek', $result['imie']);
        $this->assertEquals('Nowak', $result['nazwisko']);
    }

    public function testDeletePerson() {
        Person::add($this->pdo, 'Jan', 'Kowalski', 'jan@example.com', '123456789');
        Person::delete($this->pdo, 1);

        $stmt = $this->pdo->query('SELECT * FROM osoby');
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $this->assertCount(0, $result);
    }

    public function testGetAllPersons() {
        Person::add($this->pdo, 'Jan', 'Kowalski', 'jan@example.com', '123456789');
        Person::add($this->pdo, 'Anna', 'Nowak', 'anna@example.com', '987654321');

        $people = Person::getAll($this->pdo);
        $this->assertCount(2, $people);
    }
}