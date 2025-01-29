<?php
require 'db.php';
require 'Person.php';
require 'Notification.php';
require 'NotificationFactory.php';
require 'NotificationAdapter.php';
require 'NotificationObserver.php';

$observer = new NotificationObserver();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        Person::add($pdo, $_POST['imie'], $_POST['nazwisko'], $_POST['email'], $_POST['telefon']);
    } elseif (isset($_POST['edit'])) {
        Person::update($pdo, $_POST['id'], $_POST['imie'], $_POST['nazwisko'], $_POST['email'], $_POST['telefon']);
    } elseif (isset($_POST['delete'])) {
        Person::delete($pdo, $_POST['id']);
    } elseif (isset($_POST['subscribe'])) {
        $person = new Person();
        $person->id = $_POST['id'];
        $person->imie = $_POST['imie'];
        $person->nazwisko = $_POST['nazwisko'];
        $person->email = $_POST['email'];
        $person->telefon = $_POST['telefon'];
        $observer->subscribe($person, $_POST['subscribe']);
    } elseif (isset($_POST['unsubscribe'])) {
        $person = new Person();
        $person->id = $_POST['id'];
        $person->imie = $_POST['imie'];
        $person->nazwisko = $_POST['nazwisko'];
        $person->email = $_POST['email'];
        $person->telefon = $_POST['telefon'];
        $observer->unsubscribe($person, $_POST['unsubscribe']);
    } elseif (isset($_POST['send_message'])) {
        $observer->notify($_POST['message']);
    }
}

$people = Person::getAll($pdo);
$subscriptions = $observer->getSubscriptions();
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Zarządzanie osobami</title>
    <style>
        .edit-form {
            display: none;
            margin-top: 10px;
        }
        .subscription-status {
            font-size: 0.9em;
            color: green;
        }
    </style>
    <script>
        function toggleEditForm(id) {
            var form = document.getElementById('edit-form-' + id);
            if (form.style.display === 'none') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</head>
<body>
<h1>Lista osób</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Imię</th>
        <th>Nazwisko</th>
        <th>Email</th>
        <th>Telefon</th>
        <th>Subskrypcje</th>
        <th>Akcje</th>
    </tr>
    <?php foreach ($people as $person): ?>
        <tr>
            <td><?= $person->id ?></td>
            <td><?= $person->imie ?></td>
            <td><?= $person->nazwisko ?></td>
            <td><?= $person->email ?></td>
            <td><?= $person->telefon ?></td>
            <td>
                <?php
                $personSubscriptions = array_filter($subscriptions, function($sub) use ($person) {
                    return $sub['id'] === $person->id;
                });
                foreach ($personSubscriptions as $sub) {
                    echo "<div class='subscription-status'>{$sub['type']}</div>";
                }
                ?>
            </td>
            <td>
                <button onclick="toggleEditForm(<?= $person->id ?>)">Edytuj</button>
                <div id="edit-form-<?= $person->id ?>" class="edit-form">
                    <form method="POST">
                        <input type="hidden" name="id" value="<?= $person->id ?>">
                        <input type="text" name="imie" value="<?= $person->imie ?>" required>
                        <input type="text" name="nazwisko" value="<?= $person->nazwisko ?>" required>
                        <input type="email" name="email" value="<?= $person->email ?>" required>
                        <input type="text" name="telefon" value="<?= $person->telefon ?>" required>
                        <button type="submit" name="edit">Zapisz zmiany</button>
                    </form>
                </div>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $person->id ?>">
                    <button type="submit" name="delete">Usuń</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="id" value="<?= $person->id ?>">
                    <input type="hidden" name="imie" value="<?= $person->imie ?>">
                    <input type="hidden" name="nazwisko" value="<?= $person->nazwisko ?>">
                    <input type="hidden" name="email" value="<?= $person->email ?>">
                    <input type="hidden" name="telefon" value="<?= $person->telefon ?>">
                    <button type="submit" name="subscribe" value="email">Subskrybuj Email</button>
                    <button type="submit" name="subscribe" value="sms">Subskrybuj SMS</button>
                    <button type="submit" name="unsubscribe" value="email">Anuluj Email</button>
                    <button type="submit" name="unsubscribe" value="sms">Anuluj SMS</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<h2>Dodaj nową osobę</h2>
<form method="POST">
    <input type="text" name="imie" placeholder="Imię" required>
    <input type="text" name="nazwisko" placeholder="Nazwisko" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="telefon" placeholder="Telefon" required>
    <button type="submit" name="add">Dodaj</button>
</form>

<h2>Wyślij powiadomienie</h2>
<form method="POST">
    <textarea name="message" placeholder="Wiadomość" required></textarea>
    <button type="submit" name="send_message">Wyślij</button>
</form>
</body>
</html>