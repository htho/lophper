<?php

namespace Lophper\Spec;

use Lophper\Logger;
use Lophper\Cycles;

describe("needsLog", function () {
    $logger = new Logger();
    it("returns true for ALWAYS", function () use ($logger) {
        expect($logger->needsLog(Cycles::ALWAYS, 0, 0))->toBe(true);
    });
    it("returns true for ONCE when lastlog is 0", function () use ($logger) {
        expect($logger->needsLog(Cycles::ONCE, 0, 0))->toBe(true);
    });
    it("returns false for ONCE when lastlog is > 0", function () use ($logger) {
        expect($logger->needsLog(Cycles::ONCE, 1, 0))->toBe(false);
    });
    it("returns true for DAILY when lastlog is older than one day", function () use ($logger) {
        $now = time();
        $lastLog = strtotime("-25 hours");
        expect($logger->needsLog(Cycles::DAILY, $lastLog, $now))->toBe(true);
    });
    it("returns false for DAILY when lastlog less than a day", function () use ($logger) {
        $now = time();
        $lastLog = strtotime("-23 hours");
        expect($logger->needsLog(Cycles::DAILY, $lastLog, $now))->toBe(false);
    });
    it("returns true for DAILY when lastlog is older than a month", function () use ($logger) {
        $now = time();
        $lastLog = strtotime("-2 month");
        expect($logger->needsLog(Cycles::MONTHLY, $lastLog, $now))->toBe(true);
    });
    it("returns false for DAILY when lastlog less tha a month", function () use ($logger) {
        $now = time();
        $lastLog = strtotime("-70 hours");
        expect($logger->needsLog(Cycles::MONTHLY, $lastLog, $now))->toBe(false);
    });
});
