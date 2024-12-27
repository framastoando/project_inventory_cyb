<?php
    session_name('inventory_web'); 
    session_start();
    session_destroy();
    header('location:login.php');

?>