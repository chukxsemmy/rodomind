<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    exit('Invalid Request');
}

function clean($value)
{
    return htmlspecialchars(trim($value));
}

$name = clean($_POST['name'] ?? '');
$email = clean($_POST['email'] ?? '');
$phone = clean($_POST['phone'] ?? '');
$company = clean($_POST['company'] ?? '');
$service = clean($_POST['service'] ?? '');
$budget = clean($_POST['budget'] ?? '');
$message = clean($_POST['message'] ?? '');

if (
    empty($name) ||
    empty($email) ||
    empty($message)
) {
    exit("Please complete all required fields.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit("Invalid email address.");
}

$mail = new PHPMailer(true);

try {

    /*
    ==========================================
    SMTP SETTINGS
    ==========================================
    */

    $mail->isSMTP();

    $mail->Host = 'smtp.gmail.com';

    $mail->SMTPAuth = true;

    $mail->Username = 'yourgmail@gmail.com';

    $mail->Password = 'YOUR_APP_PASSWORD';

    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

    $mail->Port = 587;

    /*
    ==========================================
    EMAIL
    ==========================================
    */

    $mail->setFrom('yourgmail@gmail.com', 'Rodomind Website');

    $mail->addAddress('yourgmail@gmail.com');

    $mail->addReplyTo($email, $name);

    $mail->isHTML(true);

    $mail->Subject = 'New Rodomind Contact Form Submission';

    $mail->Body = "

    <h2>New Contact Form Submission</h2>

    <table cellpadding='10' cellspacing='0' border='1' width='100%'>

        <tr>

            <td><strong>Name</strong></td>

            <td>{$name}</td>

        </tr>

        <tr>

            <td><strong>Email</strong></td>

            <td>{$email}</td>

        </tr>

        <tr>

            <td><strong>Phone</strong></td>

            <td>{$phone}</td>

        </tr>

        <tr>

            <td><strong>Company</strong></td>

            <td>{$company}</td>

        </tr>

        <tr>

            <td><strong>Service</strong></td>

            <td>{$service}</td>

        </tr>

        <tr>

            <td><strong>Budget</strong></td>

            <td>{$budget}</td>

        </tr>

        <tr>

            <td><strong>Message</strong></td>

            <td>{$message}</td>

        </tr>

    </table>

    ";

    $mail->send();

    echo "✅ Thank you! Your message has been sent successfully.";

} catch (Exception $e) {

    echo "❌ Unable to send message.";

}