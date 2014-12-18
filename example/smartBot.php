#!/usr/bin/env php
<?

  include __DIR__ . '/../vendor/autoload.php';

  $user = 'user.name@gmail.com';
  $password = 'password';

  $conn = new \Fiv\Xmpp\Xmpp('talk.google.com', 5222, $user, $password, 'xmpphp', 'google.com', true, \Fiv\Xmpp\Log::LEVEL_INFO);

  $conn->autoSubscribe();
  $vCardRequest = array();


  $commands = [
    '-h' => 'Help bump',
    '-r' => 'Get random number',
    '-q' => 'Quit',
  ];


  try {
    $conn->connect();

    while (!$conn->isDisconnected()) {
      $payloads = $conn->processUntil(array('message', 'presence', 'end_stream', 'session_start', 'vcard'));

      foreach ($payloads as $event) {
        $pl = $event[1];
        switch ($event[0]) {
          case 'message':

            $command = $pl['body'];

            if (empty($command)) {
              continue;
            }
            $body = '';

            switch ($command) {

              case '-h':
                foreach ($commands as $c => $info) {
                  $body .= $c . " - " . $info . "\n";
                }
                break;

              case '-r':
                $body = rand(0, 100);
                break;

              case '-q':
                $body = "Bye bye";
                break;

              default:
                $body = 'Invalid command:' . $command . "\nTry -h ";
                break;
            }

            $conn->message($pl['from'], $body, $type = $pl['type']);

            if ($command == "-q") {
              $conn->disconnect();
            }
            break;
          case 'presence':
            print "Presence: {$pl['from']} [{$pl['show']}] {$pl['status']}\n";
            break;
          case 'session_start':
            print "Session Start\n";
            $conn->getRoster();
            $conn->presence($status = "Cheese!");
            break;
          case 'vcard':
            # check to see who requested this vCard
            $deliver = array_keys($vCardRequest, $pl['from']);
            # work through the array to generate a message
            print_r($pl);
            $msg = '';
            foreach ($pl as $key => $item) {
              $msg .= "$key: ";
              if (is_array($item)) {
                $msg .= "\n";
                foreach ($item as $subkey => $subitem) {
                  $msg .= "  $subkey: $subitem\n";
                }
              } else {
                $msg .= "$item\n";
              }
            }
            # deliver the vCard msg to everyone that requested that vCard
            foreach ($deliver as $sendId) {
              # remove the note on requests as we send out the message
              unset($vCardRequest[$sendId]);
              $conn->message($sendId, $msg, 'chat');
            }
            break;
        }
      }
    }
  } catch (\Fiv\Xmpp\Exception $e) {
    echo $e->getMessage();
  }
