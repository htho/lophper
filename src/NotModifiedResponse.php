<?php

namespace Lophper;

class NotModifiedResponse extends AbstractResponse {
    public function send(): void
    {
        $this->sender->header("HTTP/1.1 304 Not Modified");
    }
}