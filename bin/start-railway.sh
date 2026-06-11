#!/usr/bin/env sh
set -eu

PORT="${PORT:-1234}"

exec php -S "0.0.0.0:${PORT}" -t public public/index.php
