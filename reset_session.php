<?php
session_start();
session_destroy();
session_start();
$_SESSION['step'] = 0;
?>