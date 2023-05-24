<?php

declare(strict_types=1);

spl_autoload_register(function ($class) {
    $fileName = __DIR__ . '/src/' . ucfirst($class) . '.php';
    if (file_exists($fileName)) {
        require $fileName;
    }
});
set_error_handler("ErrorHandler::handleError");
set_exception_handler("ErrorHandler::handleException");

header("Content-type: application/json; charset=UTF-8");

$parts = explode("/", $_SERVER["REQUEST_URI"]);

if ($parts[1] != "products") {
    http_response_code(404);
    exit;
}

$id = $parts[2] ?? null;

$database = new DataBase("localhost", "api-tester", "root", "1234");

$gateway = new ProductGateway($database);

$controller = new ProductController($gateway);

$controller->processRequest($_SERVER["REQUEST_METHOD"], $id);