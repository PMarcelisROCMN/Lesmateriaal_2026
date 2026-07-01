<?php

/** @var \App\Domain\Event[] $events */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <h1>All events:</h1>

    <form action="">
        <label for="search query">Search:</label>
        <input type="text" placeholder="search term.." name="q">
        <button>search!</button>
    </form>

    <?php

    if(!$events){
        echo '<h3> No event could be found </h3>';
    }else {
        foreach ($events as $event) {
            echo '<p>title: ' . $event->title . '</p>';
            echo "<a href=events/$event->id>show details</a>";
            echo '<br>';
            }
            }
    ?>
</body>

</html>