<?php
session_start();
require_once 'includes/conn.php';

if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header('Location: index.php');
    exit;
}

$sql = "SELECT * FROM admin WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['admin']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();