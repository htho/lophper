<?php

namespace Lophper;

class NotModifiedResponse extends Response {
    public function send(): void
    {
        $this->sender->header("HTTP/1.1 304 Not Modified");
    }
}