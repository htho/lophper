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

There can be different log `events` (index page, about page, click on button) and `cycles` (once, always, daily, monthly).

### Counter-Storage

There must be some kind of persistant storage on the server.
There are many ways: Log-Files with time-stamps, Session-Storage, Databases.
Lopher uses an approach, were statistics are available by looking at the file-system:
Each log increases the size of a file by one byte.

* `{event}/always/{YYYY}-{MM}/{day}.ctr`
* `{event}/once/{YYYY}-{MM}/{day}.ctr`
* `{event}/once-revisit/{YYYY}-{MM}/{day}.ctr`
* `{event}/daily/{YYYY}-{MM}/{day}.ctr`
* `{event}/daily-revisit/{YYYY}-{MM}/{day}.ctr`
* `{event}/weekly/{YYYY}-{MM}/{day}.ctr`
* `{event}/weekly-revisit/{YYYY}-{MM}/{day}.ctr`
* `{event}/monthly/{YYYY}-{MM}/{day}.ctr`
* `{event}/monthly-revisit/{YYYY}-{MM}/{day}.ctr`

By looking at the files size it is for example possible to tell how many first visitors were there at this day.

It is even possible to find out when in a day the visit was made.
There are 1440 minutes in a day.
From minute 0 to 6 of the day a 0 is stored.
From minute 6 to 12 of the day a 1 is stored.
From minute 1434 to 1440 a 239 is stored.