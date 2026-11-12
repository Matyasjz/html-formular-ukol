<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Formulář je nutné odeslat metodou POST.');
}

$jmeno = trim($_POST['jmeno'] ?? '');
$email = trim($_POST['email'] ?? '');
$vek = filter_var($_POST['vek'] ?? null, FILTER_VALIDATE_INT);
$datum = $_POST['datum'] ?? '';
$souhlas = ($_POST['souhlas'] ?? '') === 'ano';

$datumObj = DateTimeImmutable::createFromFormat('!Y-m-d', $datum);
$datumPlatne = $datumObj !== false && $datumObj->format('Y-m-d') === $datum;

if ($jmeno === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    $vek === false || $vek < 1 || $vek > 120 || !$datumPlatne || !$souhlas) {
    http_response_code(400);
    exit('Některé údaje nejsou správné nebo chybí souhlas.');
}

function vypis(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zpracování formuláře</title>
</head>
<body>
    <h1>Formulář byl úspěšně odeslán</h1>
    <p>Jméno: <?= vypis($jmeno) ?></p>
    <p>E-mail: <?= vypis($email) ?></p>
    <p>Věk: <?= $vek ?></p>
    <p>Datum narození: <?= vypis($datum) ?></p>
    <p>Souhlas se zpracováním: ano</p>
    <a href="index.html">Zpět na formulář</a>
</body>
</html>
