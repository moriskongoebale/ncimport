<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $info = $_POST['info'] ?? '';
    $product = $_POST['product'] ?? '';

    $arr = array(
        'Имя: ' => $name,
        'Телефон: ' => $phone,
        'Информация: ' => $info,
        'Автомобиль:'  => $product
    );

    $token = "6778137550:AAGq-bksXisCWThDTuHFTIU0YUh4jsGaoQo";
    $chat_id = "-4263903223";

    $txt = '';

    foreach ($arr as $key => $value) {
        $txt .= "<b>" . $key . "</b> " . $value . "%0A";
    }

    $sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}", "r");

    if ($sendToTelegram) {
        echo '<script>alert("Ваша заявка отправлена!"); window.location.href = "index.html";</script>';
    } else {
        echo '<script>alert("Произошла ошибка при отправке заявки!"); window.location.href = "index.html";</script>';
    }
    exit;
}
?>
