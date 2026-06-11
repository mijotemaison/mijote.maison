#!/usr/bin/env sh
set -eu

PORT="${PORT:-1234}"

php scripts/bootstrap_database.php || echo "Bootstrap base ignore: verifier les variables MySQL Railway." >&2

exec php -S "0.0.0.0:${PORT}" -t public public/index.php
