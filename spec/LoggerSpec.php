<?php

namespace Lophper\Spec;

use Lophper\Logger;
use Lophper\ECycles;

describe("needsLog", function () {
    it("returns true for ALWAYS", function () {
        $logger = new Logger(ECycles::ALWAYS, 0, 0);
        expect($logger->needsLog())->toBe(true);
    });
    it("returns true for ONCE when lastlog is 0", function () {
        $logger = new Logger(ECycles::ONCE, 0, 0);
        expect($logger->needsLog())->toBe(true);
    });
    it("returns false for ONCE when lastlog is > 0", function () {
        $logger = new Logger(ECycles::ONCE, 1, 0);
        expect($logger->needsLog())->toBe(false);
    });
    it("returns true for DAILY when lastlog is older than one day", function () {
        $now = time();
        $lastLog = strtotime("-25 hours");
        $logger = new Logger(ECycles::DAILY, $lastLog, $now);
        expect($logger->needsLog())->toBe(true);
    });
    it("returns false for DAILY when lastlog less than a day", function () {
        $now = time();
        $lastLog = strtotime("-23 hours");
        $logger = new Logger(ECycles::DAILY, $lastLog, $now);
        expect($logger->needsLog())->toBe(false);
    });
    it("returns true for DAILY when lastlog is older than a month", function () {
        $now = time();
        $lastLog = strtotime("-2 month");
        $logger = new Logger(ECycles::MONTHLY, $lastLog, $now);
        expect($logger->needsLog())->toBe(true);
    });
    it("returns false for DAILY when lastlog less tha a month", function () {
        $now = time();
        $lastLog = strtotime("-70 hours");
        $logger = new Logger(ECycles::MONTHLY, $lastLog, $now);
        expect($logger->needsLog())->toBe(false);
    });
});
