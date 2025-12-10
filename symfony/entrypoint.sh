#!/bin/sh

# Script d'entrée pour gérer les secrets Symfony
DATABASE_URL=$(cat /run/secrets/database_url)
echo "DATABASE_URL=$DATABASE_URL" > /app/.env
echo "✓ Fichier .env créé"

APP_SECRET=$(cat /run/secrets/app_secret)
echo "APP_SECRET=$APP_SECRET" > /app/.env
echo "✓ Fichier .env créé"

exec "$@"
