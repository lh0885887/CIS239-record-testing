<?php

// IMPORTS
require_once __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'db.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'functions.php';

session_start();

$view   = filter_input(INPUT_GET, 'view') ?: 'list';
$action = filter_input(INPUT_POST, 'action');


// 1. This function checks if the value for $_SESSION['user_id'] is empty. If it is, this would indicate that a user is not logged in, so the next step is to send an HTTP header that redirects to the login page. The function is called if the user is trying to access non-public actions/views (list, add record, etc.). 
function require_login(): void
{
    if (empty($_SESSION['user_id'])) {
        header('Location: ?view=login');
        exit;
    }
}

$public_views   = ['login', 'register'];
$public_actions = ['login', 'register'];

if ($action && !in_array($action, $public_actions, true)) {
    require_login();
}

if (!$action && !in_array($view, $public_views, true)) {
    require_login();
}


switch ($action) {

    // ========== LOGIN/LOGOUT/REGISTER ==========
    // 2. The login case is storing both username and password from the 'login' post request, and then verifying that information by checking if the user exists, and verifying that the password entered matches the stored password. If all checks succeed, we store the user id and name in the session credentials, and grant access/redirect to the record list view. In contrast, the register case will create a user if one does not already exist, and all fields are filled in and valid.
    case 'login':
        $username = trim((string)($_POST['username'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($username && $password) {
            $user = user_find_by_username($username);
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $view = 'list';
            } else {
                $login_error = "Invalid username or password.";
                $view = 'login';
            }
        } else {
            $login_error = "Enter both fields.";
            $view = 'login';
        }
        break;

    case 'logout':
        $_SESSION = [];
        session_destroy();
        session_start();
        $view = 'login';
        break;

    case 'register':
        $username  = trim((string)($_POST['username'] ?? ''));
        $full_name = trim((string)($_POST['full_name'] ?? ''));
        $password  = (string)($_POST['password'] ?? '');
        $confirm   = (string)($_POST['confirm_password'] ?? '');

        if ($username && $full_name && $password && $password === $confirm) {
            $existing = user_find_by_username($username);
            if ($existing) {
                $register_error = "That username already exists.";
                $view = 'register';
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                user_create($username, $full_name, $hash);

                $user = user_find_by_username($username);
                $_SESSION['user_id'] = (int)$user['id'];
                $_SESSION['full_name'] = $user['full_name'];
                $view = 'list';
            }
        } else {
            $register_error = "Complete all fields and match passwords.";
            $view = 'register';
        }
        break;

    // 3. The id of the record from it's "Add to Cart" button is stored. If you click Add to Cart on record 2, ID 2 will be passed into the session as 'cart': [2]. Each time a record is added to the cart, the add_to_cart case is executed and the record id is pushed onto the array for $_SESSION['cart']. On checkout, the cart ids are grabbed, and an individual db row for each record purchased is created via the purchase_create() function.
    case 'add_to_cart':
        require_login();
        $record_id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

        if ($record_id) {
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            $_SESSION['cart'][] = $record_id;
        }
        $view = 'list';
        break;

    case 'checkout':
        require_login();
        $cart_ids = $_SESSION['cart'] ?? [];

        if ($cart_ids) {
            foreach ($cart_ids as $rid) {
                purchase_create((int)$_SESSION['user_id'], (int)$rid);
            }
            $_SESSION['cart'] = [];
        }
        $view = 'checkout_success';
        break;

    // ========== CREATE ==========
    case 'create':
        // Get data from form input
        $title    = trim((string)(filter_input(INPUT_POST, 'title') ?? ''));
        $artist   = trim((string)(filter_input(INPUT_POST, 'artist') ?? ''));
        $price    = (float)(filter_input(INPUT_POST, 'price') ?? 0);
        $format_id = (int)(filter_input(INPUT_POST, 'format_id') ?? 0);

        if ($title && $artist && $format_id) {
            record_create($title, $artist, $price, $format_id);
            $view = 'created';
        } else {
            $view = 'create'; // Missing fields
        }
}

if ($view === 'cart') {
    $cart_ids = $_SESSION['cart'] ?? [];
    $records_in_cart = records_by_ids($cart_ids);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Store</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body>

    <!-- NAV -->
    <?php include __DIR__ . '/components/nav.php'; ?>

    <!-- PARTIALS -->
    <?php
    if ($view === 'login') {
        include __DIR__ . '/partials/login_form.php';
    } elseif ($view === 'register') {
        include __DIR__ . '/partials/register_form.php';
    } elseif ($view === 'cart') {
        include __DIR__ . '/partials/cart.php';
    } elseif ($view === 'checkout_success') {
        include __DIR__ . '/partials/checkout_success.php';
    } elseif ($view === 'list') {
        include __DIR__ . '/partials/records_list.php';
    } elseif ($view === 'create') {
        include __DIR__ . '/partials/record_form.php';
    } elseif ($view === 'created') {
        include __DIR__ . '/partials/record_created.php';
    } elseif ($view === 'deleted') {
        include __DIR__ . '/partials/record_deleted.php';
    }
    ?>

</body>

</html>