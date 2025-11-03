<?php
require_once __DIR__ . DIRECTORY_SEPARATOR . 'db.php';

function formats_all(): array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
    SELECT f.id, f.name
    FROM formats f
    ORDER BY f.name
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function records_all(): array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("
    SELECT r.title, r.artist, r.price, f.name
    FROM records r
    JOIN formats f ON r.format_id = f.id
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function record_insert(): void {
    $pdo = get_pdo();

    $title = 'Demo Title';
    $artist = 'Demo Artist';
    $price = 12.99;
    $format_id = 1;

    $stmt = $pdo->prepare("
    INSERT INTO records (title, artist, price, format_id)
    VALUES (:title, :artist, :price, :format_id)
    ");

    $stmt->execute([
        ':title' => $title,
        ':artist' => $artist,
        ':price' => $price,
        ':format_id' => $format_id,
    ]);

    $rowCount = $stmt->rowCount();

    if ($rowCount > 0)
    {
        echo "<p>Insert success: true, rows: $rowCount</p>";
    }
    else {
        echo "<p>Insert success: false, rows: $rowCount</p>";
    }
    
}