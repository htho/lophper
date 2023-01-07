<?php

namespace Lophper;

trait JoinsDirs {
    public function joinDir(string ...$segments): string {
        return join(DIRECTORY_SEPARATOR, $segments);
    }
}