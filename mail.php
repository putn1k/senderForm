<?php

require 'phpmailer/Exception.php';
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'secret.php'; // этот файл в .gitignore
require 'variables.php';

foreach ( $_POST as $key => $value ) {
  if ( $value != '' ) {
    // формируем HTML-таблицу для отправки на почту
    $message .= ( ($c = !$c) ? '<tr>':"<tr style=\"{$style_tr}\">" )."
                  <td style=\"{$style_td}\"><b>{$key}</b></td>
                  <td style=\"{$style_td}\">{$value}</td>
                </tr>";
    // формируем текстовый формат отправления
    $text .= "$key : $value \n";
  }
}
// формируем сообщение для telegram
$telegram_message = urlencode('<b>' . $form_subject . '</b>' . "\n" . $text);

// если есть файлы добавляем в отправку
if (!empty($file['name'][0])) {
  for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
      $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
      $filename = $file['name'][$ct];
      if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
          $mail->addAttachment($uploadfile, $filename); 
      } else {
          echo "Не удалось прикрепить файл $filename";
      }
  }
}
// отправляем сообщение в телеграм
$sendToTelegram = fopen("https://api.telegram.org/bot{$bot_token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$telegram_message}","r");

$mail->CharSet = 'UTF-8';
$mail->setFrom(	$sender_email );
$mail->addAddress( $admin_email );
$mail->Subject = $form_subject;
$mail->msgHTML( "<table style=\"{$style_table}\">{$message}</table>" );

// отправляем сообщение на почту
if(!$mail->send()) {
  echo 'Сообщение не может быть отправлено.';
  echo 'Ошибка Mailer: ' . $mail->ErrorInfo;
} else {
  echo 'Сообщение отправлено!';
}

$mail->clearAddresses();
$mail->clearAttachments();
