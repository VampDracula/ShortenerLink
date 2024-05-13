<?php
require_once 'database.php';
require_once 'config.php';

session_start();

if (!isset($_SESSION['id'])) {
    header("location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
$stmt->bind_param("i", $_SESSION['id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("location: login.php");
    exit;
}

$user = $result->fetch_assoc();

$stmt->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oglink = $_POST['url'];
    $customurl = $_POST['custom-url'];
    $direct = isset($_POST['direct']) && $_POST['direct'] == "on";

    if ($_POST['custom'] == "on") {
        $lnk = $customurl;
    } else {
        $lnk = generateRandomString(10);
    }

    $stmt = $conn->prepare("INSERT INTO link (name, link, direct) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $lnk, $oglink, $direct);

    $stmt->execute();
    $stmt->close();
}
$conn->close();

$data = array(
    'merchant' => $merchand_id,
    'amount' => 9.99,
    'currency' => 'EUR',
    'lifeTime' => 60,
    'feePaidByPayer' => 0,
    'underPaidCover' => 2.5,
    'callbackUrl' => '',
    'returnUrl' => 'https://myshortlink.fr/payment.php',
    'description' => 'Inscription prémium pour le service MyShortLink',
    'orderId' => 'ORD-' . generateRandomString(6),
    'email' => $user['username']
);
$options = array(
    'http' => array(
        'method' => 'POST',
        'content' => json_encode($data),
        'header' => "Content-Type: application/json\r\n" .
            "Accept: application/json\r\n"
    )
);

$context = stream_context_create($options);
$result = file_get_contents("https://api.oxapay.com/merchants/request", false, $context);
$response = json_decode($result);
$_SESSION["trackId"] = $response->trackId;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Espace client</title>
    <link rel="stylesheet" type="text/css" href="home.css">
</head>

<body>
    <header>
        <h1>My Short Link</h1>
        <p>Raccourcissez vos liens et partagez-les facilement</p>
    </header>
    <section>
        <?php if ($user['premium'] == 0): ?>
            <h2>Passer à la version premium</h2>
            <p>Accédez à davantage de fonctionnalités et d'options de personnalisation en passant à la version premium.</p>

            <a href="<?php echo $response->payLink; ?>" class="button">Acheter maintenant (9.99€)</a>
        <?php else: ?>
            <h2>Créer un lien court</h2>
            <form method="post">
                <div>

                    <label for="url">URL:</label>
                    <input type="url" id="url" name="url" required>
                </div>
                <div>
                    <label for="custom">Lien personalisé:</label>
                    <input type="checkbox" id="custom" name="custom">
                </div>
                <div id="custom-input">
                    <label for="custom-url" id="custom-label">Entrer un URL personalisé:</label>
                    <input type="text" id="custom-url" name="custom-url" placeholder="Custom url">
                </div>
                <div>
                    <label for="direct">Instantané:</label>
                    <input type="checkbox" id="direct" name="direct">
                </div>
                <button type="submit" class="button">Racourcir le lien</button>
                <?php if (isset($lnk)): ?>
                    <br />
                    <br />
                    <p style="color: green;">https://myshortlink/s/?u=<?php echo htmlspecialchars($lnk); ?></p>
                <?php endif; ?>
            </form>
            <script>

                document.getElementById('custom-input').style.display = this.checked ? '' : 'none';
                document.getElementById('custom').addEventListener('change', function () {
                    document.getElementById('custom-input').style.display = this.checked ? '' : 'none';
                });
                document.getElementById('custom-url').addEventListener('input', function () {
                    document.getElementById('custom-label').textContent = "Entrer un URL personalisé (" + window.location.origin + "/s/?u=" + document.getElementById('custom-url').value + "):"
                });
            </script>
        <?php endif; ?>
    </section>
</body>

</html>