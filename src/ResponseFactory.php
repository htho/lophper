<?php

namespace Lophper;

class ResponseFactory
{
    public readonly Sender $sender;
    public readonly int $now;

    public function __construct(Sender $sender, int $now) {
        $this->sender = $sender;
        $this->now = $now;
    }
    
    public function getLastModified(): AbstractResponse
    {
        return new LastModifiedResponse($this->sender, $this->now);
    }
    
    public function getNotModified(): AbstractResponse
    {
        return new NotModifiedResponse($this->sender);
    }
}

