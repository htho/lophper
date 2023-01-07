<?php

namespace Lophper;

interface Sender
{
    public function header(string $data);
    public function echo(string $data);
}
