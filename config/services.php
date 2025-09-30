<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

     /*
    |--------------------------------------------------------------------------
    | Google Drive Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Google Drive API integration used by CO.DE Platform
    | for secure document storage and management.
    |
    */
    'google' => [
        'credentials_path' => env('GOOGLE_DRIVE_CREDENTIALS_PATH', 'storage/app/google/service-account-key.json'),
        'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),
        'shared_drive_id' => env('GOOGLE_DRIVE_SHARED_DRIVE_ID'),
        'main_folder' => env('GOOGLE_DRIVE_MAIN_FOLDER', 'CO.DE Platform'),
        'max_file_size' => env('GOOGLE_DRIVE_MAX_FILE_SIZE', 52428800), // 50MB
        'retry_attempts' => env('GOOGLE_DRIVE_RETRY_ATTEMPTS', 3),
        'timeout' => env('GOOGLE_DRIVE_TIMEOUT', 120),
        'daily_upload_limit' => env('GOOGLE_DRIVE_DAILY_UPLOAD_LIMIT', 1000),
    ]


];
