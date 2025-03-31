<?php
session_start();
require_once("config.php"); // DB eingebunden!


// Variablen initialisieren:
$firstName = "";
$lastName = "";
$email = "";
$address = "";
$zip = "";
$phone = "";
$password = "";
$confirmPassword = "";

// Variable für Erfolgsmeldung:
$success = "";
// Variable für Fehlermeldung:
$errors = [];

// Überprüfen, ob das Formular gesendet wurde: 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $zip = trim($_POST['zip']);
    $phone = trim($_POST['phone']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    

//Validierung:
    if (empty($firstName)) {
        $errors[] = "Vorname ist erforderlich.";
    }
   
    if (empty($lastName)) {
        $errors[] = "Nachname ist erforderlich.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Ungültige E-Mail-Adresse.";
    }

    if (empty($address)) {
        $errors[] = "Adresse ist erforderlich.";
    }

    if (empty($zip) || !preg_match("/^[0-9]{4,5}$/", $zip)) {
        $errors[] = "Postleitzahl ist ungültig.";
    }

    if (empty($phone) || !preg_match("/^[0-9+\s()-]{6,20}$/", $phone)) {
        $errors[] = "Telefonnummer ist ungültig.";
    }

// Passwortvalidierung:
    if (empty($password)) {
        $errors[] = "Passwort ist erforderlich.";
    } elseif (strlen($password) < 8) {
        $errors[] = "Passwort muss mindestens 8 Zeichen lang sein.";
    } elseif (!preg_match("/[A-Z]/", $password)) {
        $errors[] = "Passwort muss mindestens einen Großbuchstaben enthalten.";
    } elseif (!preg_match("/[a-z]/", $password)) {
        $errors[] = "Passwort muss mindestens einen Kleinbuchstaben enthalten.";
    } elseif (!preg_match("/[0-9]/", $password)) {
        $errors[] = "Passwort muss mindestens eine Zahl enthalten.";
    } elseif (!preg_match("/[\W_]/", $password)) {
        $errors[] = "Passwort muss mindestens ein Sonderzeichen enthalten.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwörter stimmen nicht überein.";
    }

// Überprüfen, ob der Benutzername oder die E-Mail bereits existiert:

    if (empty($errors)) {
        $stmt = $db_obj->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errors[] = "E-Mail-Adresse ist bereits registriert.";
        }
        $stmt->close();
    }

// Wenn keine Fehler vorliegen, Benutzer in die Datenbank einfügen:
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db_obj->prepare("INSERT INTO users (first_name, last_name, email, address, zip, phone, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $firstName, $lastName, $email, $address, $zip, $phone, $hashed_password);

        if ($stmt->execute()) {
            $success_msg = "Registrierung erfolgreich!";
            $firstName = $lastName = $email = $address = $zip = $phone = $password = $confirmPassword = "";
        } else {
            $errors[] = "Fehler bei der Registrierung. Bitte versuchen Sie es später erneut.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <title>Registerierung</title>
</head>
<body>


    <div class="container mt-5">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_msg)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success_msg) ?>
        </div>
    <?php endif; ?>

    
    <form method="post" action="register.php" class="form-container">>
        <div class="mb-3">
            <label for="first_name" class="form-label">Vorname</label>
            <input type="text" id="first_name" name="first_name" class="form-control" value="<?= htmlspecialchars($firstName) ?>" required>
        </div>

        <div class="mb-3">
            <label for="last_name" class="form-label">Nachname</label>
            <input type="text" id="last_name" name="last_name" class="form-control" value="<?= htmlspecialchars($lastName) ?>" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-Mail-Adresse</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($email) ?>" required>
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Adresse</label>
            <input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($address) ?>" required>
        </div>

        <div class="mb-3">
            <label for="zip" class="form-label">Postleitzahl</label>
            <input type="text" id="zip" name="zip" class="form-control" value="<?= htmlspecialchars($zip) ?>" required>
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">Telefonnummer</label>
            <input type="text" id="phone" name="phone" class="form-control" value="<?= htmlspecialchars($phone) ?>" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Passwort</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="confirm_password" class="form-label">Passwort wiederholen</label>
            <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Registrieren</button>
    </form>
</div>
</body>
</html>
