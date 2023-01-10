<?php

namespace Lophper;

class LogWriter
{
    use JoinsDirs;

    public function write(int $data, string $dir, string $filename)
    {
        $finalDir = $this->joinDir("log", $dir);
        $this->ensureDir($finalDir);
        $path = $this->joinDir($finalDir, $filename);
        $binary = pack("C", $data);

        file_put_contents($path, $binary, FILE_APPEND | LOCK_EX);
    }

    private function ensureDir($dir): void
    {
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}
