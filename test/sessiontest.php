<?php

class Koekje {

private static int $count = 0;

public int $id;

public function __construct()
{
    $this->id = self::$count;
    self::$count += 1;
}


}
$koek = new Koekje();
$bar = new Koekje();

session_start();
// session_destroy();
// exit;

$_SESSION['basket'] = [];

array_push($_SESSION['basket'], $koek);
array_push($_SESSION['basket'], $bar);
// array_push($_SESSION['basket'], $bar);
// $_SESSION['basket'] = $bar;

if (in_array($bar, $_SESSION['basket'])){
    echo 'test';
}

echo '<pre>';
var_dump($_SESSION['basket']);
echo '</pre>';