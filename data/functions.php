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


// 4a. This function is called by the register case, and creates a new database record in the users table with data retreived from the register POST request. It takes the username, full name of the user, and a hashed version of the new user's entered password. It returns nothing.
function user_create(string $username, string $full_name, string $hash): void {
    $pdo = get_pdo();
    $sql = "INSERT INTO users (username, full_name, password_hash)
            VALUES (:u, :f, :p)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u'=>$username, ':f'=>$full_name, ':p'=>$hash]);
}

// 4b. This function is used by both the login, and register case to discern if a user already exists in the database by querying for the username they entered. It only takes the username as a parameter (obtained from the POST request). It returns an array of matching usernames (assuming there can be matches), or nothing if no match is found.
function user_find_by_username(string $username): ?array {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :u");
    $stmt->execute([':u'=>$username]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ?: null;
}

// 4c. This function is used to populate the cart page, by getting each record by it's id. The implode statement is required so the query can be executed properly. "Where r.id IN ($ph) is where this comes into play. The prepared $ph variable took each id from the array it got passed in, and formatted it using implode like this "1,2,3,4). It returns an array that will have a database row for each record returned by the sql statement.
function records_by_ids(array $ids): array {
    if (empty($ids)) return [];
    $pdo = get_pdo();
    $ph = implode(',', array_fill(0, count($ids), '?'));
    $sql = "SELECT r.id, r.title, r.artist, r.price, f.name
            FROM records r
            JOIN formats f ON r.format_id = f.id
            WHERE r.id IN ($ph)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($ids);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 4d. This function creates a purchase record in the database for each record that was purchased from the cart. It takes the user_id and each record id that is stored in the session. It returns nothing.
function purchase_create(int $user_id, int $record_id): void {
    $pdo = get_pdo();
    $sql = "INSERT INTO purchases (user_id, record_id, purchase_date)
            VALUES (:u, :r, NOW())";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':u'=>$user_id, ':r'=>$record_id]);
}