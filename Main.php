<?php
declare(strict_types=1);
require_once 'vendor/autoload.php';

use App\DB;
use App\Form\Form;

$formHandler = new Form();
$response = [];

// Very simple routing
$requestUri = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $response = $formHandler->handleFormSubmission($_POST);

    if ($response['success']) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['errors' => $response['errors']]);
    }

} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($requestUri === 'table') {
        $pdo = DB::getConnection();

        $sortColumn = $_GET['sort'] ?? 'name';
        $sortOrder = $_GET['order'] ?? 'ASC';

        $validColumns = ['name', 'surname', 'email'];
        if (!in_array($sortColumn, $validColumns)) {
            $sortColumn = 'name';
        }
        if (!in_array($sortOrder, ['ASC', 'DESC'])) {
            $sortOrder = 'ASC';
        }

        $query = $pdo->prepare("SELECT * FROM zadanie ORDER BY $sortColumn $sortOrder");
        try {
            $query->execute();
            $data = $query->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($data);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Failed to fetch data']);
        }
    } elseif ($requestUri === 'counter') {
        $surname = $_GET['surname'] ?? '';
        $email = $_GET['email'] ?? '';

        if ($surname) {
            echo json_encode(['count' => $formHandler->counterSurname($surname)]);
        } elseif ($email) {
            echo json_encode(['count' => $formHandler->counterEmailLike($email, 'right')]);
        }

    }
}