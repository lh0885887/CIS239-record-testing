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
    SELECT r.id, r.title, r.artist, r.price, f.name
    FROM records r
    JOIN formats f ON r.format_id = f.id
    ");

    $stmt->execute();
    return $stmt->fetchAll();
}

function record_create(string $title, string $artist, float $price, int $format_id): void {
    $pdo = get_pdo();

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

}

function record_update(int $record_id, string $title, string $artist, float $price, int $format_id): void {
    $pdo = get_pdo();

    $stmt = $pdo->prepare(
        "UPDATE records
        SET :title, :artist, :price, :format_id
        WHERE id = :record_id
        ");

        $stmt->execute([
            ':record_id' => $record_id,
            ':title' => $title,
            ':artist' => $artist,
            ':price' => $price,
            ':format_id' => $format_id
        ]);
}