<?php
include("includes/include.php");
require 'vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

/* ================= USER VALIDATION ================= */
$userID = (int)($_GET['id'] ?? 0);
if (!$userID) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid User']);
    exit;
}

$res  = db_query("SELECT * FROM users WHERE id = $userID LIMIT 1");
$user = db_fetch_array($res);

if (!$user) {
    echo json_encode(['status' => 'error', 'message' => 'User not found']);
    exit;
}

/* ================= CACHE CHECK ================= */
if (!empty($user['visiting_card']) && file_exists($user['visiting_card'])) {
    echo json_encode([
        'status'   => 'success',
        'pdf_path' => $user['visiting_card'],
        'cached'   => true
    ]);
    exit;
}

/* ================= CREATE FOLDER ================= */
$folderPath = 'uploads/visiting_cards/';
if (!is_dir($folderPath)) {
    mkdir($folderPath, 0777, true);
}

/* ================= FILE PATH ================= */
$fileName = 'visiting_card_' . $userID . '.pdf';
$filePath = $folderPath . $fileName;

/* ================= DYNAMIC DATA ================= */
$name        = ucwords($user['name']);
$designation = $user['role'] ?? '';
$phone       = $user['mobile'];
$email       = $user['email'];
$website     = $user['website'] ?? 'www.ict360.com';

/* ================= HTML ================= */
$html = '
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">

<style>
@page {
    margin: 0;
}

html, body {
    margin: 0;
    padding: 0;
    width: 100%;
    font-family: DejaVu Sans, sans-serif;
}

.wrapper {
    width: 1000px;
    margin: 0;
    padding: 0;
}

.card {
    width: 1000px;
    height: 571px;
    position: relative;
}

.bg {
    position: absolute;
    top: 0;
    left: 0;
    width: 1000px;
    height: 550px;
}

.left-info {
    position: absolute;
    top: 152px;
    left: 120px;
    color: #ffffff;
    font-size: 16px;
    font-weight: 600;
    line-height: 45px;
}

.right-info {
    position: absolute;
    top: 190px;
    right: 50px;
    text-align: center;
    width: 350px;
}

.right-info h3 {
    margin: 0;
    font-size: 22px;
    color: #000000;
}

.right-info p {
    margin: 0;
    font-size: 14px;
    color: #16284f;
}
</style>
</head>

<body>

<div class="wrapper">

    <!-- BACK SIDE -->
    <div class="card">
        <img class="bg"
             src="https://stagedr.ict360.com/uploads/vc-back.jpg"
             width="1000"
             style="display:block;">

        <div class="left-info">
            <div>+91 ' . $phone . '</div>
            <div>' . $email . '</div>
            <div>' . $website . '</div>
        </div>

        <div class="right-info">
            <h3>' . $name . '</h3>
            <p>' . $designation . '</p>
        </div>
    </div>

    <!-- FRONT SIDE -->
    <img clas="bg" src="https://stagedr.ict360.com/uploads/vc-front.jpg"
         width="1000"
         style="display:block;">

</div>

</body>
</html>
';

/* ================= DOMPDF OPTIONS ================= */
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('dpi', 96);
$options->set('defaultFont', 'DejaVu Sans');
$options->set('defaultMediaType', 'print');

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

/* Exact paper size (MATCH IMAGE WIDTH) */
$dompdf->setPaper([0, 0, 750, 857]);

$dompdf->render();

/* ================= SAVE PDF ================= */
file_put_contents($filePath, $dompdf->output());

/* ================= SAVE PATH ================= */
db_query("
    UPDATE users 
    SET visiting_card = '$filePath'
    WHERE id = $userID
");

/* ================= RESPONSE ================= */
echo json_encode([
    'status'   => 'success',
    'pdf_path' => $filePath,
    'cached'   => false
]);
exit;
