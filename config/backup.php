<?php

return [
    'backup' => [
        'name' => env('APP_NAME', 'laravel-backup'),

        'source' => [
            'files' => [
                'include' => [],
                'exclude' => [
                    base_path(),
                ],
                'follow_links' => false,
            ],

            'databases' => [
                'mysql',
            ],
        ],

        'mysql' => [
        'dump_command_path' => 'D:/xampp/mysql/bin/', // Windows path
        'dump_command_timeout' => 60 * 5, // 5 minute timeout
        'dump_using_single_transaction' => true,
        'use_extended_inserts' => true,
    ],

        'destination' => [
            'filename_prefix' => '',
            'disks' => [
                'local',
            ],
        ],

        // SUPPRIMEZ ces lignes qui causent l'erreur :
        // 'password' => env('BACKUP_ARCHIVE_PASSWORD'),
        // 'encryption' => 'default-encryption',

        'temporary_directory' => storage_path('app/backup-temp'),
    ],

    'notifications' => [

    'notifications' => [
        \Spatie\Backup\Notifications\Notifications\BackupHasFailedNotification::class => ['mail'],
        \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFoundNotification::class => ['mail'],
        \Spatie\Backup\Notifications\Notifications\CleanupHasFailedNotification::class => ['mail'],
        \Spatie\Backup\Notifications\Notifications\BackupWasSuccessfulNotification::class => ['mail'],
        \Spatie\Backup\Notifications\Notifications\HealthyBackupWasFoundNotification::class => ['mail'],
        \Spatie\Backup\Notifications\Notifications\CleanupWasSuccessfulNotification::class => ['mail'],
    ],

    // Notifiable: The class that will receive notifications
    'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,

    'mail' => [
        'to' => 'wahidfkiri5@gmail.com',
    ],

    'slack' => [
        'webhook_url' => env('BACKUP_SLACK_WEBHOOK_URL'),
    ],
],
];