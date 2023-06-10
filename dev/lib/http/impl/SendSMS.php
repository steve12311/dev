<?php

namespace lib\http\impl;

use lib\http\Message;

class SendSMS implements Message
{

    public function send()
    {
        echo 'Send SMS<br>';
    }
}