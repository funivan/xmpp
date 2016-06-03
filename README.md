#About

[![Build Status](https://travis-ci.org/funivan/xmpp.svg?branch=master)](https://travis-ci.org/funivan/xmpp)

Elegant PHP library for XMPP (aka Jabber, Google Talk, etc).

Example:

```php

   $user = 'username@gmail.com';
   $password = 'password';


   $conn = new \Fiv\Xmpp\Xmpp('talk.google.com', 5222, $user, $password, 'xmpphp', 'gmail.com', true, \Fiv\Xmpp\Log::LEVEL_INFO);
   $conn->connect();
   $conn->processUntil('session_start');

   $conn->message("other.user.name@gmail.com/gmail.00145456", "test message");
   $conn->disconnect();

```


Author: Nathan Fritz, jabber id: fritzy [at] netflint.net
Co-Author: Stephan Wentz, jabber id: stephan [at] wentz.it

Maintainer of this fork: funivan

