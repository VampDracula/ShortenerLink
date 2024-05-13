<?php
session_start();

if (!isset($_SESSION['trackId'])) {
    header("location: home.php");
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Monitor</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" type="text/css" href="home.css">
</head>
<body>
	<header>
		<h1>Paiement</h1>
		<p>Vérification du paiement (cela peut prendre jusqu'à 30 minutes)...</p>
	</header>
	<section>
		<div id="status"></div>
	</section>
</body>

<script>
setInterval(function() {
  $.get('monitor.php', function(response) {
    if (response === 'OK') {
      window.location.href = 'home.php';
    }
  });
}, 1000);
</script>
</html>