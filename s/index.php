<?php
require_once '../database.php';

if (!isset($_GET["u"])) {
    header("location: ../index.php");
    exit;
}


$stmt = $conn->prepare("SELECT * FROM link WHERE name=?");
$stmt->bind_param("s", $_GET["u"]);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    header("location: ../index.php");
    exit;
}

$link = $result->fetch_assoc();

if ($link["direct"]) {
    header("location: " . $link["link"]);
    exit;
} else {
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <title>Countdown</title>
        <style>
            body {
                background-color: #f5f5f5;
                font-family: Arial, sans-serif;
            }

            header {
                background-color: #333;
                color: #fff;
                padding: 20px;
                text-align: center;
            }

            section {
                background-color: #fff;
                border-radius: 5px;
                box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                margin: 50px auto;
                padding: 20px;
                text-align: center;
                width: 50%;
            }

            #countdown {
                font-size: 50px;
                line-height: 50px;
            }
        </style>
    </head>

    <body>
        <header>
            <h1>Redirection</h1>
            <p>Redirection dans 5 secondes...</p>
        </header>
        <section>
            <div id="countdown"></div>
        </section>
    </body>

    <script>
        var url = '<?php echo $link["link"] ?>';
        var countdown = 5;

        document.getElementById('countdown').innerHTML = countdown;
        setInterval(function () {
            countdown--;
            document.getElementById('countdown').innerHTML = countdown;
            if (countdown === 0) {
                window.location.href = url;
            }
        }, 1000);
    </script>

    </html>
    <?php
}

$stmt->close();

$conn->close();


?>