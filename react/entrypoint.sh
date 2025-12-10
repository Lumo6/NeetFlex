#!/bin/sh

DB_HOST=$(cat /run/secrets/vite_api_base_url)
echo "VITE_API_BASE_URL=$DB_HOST" > /app/.env
echo "✓ Fichier .env créé"

exec "$@"