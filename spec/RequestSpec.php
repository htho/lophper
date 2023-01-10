<?php

namespace Lophper\Spec;

use Lophper\Request;
use Lophper\ECycles;
use Lophper\HttpDateFormatter;

use Exception;

describe("Request", function () {
    describe("constructor", function () {
        it("throws on missing event", function () {
            expect(fn () => new Request(["c" => "a"], [], 0))->toThrow(new Exception("Missing get param e", 1));
        });
        it("throws on invalid event", function () {
            expect(fn () => new Request(["c" => "a", "e" => "../foo"], [], 0))->toThrow(new Exception("Event contains illegal characters!", 1));
        });

        it("accepts valid event and cycle", function () {
            $request = new Request(["c" => "o", "e" => "foo"], [], 0);
            expect($request->event)->toBe("foo");
            expect($request->cycle)->toBe(ECycles::ONCE);
        });

        it("throws on missing cycle", function () {
            expect(fn () => new Request(["e" => "foo"], [], 0))->toThrow(new Exception("Missing get param c", 1));
        });
        it("throws on invalid cycle", function () {
            expect(fn () => new Request(["c" => "x", "e" => "foo"], [], 0))->toThrow(new Exception("Invalid cycle characters!", 1));
        });
    });

    describe("lastLog", function () {
        it("is null for missing httpIfModifiedSince", function () {
            $request = new Request(["c" => "o", "e" => "foo"], [], 0);
            expect($request->lastLog)->toBe(null);
        });
        it("is parsed httpIfModifiedSince", function () {
            $now = time();
            $headerTime = (new HttpDateFormatter($now))->httpDate;

            $request = new Request(["c" => "o", "e" => "foo"], ["HTTP_IF_MODIFIED_SINCE" => $headerTime], 0);
            expect($request->lastLog)->toBe($now);
        });
    });

    describe("needsLog", function () {
        $now = time();
        $httpNow = (new HttpDateFormatter($now))->httpDate;
        $httpAncient = (new HttpDateFormatter(strtotime("1960-01-01")))->httpDate;
        $httpTimeStamp0 = (new HttpDateFormatter(0))->httpDate;
        $http25HoursAgo = (new HttpDateFormatter(strtotime("-25 hours")))->httpDate;
        $http23HoursAgo = (new HttpDateFormatter(strtotime("-23 hours")))->httpDate;
        $http32DaysAgo = (new HttpDateFormatter(strtotime("-32 days")))->httpDate;
        $http27DaysAgo = (new HttpDateFormatter(strtotime("-27 days")))->httpDate;

        it("needs log for ONCE if httpIfModifiedSince is not set", function () use ($now) {
            $request = new Request(["c" => "o", "e" => "foo"], [], $now);
            expect($request->needsLog)->toBe(true);
        });
        it("needs no log for ONCE if httpIfModifiedSince less than 0", function () use ($now, $httpAncient) {
            $request = new Request(["c" => "o", "e" => "foo"], ["HTTP_IF_MODIFIED_SINCE" => $httpAncient], $now);
            expect($request->needsLog)->toBe(false);
        });
        it("needs no log for ONCE if httpIfModifiedSince is 0", function () use ($now, $httpTimeStamp0) {
            $request = new Request(["c" => "o", "e" => "foo"], ["HTTP_IF_MODIFIED_SINCE" => $httpTimeStamp0], $now);
            expect($request->needsLog)->toBe(false);
        });
        it("needs no log for ONCE if httpIfModifiedSince greater 0", function () use ($now, $httpNow) {
            $request = new Request(["c" => "o", "e" => "foo"], ["HTTP_IF_MODIFIED_SINCE" => $httpNow], $now);
            expect($request->needsLog)->toBe(false);
        });

        it("needs log for DAILY if lastlog is not set", function () use ($now) {
            $request = new Request(["c" => "d", "e" => "foo"], [], $now);
            expect($request->needsLog)->toBe(true);
        });
        it("needs log for DAILY if lastlog is older than a day", function () use ($now, $http25HoursAgo) {
            $request = new Request(["c" => "d", "e" => "foo"], ["HTTP_IF_MODIFIED_SINCE" => $http25HoursAgo], $now);
            expect($request->needsLog)->toBe(true);
        });
        it("needs no log for DAILY if lastlog is not older than a day", function () use ($now, $http23HoursAgo) {
            $request = new Request(["c" => "d", "e" => "foo"], ["HTTP_IF_MODIFIED_SINCE" => $http23HoursAgo], $now);
            expect($request->needsLog)->toBe(false);
        });

        it("needs log for MONTHLY if lastlog is not set", function () use ($now) {
            $request = new Request(["c" => "m", "e" => "foo"], [], $now);
            expect($request->needsLog)->toBe(true);
        });
        it("needs log for MONTHLY if lastlog is older than a month", function () use ($now, $http32DaysAgo) {
            $request = new Request(["c" => "m", "e" => "foo"], ["HTTP_IF_MODIFIED_SINCE" => $http32DaysAgo], $now);
            expect($request->needsLog)->toBe(true);
        });
        it("needs no log for MONTHLY if lastlog is not older than a month", function () use ($now, $http27DaysAgo) {
            $request = new Request(["c" => "m", "e" => "foo"], ["HTTP_IF_MODIFIED_SINCE" => $http27DaysAgo], $now);
            expect($request->needsLog)->toBe(false);
        });
    });
});
