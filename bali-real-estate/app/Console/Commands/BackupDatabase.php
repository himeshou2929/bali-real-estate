<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BackupDatabase extends Command
{
    protected $signature = 'db:backup';
    protected $description = 'Backup the database and store to local storage';

    public function handle()
    {
        $filename = 'backup_'.date('Y_m_d_His').'.sql';
        $path = storage_path('app/'.$filename);
        $command = sprintf('mysqldump -u%s -p%s %s > %s',
            env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'), $path);
        system($command);
        $this->info('Backup created: '.$path);
    }
}