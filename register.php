<?php
require_once 'database.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $password = $_POST['password'];

    var_dump($username);

    $stmt = $conn->prepare("SELECT id FROM user WHERE username=?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user_exists = true;
    } else {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO user (username, password, name, premium) VALUES (?, ?, ?, FALSE)");
        $stmt->bind_param("sss", $username, $hashed_password, $name);

        $stmt->execute();

        header("location: login.php");
        exit;
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" type="text/css" href="form.css">
</head>

<body>
    <div class="form-container">
        <h2>Inscription</h2>
        <form method="post">
            <label for="name">Nom:</label>
            <input type="text" id="name" name="name" required>

            <label for="username">Email:</label>
            <input type="email" id="username" name="username"
                style="<?php if (isset($username_error)): ?>border: 1px solid red;<?php endif; ?>" required>
            <?php if (isset($user_exists)): ?>
                <p style="color: red;">Email déjà utilisée.</p>
            <?php endif; ?>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Inscription</button>
            <a href="./login.php">Connexion</a>
        </form>
    </div>
</body>

</html>