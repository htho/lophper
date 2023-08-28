# lophper

Privacy Aware GDPR-Conform **Lo**w-(Level|Maintence|Data|Impact) **PHP** (Track|Counter|Logg)**er**

## The Problem

Counters aim to count how often a page is visited.
It is interesting to know how many *new* users are there.
And how many *re-*users are there.

This is usually solved by Cookies or similar technologies.
But it has privacy implications and requires the users consent (GDPR).

For simple Web-Applications with no desire to monetize,
this is too much trouble.

## The Solution

### Disitinguishing (Re-)Visitors

Lopher uses plain old **HTTP-Caching** in order to find out if a user was there before.

The App sends a log request to the API.
On the first visit the Answer is a simple `200 OK` response, with a `Last-Modified` header.
The API increases the counter.
On consectuive calls, the Browser sends the `If-Modified-Since` header.
The API responses with `304 Not Modified` and may increase a revisit counter.

There can be different log `events` (index page, about page, click on button) and `cycles` (once, daily, monthly).

### Counter-Storage

There must be some kind of persistant storage on the server.
There are many ways: Log-Files with time-stamps, Session-Storage, Databases.
Lopher uses an approach, were statistics are available by looking at the file-system:
Each log increases the size of a file by one byte.

* `{event}/once/{YYYY}-{MM}/{day}`
* `{event}/once-revisit/{YYYY}-{MM}/{day}`
* `{event}/daily/{YYYY}-{MM}/{day}`
* `{event}/daily-revisit/{YYYY}-{MM}/{day}`
* `{event}/monthly/{YYYY}-{MM}/{day}`
* `{event}/monthly-revisit/{YYYY}-{MM}/{day}`

By looking at the files size it is for example possible to tell how many first visitors were there at this day.

There are 3 types of cycles:

1. once
2. daily
3. monthly

The content of the files is not just a dummy byte, but a specific value.

1. once: day of month
2. daily: hour of day
3. monthly: day of month
