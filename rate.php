<?php
require "db.inc.php";

$POST = filter_var_array($_POST, FILTER_SANITIZE_STRING);
$POSTI = filter_var_array($_POST, FILTER_SANITIZE_NUMBER_INT);

if (isset($POST['starRate'])) {
    // Utilisation de crochets pour accéder aux éléments
    $starRate = mysqli_real_escape_string($conn, $POSTI['starRate']);
    $rateMsg = mysqli_real_escape_string($conn, $POST['rateMsg']);
    $date = mysqli_real_escape_string($conn, $POST['date']);
    $name = mysqli_real_escape_string($conn, $POST['name']);

    // Vérifier si l'utilisateur existe déjà
    $sql = $conn->prepare("SELECT * FROM rate WHERE userName=?");
    $sql->bind_param('s', $name);
    $sql->execute();
    $res = $sql->get_result();
    $rst = $res->fetch_assoc();
    $val = $rst["userName"];

    if (!$val) {
        // Insertion d'un nouvel enregistrement
        $stmt = $conn->prepare("INSERT INTO rate (userName, userReview, userMessage, dateReview) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $name, $starRate, $rateMsg, $date);
        $stmt->execute();
        echo "Inserted successfully";
    } else {
        // Mise à jour de l'enregistrement existant
        $stmt = $conn->prepare("UPDATE rate SET userName = ?, userReview = ?, userMessage = ?, dateReview = ? WHERE userName = ?");
        $stmt->bind_param('sssss', $name, $starRate, $rateMsg, $date, $name);
        $stmt->execute();
        echo "Update successfully";
    }
}
?>
