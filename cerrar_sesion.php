<?php
session_start();
// eliminar los datos de sesiòn
session_destroy();
header("Location: login.php");