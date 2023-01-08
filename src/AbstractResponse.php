<?php

namespace Lophper;

abstract class AbstractResponse {
    public Sender $sender;
    public function __construct(Sender $sender) {
        $this->sender = $sender;
    }
    public abstract function send(): void;
}

