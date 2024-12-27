<?php
require_once 'function.php';
if (isset($_SESSION['log'])) {
    $email = $_SESSION['email'];
} else {
    header('location:login.php');
    exit;
}
?>