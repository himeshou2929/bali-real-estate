<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageVariantService;
use Illuminate\Support\Facades\Storage;

class MakeImageVariants extends Command
{
    protected $signature = 'images:variants {--dir=images}';
    protected $description = 'Generate size/webp/avif variants under storage/app/public/{dir}';

    public function handle(ImageVariantService $svc): int
    {
        $dir = trim($this->option('dir'), '/');
        $files = Storage::disk('public')->allFiles($dir);
        $count = 0;
        foreach ($files as $f) {
            if (!preg_match('/\.(jpe?g|png)$/i', $f)) continue;
            $svc->makeVariants($f);
            $this->line("ok: $f");
            $count++;
        }
        $this->info("Done. processed={$count}");
        return self::SUCCESS;
    }
}
