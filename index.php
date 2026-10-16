<?php
include_once("control.php");
$page = $_GET['action'] ?? "home";

$control = new Controller($page);
