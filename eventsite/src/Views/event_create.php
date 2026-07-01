<?php
 if (isset($error)){
    echo $error;
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create a new event</title>
</head>
<body>
    <form action="" method="post">
        <label for="event_title">Event title:</label>
        <input type="text" name="title" placeholder="Fill in a title..">
        <br>
        <label for="event_description">Event description:</label>
        <input type="text" name="description" placeholder="Fill in a description">
        <br>
        <input type="submit">
    </form>
</body>
</html>