<?php
session_start();
$_SESSION['isLoggedIn'] = true;
header("location: index.php?type=im&amp;page=index.html");