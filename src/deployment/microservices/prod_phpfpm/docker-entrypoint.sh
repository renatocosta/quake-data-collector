#!/bin/sh
set -e

# Copy the php environment from the secret location into the /app folder

if [ -e /run/secrets/php_env ]
then
    cp /run/secrets/php_env /var/www/.env
else
    echo "PHP environment secret is missing, this container expects a secret with the name php_env"
fi

# Copy the Laravel passport keys into the /storage folder

if [ -e /run/secrets/passport_oauth_public ] || [ -e /run/secrets/passport_oauth_private ]
then
    cp /run/secrets/passport_oauth_public /var/www/storage/oauth-public.key
    cp /run/secrets/passport_oauth_private /var/www/storage/oauth-private.key
else
    echo "Laravel passport keys are missing, this container expects two secrets with the names passport_oauth_public and passport_oauth_private"
fi

# Copy firebase private key into the /storage folder

if [ -e /run/secrets/firebase_cred ]
then
    cp /run/secrets/firebase_cred /var/www/storage/firebase-credentials.json
else
    echo "Firebase private key json is missing, this container expects a secret with name firebase_cred"
fi

exec "$@"
