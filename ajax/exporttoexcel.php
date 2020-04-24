<?php
include '../init.php';

$excel->export();
header('Location: ' . SITE_URL);