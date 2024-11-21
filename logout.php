<?php
// Created and Programmed by: LeoTechWhiz / Roshan Gautam


session_start();
$_SESSION = array();
session_destroy();
header("location: login.php");

?>
