<?php

// Подключение файла, содержащего конфигурацию Laravel
require_once __DIR__.'/bootstrap/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Создание фейкового запроса с идентификатором сессии
$request = Illuminate\Http\Request::create('/');
$request->setSession(app('session')->driver());
$request->session()->setId($_GET['session_id']);
$app->instance('request', $request);

// Обработка запроса через ядро Laravel
$response = $kernel->handle($request);
$response->send();

// Завершение запроса
$kernel->terminate($request, $response);