<?php
require_once 'database.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password FROM user WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {

        $row = $result->fetch_assoc();
        $hash_password = $row['password'];

        if (password_verify($password, $hash_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("location: home.php");
            exit;
        } else {
            $password_error = "Le mot de passe n'est pas valide.";
        }
    } else {
        $username_error = "Aucun compte avec cet email n'existe.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>

<body>
    <div class="form-container">
        <h2>Connexion</h2>
        <form method="post">
            <label for="username">Email:</label>
            <input type="email" id="username" name="username"
                style="<?php if (isset($username_error)): ?>border: 1px solid red;<?php endif; ?>" required>
            <?php if (isset($username_error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($username_error); ?></p>
            <?php endif; ?>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password"
                style="<?php if (isset($password_error)): ?>border: 1px solid red;<?php endif; ?>" required>

            <?php if (isset($password_error)): ?>
                <p style="color: red;"><?php echo htmlspecialchars($password_error); ?></p>
            <?php endif; ?>

            <button type="submit">Connexion</button>
            <a href="./register.php">Inscription</a>
        </form>
    </div>
</body>

</html>