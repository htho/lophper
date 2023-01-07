<?php

namespace Lophper;

class LogWriter
{
    use JoinsDirs;

    public function write(int $data, string $dir, string $filename)
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $path = $this->joinDir($dir, $filename);
        $binary = pack("C", $data);

        file_put_contents($path, $binary, FILE_APPEND | LOCK_EX);
    }
}