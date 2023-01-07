<?php

namespace Lophper\Spec;

class MockSender implements Sender {
    public array $headers = [];
    public array $echoes = [];

    public function header(string $data) {
        \array_push($this->headers, $data);
    }
    public function echo(string $data) {
        \array_push($this->echoes, $data);
    }
}
