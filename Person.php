<?php
class Person {
    public $id;
    public $imie;
    public $nazwisko;
    public $email;
    public $telefon;

    // Konstruktor bez parametrów
    public function __construct() {}

    // Gettery (opcjonalne, ale mogą być przydatne)
    public function getId() { return $this->id; }
    public function getImie() { return $this->imie; }
    public function getNazwisko() { return $this->nazwisko; }
    public function getEmail() { return $this->email; }
    public function getTelefon() { return $this->telefon; }

    // Metoda do pobierania wszystkich osób
    public static function getAll($pdo) {
        $stmt = $pdo->query('SELECT * FROM osoby');
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Person');
    }

    // Metoda do dodawania osoby
    public static function add($pdo, $imie, $nazwisko, $email, $telefon) {
        $stmt = $pdo->prepare('INSERT INTO osoby (imie, nazwisko, email, telefon) VALUES (?, ?, ?, ?)');
        $stmt->execute([$imie, $nazwisko, $email, $telefon]);
    }

    // Metoda do aktualizacji osoby
    public static function update($pdo, $id, $imie, $nazwisko, $email, $telefon) {
        $stmt = $pdo->prepare('UPDATE osoby SET imie = ?, nazwisko = ?, email = ?, telefon = ? WHERE id = ?');
        $stmt->execute([$imie, $nazwisko, $email, $telefon, $id]);
    }

    // Metoda do usuwania osoby
    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare('DELETE FROM osoby WHERE id = ?');
        $stmt->execute([$id]);
    }
}
?>