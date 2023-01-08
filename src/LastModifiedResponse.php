<?php

namespace Lophper;

class LastModifiedResponse extends AbstractResponse {
    use FormatsHttpDate;
    
    public readonly string $header;

    public function __construct(Sender $sender, int $now) {
        parent::__construct($sender);
        
        $lastModifiedNow = $this->httpDateFormat($now);
        $this->header = "Last-Modified: $lastModifiedNow";
    }
    
    public function send(): void
    {
        $this->sender->header($this->header);
    }
}