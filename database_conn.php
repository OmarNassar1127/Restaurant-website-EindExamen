<?php
// hier wordt een database connectie gemaakt
$user = 'root';
$pass = '';
$db_conn = new PDO('mysql:host=localhost;dbname=excellent', $user, $pass);
