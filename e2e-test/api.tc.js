// @ts-check
import { rawReq, req, responseHeaders } from "./helpers";

fixture("lophper");

test("first call with {event: foo cycle: once}", async (t) => {
    const response = await t.request(req("foo", "once"));

    await t.expect(response.status).eql(200);
    const headers = await responseHeaders(response);
    await t.expect(headers["last-modified"]).typeOf("string");
});

test("second call with {event: foo cycle: once}", async (t) => {
    const firstResponse = await t.request(req("foo", "once"));
    
    const firstHeaders = await responseHeaders(firstResponse);
    const lastModified = firstHeaders["last-modified"];

    const secondResponse = await t.request(req("foo", "once", {"if-modified-since": lastModified}));
    const secondHeaders = await responseHeaders(secondResponse);
    await t.expect(secondHeaders["last-modified"]).typeOf("undefined");
});

test("fails with missing event {cycle: once}", async (t) => {
    const response = await t.request(rawReq({c: "o"}));
    await t.expect(response.status).eql(500);
});
test("fails with invalid event {cycle: once}", async (t) => {
    await t.expect((await t.request(req("foo", "once"))).status).eql(200);

    await t.expect((await t.request(req("foo/x", "once"))).status).eql(500);
    await t.expect((await t.request(req("foo!", "once"))).status).eql(500);
    await t.expect((await t.request(req("foo§", "once"))).status).eql(500);
    await t.expect((await t.request(req("foo bar", "once"))).status).eql(500);
});
test("fails with missing cycle {event: foo}", async (t) => {
    const response = await t.request(rawReq({e: "foo"}));
    await t.expect(response.status).eql(500);
});
test("fails with invalid cycle {event: foo}", async (t) => {
    await t.expect((await t.request(rawReq({e: "foo", c: "o"}))).status).eql(200);
    await t.expect((await t.request(rawReq({e: "foo", c: "d"}))).status).eql(200);
    await t.expect((await t.request(rawReq({e: "foo", c: "m"}))).status).eql(200);

    await t.expect((await t.request(rawReq({e: "foo", c: "a"}))).status).eql(500);
});
