<?php

// IMPORTS
require __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'db.php';
require __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'functions.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Store</title>
</head>

<body>
    <h2>Unit Test 1 - Formats</h2>
    <?php
    $result = formats_all();

    foreach ($result as $row) {
        $name = $row['name'];

        echo "<p>$name</p>";
    }
    ?>
    <hr>

    <h2>Unit Test 2 — Records JOIN</h2>
    <?php
    $result = records_all();

    foreach ($result as $row) {
        $title = $row['title'];
        $price = $row['price'];
        $format_name = $row['name'];

        echo "<p>$title - $format_name - $$price</p>";
    }
    ?>
    <hr>

    <h2>Unit Test 3 — Insert</h2>
    <?php
    record_insert();
    $result = records_all();

    foreach ($result as $row) {
        $title = $row['title'];
        $format_name = $row['name'];

        echo "<p>$title - $format_name</p>";
    }


    ?>
    <hr>


</body>

</html>