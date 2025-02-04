<?php
header("Access-Control-Allow-Origin: https://tverdokhlebalex.github.io/Franchise-Domustroy/");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// Укажите здесь токен вашего бота и ID чата, куда отправлять сообщения
$botToken = '8026378729:AAHI9BzekY5xetk3Z9TKeLGNGD0VzBN-R7EN';
$chatId = '721573769';

// Получаем JSON данные из POST запроса
$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'Нет полученных данных']);
    exit;
}

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

// URL запроса к Telegram Bot API
$url = "https://api.telegram.org/bot{$botToken}/sendMessage?chat_id={$chatId}&text=" . urlencode($message);

// Отправляем запрос к Telegram API
$response = file_get_contents($url);
$result = json_decode($response, true);

if ($result && $result['ok']) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Ошибка Telegram API']);
}
?>
