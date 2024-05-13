<?php
require_once 'database.php';
require_once 'config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oglink = $_POST['original-link'];

    $lnk = generateRandomString(10);

    $stmt = $conn->prepare("INSERT INTO link (name, link, direct) VALUES (?, ?, FALSE)");
    $stmt->bind_param("ss", $lnk, $oglink);

    $stmt->execute();
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html>

<head>
    <title>My Short Link</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header>
        <h1>My Short Link</h1>
        <p>Raccourcissez vos liens et partagez-les facilement</p>
        <a href="./home.php" id="premium-button">Espace client</a>
    </header>
    <section>
        <h2>Collez l'URL à raccourcir</h2>
        <form id="shorten-form" method="POST">
            <input type="text" id="original-link" name="original-link" placeholder="Entrez votre URL longue ici">
            <button type="submit">Raccourcir l'URL</button>

            <?php if (isset($lnk)): ?>
                <br/>
                <br/>
                <p style="color: green;">https://myshortlink/s/?u=<?php echo htmlspecialchars($lnk); ?></p>
            <?php endif; ?>
        </form>
        <div id="short-link"></div>
    </section>
    <section>
        <div id="premium-offer">
            <h2>Passez à la version Premium pour bénéficier d'encore plus de fonctionnalités</h2>
            <ul>
                <li>Alias personnalisés pour vos liens courts</li>
                <li>Pas de publicité sur vos liens raccourcis</li>
                <li>Redirections directes pour vos liens</li>
            </ul>
            <a href="./home.php" id="premium-button">Passer à la version Premium</a>
        </div>
        <h2>Caractéristiques</h2>
        <ul>
            <li>Facile à utiliser - il suffit de coller votre URL longue et de cliquer sur "Raccourcir l'URL".</li>
            <li>Les liens raccourcis sont faciles à partager et à mémoriser</li>
            <li>Suivre les clics et les analyses pour mesurer l'engagement</li>
            <li>Intégration avec les médias sociaux, les applications de messagerie, etc.</li>
        </ul>
    </section>
    <footer>
        <p>Copyright 2024 My Short Link</p>
    </footer>
</body>

</html>