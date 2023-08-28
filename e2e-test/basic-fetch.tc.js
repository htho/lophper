// @ts-check
import { Selector } from "testcafe";
import { baseUrl, stats, url } from "./helpers";

fixture("basic-fetch")
.page(baseUrl)
.clientScripts([
    { path: "./basic-fetch.tc.cs.js" },
]);

const sendButton = Selector("#send");
const urlInput = Selector("#url");


test("first call with {event: foo cycle: once}", async (t) => {
    const suffix = Math.floor(Math.random() * 1_000_000_000);
    await t.typeText(urlInput, url("foo"+suffix, "once"));
    await t.click(sendButton);

    const response = await t.request(stats("foo"+suffix, "once"))
    const eventStats = response.body;
    await t.expect(eventStats["first"]).eql(1);
    await t.expect(eventStats["more"]).eql(0);
});

test("second call with {event: foo cycle: once}", async (t) => {
    const suffix = Math.floor(Math.random() * 1_000_000_000);
    await t.typeText(urlInput, url("foo"+suffix, "once"));
    await t.click(sendButton);
    await t.click(sendButton);
    await t.click(sendButton);

    const response = await t.request(stats("foo"+suffix, "once"))
    const eventStats = response.body;
    await t.expect(eventStats["first"]).eql(1);
    await t.expect(eventStats["more"]).eql(2);
});
