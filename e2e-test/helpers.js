// @ts-check

export const baseUrl = "http://localhost:8000";

/**
 * @param {string} event 
 * @param {"once" | "daily" | "monthly" } cycle 
 */
export function url(event, cycle) {
    return `${baseUrl}/?e=${event}&c=${cycle[0]}`;
}

/** @typedef {{"last-modified"?: string}} LophperResponseHeaders */
/** @typedef {{"if-modified-since"?: string}} LophperRequestHeaders */

/**
 * @param {Awaited<RequestPromise>} response
 * @returns {Promise<LophperResponseHeaders>}
 */
export async function responseHeaders(response) {
    const headers = await response.headers;
    return headers;
}

/**
 * @param {string} event 
 * @param {"once" | "daily" | "monthly" } cycle 
 * @param {LophperRequestHeaders} headers 
 * @returns {RequestUrlOpts}
 */
export function req(event, cycle, headers={}) {
    return rawReq({
        e: event,
        c: cycle[0],
    }, headers);
}
/**
 * @param {string} event 
 * @param {"once" | "daily" | "monthly" } cycle 
 * @returns {RequestUrlOpts}
 */
export function stats(event, cycle) {
    return {
        method: "get",
        url: `${baseUrl}/stats.php`,
        params: {
            e: event,
            c: cycle[0],
        },
    };
}
/**
 * @param {Record<string, string>} params 
 * @param {LophperRequestHeaders} headers 
 * @returns {RequestUrlOpts}
 */
export function rawReq(params, headers={}) {
    return {
        method: "get",
        url: baseUrl,
        params,
        headers
    };
}