<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => env('SES_REGION', 'us-east-1'),
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Entities\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'linkedin' => [
        'client_id'         => env('LINKEDIN_CLIENT_ID'),         // Your LinkedIn Client ID
        'client_secret'     => env('LINKEDIN_CLIENT_SECRET'), // Your LinkedIn Client Secret
        'redirect'          => env('LINKEDIN_REDIRECT_URL'),
    ],

    'googlemaps' => [
        'key'         => env('APP_ENV') !== 'production' ? 'AIzaSyC3z-qBBqyXyVjhgvV9hkysg3323Oit4Cw' : 'AIzaSyBRjPkXPfs4JzMhbzHVFrBQ6axzkDUXFjk'
    ],

    'tinypng' => [
        'key'         => env('APP_ENV') !== 'production' ? 'fgZxfS3wcNph6Yhq4mRB6JhVdM6MfQQ4': 'S9hrZ6Rgp0NVgSJ6KncptdVF2P8fXKyR'
    ],

    'firebase' => [
      'database_url' => env('FIREBASE_DATABASE_URL', ''),
      'project_id' => env('FIREBASE_PROJECT_ID', 'proprli-app'),
      'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID', '9bf0818c97bed230f5015570ec646363c8c546d0'),
      // replacement needed to get a multiline private key from .env
      'private_key' => str_replace("\\n", "\n", env('FIREBASE_PRIVATE_KEY', "-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDbC3gJ1exTqu0N\nbHxmm14RFx0RZJMF5LxsRksVdGhVazJn4asUuP5fhszpqV1AoE9SdxRkkPBAQIyf\nZ4aBtZyUGP1SLSMXpWKWJNo8nYhQBbkY4tg8bG9k2hr6i7JdvY8bHZh39GlIWya5\nOrOlgM5p3ueC330SHyD5HLQTHRjxKB43Quio/+uB4TlNfNfcAZvOksjz0rdoL7EN\nUVmaTIc5jPGLZp0TaqyOKbyuAUzjk0JouY0cD7cFDD6pKSK5H+/APSM7Ka6sQdBO\nXGvfgbZ84CEXaleQgEw7NTz1owZnqf2d/BjAa0r1FCn8OmYHmTjHHdrL+HwnMyVf\nnGL+Sx/JAgMBAAECggEAAxwOslIMgrQPnTQL8I92VocF2T48pZXllGJrlLSEOik1\nTBr8qSTGrK++1zbZrW4RLjYlTpetp3XbITSwqMT9oe8YYoskP0TQaVoA1KE2Yw9v\nvSIUSIhQDQYZIbMrvy+umUeSZ2qLd5Ter/y8ueXiGEfrMLUQQxp4xvyQtGBCf0Lo\nMLCeGIAPHUnGlUnZo1NFaoZWNpyF5ZLtlY0qZcY4cSl0JDpSmvMywdeF9ayoKru3\nZHHwt0ZNQ5L9/rVOvm3wWn13l0tWR4MbLuyORF+/QYfPrlfCBge4VBo8b24MxVGI\nhqyAV2oo5F65xJFanBEJ69eAlG95WOV74dwtIzk6MQKBgQDtRJdmO9ufuv/HIZY6\nNNrnM82I0geqAue04/wpsjvA5l7pEiQnTEIqmuhhsC1PvFFBlP/61fDqoqZdOnys\nms8RP528fo6pHqEluUCItbmBMdYKofVIo97xd0EgpXQQCoVihcug1/E9xzodpw7s\nxCIX7f2e3lKhLAh5x2C8RC4qsQKBgQDsVpIdyhFzVqV/OUx6l0Bdx5YHor4m7JqX\nkABH088XDB1WQk6Cf240AC3ogX5SI3jc1xKk0oPFTHyhgZOJ1IX3LtjRdeAZtzU7\ncdt7crHrRJ+culUS1bmkSAvDh3W51Lf2FJIB9RCdspVBUrG8fiLmIoLL6/trpYkt\n2yYnvBxcmQKBgQDJMOSAmDk/VL4XCO/uI2uLqW2bmx1MmHIJ/ViRssTgaXgJgoyN\nlih1IC30yANkHXd5ePMmq8tB6vHPFA0r/4meQqdqcpZd6c8TweZrBk1qs+uf0H3B\nPtOJRWpAWIv2HjXEuVdV6EZz8D/jbQlG03lleTUaRcMjT8dQHDR28AmFwQKBgQCz\nt7EtDffvKTh4Ym4R2uqLZbZk4BCRMOLt1gcgl6Sm9gNpEMr62URILsq6P557f5Xs\n+W+n/p1Nbzhm6E31RbMJoIon3ecoJvVH2vD4EuPQ8EIfwGHpDLU6g77OVTfktCmf\n60yMSHr2MFLndabKm3CWaC/mOfDajuqeAEQnexhmIQKBgFQHpirYXDdpEXYm1537\nsoW3wGNcD/rq0n0MYhntidC/YhFjnJH/QP86cswmAC3/3M3fJda0H0NJtcCoDA1i\nubGb0C1DWrenLecVrjJRY5T5AxGBOyv224oUkFuJC0/Z8MLTWb1l3nKPo+IFBTYT\nWinVY1LRl6W5WyvZuALEyTC+\n-----END PRIVATE KEY-----\n")),
      'client_email' => env('FIREBASE_CLIENT_EMAIL', 'firebase-adminsdk-1x4v0@proprli-app.iam.gserviceaccount.com'),
      'client_id' => env('FIREBASE_CLIENT_ID', '101419019924858301467'),
      'client_x509_cert_url' => env('FIREBASE_CLIENT_x509_CERT_URL', 'https://www.googleapis.com/oauth2/v1/certs'),
    ]

];
