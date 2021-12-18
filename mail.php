<?php
$c = true;
// CSS стили таблицы письма
$style_table = '
  width: 100%;
  border-collapse: collapse;
';
// CSS стили строки таблицы письма
$style_tr = '
  background-color: #879bc0;
';
// CSS стили ячейки таблицы письма
$style_td = '
  padding: 10px;
  border: #bcc7dc 1px solid;
  vertical-align: top;
';

$sender_email = "noreply@{$_SERVER['HTTP_HOST']}"; // с этого адреса приходят письма
$admin_email  = "htc.putnik@gmail.com"; // на этот адрес приходит отправленная форма
$additional_email_1  = "ex-copy@mail.com"; // адресат для копии
$form_subject = "Обратная связь с сайта {$_SERVER['HTTP_HOST']}"; // тема письма
$file = $_FILES['myfile'];

$token = ""; // токен telegram-бота
$chat_id = ""; // id чата, куда приходят сообщения

foreach ( $_POST as $key => $value ) {
  if ( $value != "" && $key != "admin_email" && $key != "form_subject" ) {
    $message .= "
    " . ( ($c = !$c) ? '<tr>':'<tr style="'.$style_tr.'">' ) . "
      <td style='".$style_td."'><b>$key</b></td>
      <td style='".$style_td."'>$value</td>
    </tr>
    ";
    $text .= "$key : $value \n";
  }
}

if (!empty($file['name'][0])) {
  for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
      $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
      $filename = $file['name'][$ct];
      if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
          $mail->addAttachment($uploadfile, $filename);
          $rfile[] = "Файл $filename прикреплён";
      } else {
          $rfile[] = "Не удалось прикрепить файл $filename";
      }
  }
}

$dataar = urlencode('<b>' . $form_subject . '</b>' . "\n" . $text);

$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$dataar}","r");

if ($sendToTelegram) {
echo "Спасибо, сообщение отправлено";
} else {
echo "Ошибка, сообщение не отправлено";
}

require 'phpmailer/phpmailerautoload.php';

$mail = new PHPMailer;
$mail->CharSet = 'UTF-8';
$mail->setFrom(	$sender_email );
$mail->addAddress( $admin_email );
$mail->Subject = $form_subject;
$mail->msgHTML( "<table style='".$style_table."'>$message</table>" );

if(!$mail->send()) {
  echo 'Сообщение не может быть отправлено.';
  echo 'Ошибка Mailer: ' . $mail->ErrorInfo;
} else {
  echo 'Сообщение отправлено!';
}
$mail->clearAddresses();
$mail->clearAttachments();
