<?php

namespace Lophper\Spec;

use Lophper\Response;

class MockResponse extends Response {
    public function __construct(Type $var = null) {
        $this->var = $var;
    }
}
