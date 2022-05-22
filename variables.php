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
$form_subject = "Обратная связь с сайта {$_SERVER['HTTP_HOST']}"; // тема письма
$file = $_FILES['myfile'];
$mail = new PHPMailer\PHPMailer\PHPMailer();