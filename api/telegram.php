<?php
// Устанавливаем заголовки CORS для всех запросов
header("Access-Control-Allow-Origin: https://tverdokhlebalex.github.io/Franchise-Domustroy/");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Если запрос OPTIONS (preflight), завершаем выполнение сразу
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Устанавливаем заголовок Content-Type для JSON-ответа
header("Content-Type: application/json");

// Читаем входной JSON из тела запроса
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Нет полученных данных']);
    exit;
}

// Проверка обязательных ключей
$requiredKeys = [
    'name',
    'phone',
    'area',
    'equipmentCost',
    'productInvestment',
    'baseCost',
    'rentExpensePerMonth',
    'cooperationFormat',
    'finalCost'
];
foreach ($requiredKeys as $key) {
    if (!isset($data[$key])) {
        echo json_encode(['success' => false, 'error' => "Отсутствует ключ: $key"]);
        exit;
    }
}

// Укажите здесь ваш Telegram Bot Token и chat_id.
// Для отправки сообщения в групповой чат chat_id должен быть отрицательным.
$botToken = '8026378729:AAHI9BzekY5xetk3Z9TKeLGNGD0VzBN-R7E';
$chatId = '-1002321283062'; // Проверьте, что chat_id соответствует вашей группе

// Формируем текст сообщения
$message = "Новая заявка на франшизу:\n";
$message .= "Имя: " . $data['name'] . "\n";
$message .= "Телефон: " . $data['phone'] . "\n";
$message .= "Площадь магазина: " . $data['area'] . " м²\n";
$message .= "Стоимость оборудования: " . number_format($data['equipmentCost'], 0, '', ' ') . " ₽\n";
$message .= "Вложения в ассортимент: " . number_format($data['productInvestment'], 0, '', ' ') . " ₽\n";
$message .= "Базовая стоимость: " . number_format($data['baseCost'], 0, '', ' ') . " ₽\n";
$message .= "Арендные расходы (мес): " . number_format($data['rentExpensePerMonth'], 0, '', ' ') . " ₽\n";
$message .= "Формат сотрудничества: " . $data['cooperationFormat'] . "\n";
$message .= "Итоговая стоимость: " . number_format($data['finalCost'], 0, '', ' ') . " ₽\n";

// Формируем URL запроса к Telegram Bot API
$url = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text=" . urlencode($message);

// Для отладки можно временно залогировать URL (раскомментируйте следующую строку)
// error_log("Telegram API URL: " . $url);

// Отправляем запрос к Telegram Bot API
$response = file_get_contents($url);
$result = json_decode($response, true);

if ($result && isset($result['ok']) && $result['ok']) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Ошибка Telegram API']);
}
