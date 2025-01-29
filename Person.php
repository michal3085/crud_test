<?php
class Person {
    public $id;
    public $imie;
    public $nazwisko;
    public $email;
    public $telefon;

    public function __construct() {}

    public static function getAll($pdo) {
        $stmt = $pdo->query('SELECT * FROM osoby');
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Person');
    }

    public static function add($pdo, $imie, $nazwisko, $email, $telefon) {
        $stmt = $pdo->prepare('INSERT INTO osoby (imie, nazwisko, email, telefon) VALUES (?, ?, ?, ?)');
        $stmt->execute([$imie, $nazwisko, $email, $telefon]);
    }

    public static function update($pdo, $id, $imie, $nazwisko, $email, $telefon) {
        $stmt = $pdo->prepare('UPDATE osoby SET imie = ?, nazwisko = ?, email = ?, telefon = ? WHERE id = ?');
        $stmt->execute([$imie, $nazwisko, $email, $telefon, $id]);
    }

    public static function delete($pdo, $id) {
        $stmt = $pdo->prepare('DELETE FROM osoby WHERE id = ?');
        $stmt->execute([$id]);
    }
}
?>