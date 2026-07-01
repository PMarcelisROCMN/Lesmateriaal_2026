<?php

/** @var \App\Domain\Event $event */

if (isset($error)) {
    echo $error;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit an event</title>
</head>

<body>
    <form action="" method="post">
        <label for="event_title">Event title:</label>
        <input type="text" name="title" value=<?= $event->title ?>>
        <br>
        <label for="event_description">Event description:</label>
        <input type="text" name="description" value=<?= $event->description ?>>
        <br>
        <input type="submit">
    </form>
</body>

</html>