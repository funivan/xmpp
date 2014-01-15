#!/usr/bin/env php
<?

  include __DIR__ . '/../vendor/autoload.php';


  $user = 'username@gmail.com';
  $password = 'password';


  $conn = new \Fiv\Xmpp\Xmpp('talk.google.com', 5222, $user, $password, 'xmpphp', 'gmail.com', true, \Fiv\Xmpp\Log::LEVEL_INFO);
  $conn->connect();
  $conn->processUntil('session_start');


  $conn->message("other.user.name@gmail.com/gmail.00145456", "test message");
  $conn->disconnect();
