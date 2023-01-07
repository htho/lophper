<?php

namespace Lophper;

class HttpSender implements Sender {
    
    public function header(string $data) {
        header($data);
    }

    public function echo(string $data) {
        echo($data);
    }
}