<?php
/** @var \App\Domain\Event $event */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Eventpage</h1>
    <h2><?=$event->title?></h2>
    <br>
    <br>
    <h3>Description of the event:</h3>
    <h4><?= $event->description?></h4>
</body>
</html>