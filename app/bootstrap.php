<?php

//add our auto loader
require_once __DIR__ . '/../vendor/autoload.php';

//database
$dbh = new PDO('mysql:host=secnem.com;port=3306;dbname=secnem_priceline', 'secnem_priceline', 'Xy4M34L0FZgU', array( PDO::ATTR_PERSISTENT => false));

?>