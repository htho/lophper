<?php

namespace Lophper;

class HttpDateFormatter {
    use FormatsHttpDate;
    
    public readonly string $httpDate;
    
    public function __construct(int $time) {
        $this->httpDate = $this->httpDateFormat($time);
    }
}