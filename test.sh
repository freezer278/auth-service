#!/usr/bin/env bash
TIMEOUT_SEC=8
REQUEST_CONCURRENCY=5
REQUEST_COUNT=25

docker run --rm --net=host --entrypoint=/bin/sh -v `pwd`:/app -w /app jordi/ab \
"-c" \
"if timeout -t ${TIMEOUT_SEC} ab -n ${REQUEST_COUNT} -c ${REQUEST_CONCURRENCY} -T 'application/json' -p /app/test-data.json http://localhost:8000/analytics/save_record; then echo \"Test successful, your code now as 🚀🚀🚀\"; else echo \"Too slow 🐌🐌🐌. Test Failed\"; fi"
