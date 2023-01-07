<?php

namespace Lophper\Spec;

use Lophper\Logger;
use Lophper\ECycles;
use Lophper\JoinsDirs;

class DirJoiner {
    use JoinsDirs;
    
    public readonly string $joined;
    public function __construct(string ...$segments) {
        $this->joined = $this->joinDir(...$segments);
    }
}

describe("joinDir", function () {
    it("no sep for one element", function () {
        $dj = new DirJoiner("B");
        expect($dj->joined)->toBe("B");
    });
    it("sep infix", function () {
        $dj = new DirJoiner(ECycles::ALWAYS->value, "B");
        expect($dj->joined)->toBe(ECycles::ALWAYS->value . DIRECTORY_SEPARATOR . "B");
    });
});
