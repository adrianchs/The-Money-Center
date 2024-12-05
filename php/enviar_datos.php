<?php
// Incluir el autoload de Composer
require '../phpMailer/src/PHPMailer.php';
require '../phpMailer/src/SMTP.php';
require '../phpMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
// Deshabilitar la caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");


// Código de envío de correos con PHPMailer...

// Verificar que se recibió el formulario mediante POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    #require 'db_connection.php';
    // Obtener los datos del formulario
    $nombre = htmlspecialchars($_POST['nombre']);
    $apellidos = htmlspecialchars($_POST['apellidos']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $telefono = htmlspecialchars($_POST['telefono']);
    $dependencia = htmlspecialchars($_POST['dependencia']);

    $errores = [];
    // Validaciones
    if (!$nombre || strlen($nombre) < 2) {
        $errores[] = "Nombre inválido.";
    }
    if (!$apellidos || strlen($apellidos) < 2) {
        $errores[] = "Apellidos inválidos.";
    }
    if (!$email) {
        $errores[] = "Correo electrónico inválido.";
    }
    if (!$telefono || !preg_match('/^\d{10}$/', $telefono)) {
        $errores[] = "Teléfono inválido (debe ser de 10 dígitos).";
    }
    if (!$dependencia || $dependencia === 'Selecciona una dependencia') {
        $errores[] = "Debes seleccionar una dependencia.";
    }

    // Si hay errores, mostrar mensajes
    if (!empty($errores)) {
        echo "<script>alert('Error: " . implode("\\n", $errores) . "'); window.history.back();</script>";
        exit;
    }

    // Validar que los campos requeridos no estén vacíos
    if (!empty($nombre) && !empty($apellidos) && !empty($email) && !empty($telefono) && !empty($dependencia)) {
        // Crear una instancia de PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // servicio de correo
            $mail->SMTPAuth = true;
            $mail->Username = 'cesaroldan3004@gmail.com'; // dirección de correo electrónico
            $mail->Password = 'tzgjqjymrpsqdzsw'; // contraseña de correo o app password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Cifrado TLS/SSL
            $mail->Port = 465; // Puerto para SMTP seguro cifrado SSL (TLS usa 587)

            // Configuración del correo
            $mail->setFrom('cesaroldan3004@gmail.com', 'The Money Center'); // Dirección y nombre del remitente
            $mail->addAddress('adrianroldan3004@gmail.com'); // Dirección del destinatario
            $mail->addReplyTo($email, "$nombre $apellidos"); // Respuesta del cliente

            // Contenido del correo
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8'; // Establecer la codificación del correo
            $mail->Subject = "Solicitud de Crédito - Desde nuestro sistema web";
            $mail->Body = "
            <html>
            <head>
                <title>Solicitud de Crédito</title>
            </head>
            <body>
                <h2>Detalles del Interezado</h2>
                <p><strong>Nombre:</strong> $nombre</p>
                <p><strong>Apellidos:</strong> $apellidos</p>
                <p><strong>Correo Electrónico:</strong> $email</p>
                <p><strong>Teléfono:</strong> $telefono</p>
                <p><strong>Dependencia:</strong> $dependencia</p>
            </body>
            </html>";

            // Enviar el correo
            $mail->send();


            // Guardar a la DB
            /*
            $stmt = $pdo->prepare("INSERT INTO solicitudes_credito (nombre, apellidos, email, telefono, dependencia) VALUES (:nombre, :apellidos, :email, :telefono, :dependencia)");
            $stmt->execute([
                ':nombre' => $nombre,
                ':apellidos' => $apellidos,
                ':email' => $email,
                ':telefono' => $telefono,
                ':dependencia' => $dependencia
            ]);
            */
            // Cierra la sesión
            session_destroy();

            echo "<script>
                alert('Formulario enviado con éxito. Un asesor se comunicará contigo.');
                window.location.href = '../index.html';
            </script>";
        } catch (Exception $e) {
            echo "<script>
                alert('Hubo un error al enviar el formulario: {$mail->ErrorInfo}');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('Por favor, completa todos los campos requeridos.');
            window.history.back();
        </script>";
    }
     
}
// Cierra la sesión
     session_destroy();

     // Redirige al formulario para asegurar el flujo
     echo "<script>window.location.href='../index.html';</script>";
     exit;
?>
