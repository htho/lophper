<?php

namespace Lophper\Spec;

use Lophper\AbstractResponse;

class MockResponse extends AbstractResponse {
    public function __construct(Type $var = null) {
        $this->var = $var;
    }
}
