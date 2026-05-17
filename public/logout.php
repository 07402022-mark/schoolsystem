<?php
require_once __DIR__ . '/../app/controllers/Auth.php';

$auth = new Auth();
$auth->logout();