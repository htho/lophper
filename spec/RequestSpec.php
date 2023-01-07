<?php

namespace Lophper\Spec;

use Lophper\Request;
use Lophper\ECycles;
use Lophper\HttpDateFormatter;

use \Exception;

describe("Request", function () {
    describe("constructor", function () {
        it("throws on missing event", function () {
            expect(fn () => new Request(["c" => "a"], []))->toThrow(new Exception("Missing get param e", 1));
        });
        it("throws on missing cycle", function () {
            expect(fn () => new Request(["e" => "foo"], []))->toThrow(new Exception("Missing get param c", 1));
        });
    });
    describe("forceValidEvent", function () {
        $request = new Request(["e" => "foo", "c" => "a"], []);
        it("returns valid event for valid event", function () use ($request) {
            expect($request->forceValidEvent("foo"))->toBe("foo");
        });
        it("throws on invalid event", function () use ($request) {
            expect(fn () => $request->forceValidEvent("../foo"))->toThrow(new Exception("Event contains illegal characters!", 1));
        });
    });
    describe("forceValidCycle", function () {
        $request = new Request(["e" => "foo", "c" => "a"], []);
        it("returns valid Cycle for valid cycle", function () use ($request) {
            expect($request->forceValidCycle("o"))->toBe(ECycles::ONCE);
            expect($request->forceValidCycle("d"))->toBe(ECycles::DAILY);
            expect($request->forceValidCycle("m"))->toBe(ECycles::MONTHLY);
        });
        it("throws on invalid event", function () use ($request) {
            expect(fn () => $request->forceValidCycle("x"))->toThrow(new Exception("Invalid cycle characters!", 1));
        });
    });
    describe("forceValidLastLog", function () {
        $request = new Request(["e" => "foo", "c" => "a"], []);
        it("returns 0 for null", function () use ($request) {
            expect($request->forceValidLastLog(null))->toBe(0);
        });
        it("returns valid Cycle for valid cycle", function () use ($request) {
            $now = time();
            $headerTime = (new HttpDateFormatter($now))->httpDate;
            expect($request->forceValidLastLog($headerTime))->toBe($now);
        });
    });
});
