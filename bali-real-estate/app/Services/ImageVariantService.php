<?php

namespace App\Services;

use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class ImageVariantService
{
    /** 画像パスを受け取り、/public 配下に派生画像を作る */
    public function makeVariants(string $relPath): array
    {
        // 入力: storage/app/public/... 相当の public 相対パス（例: images/props/1/original.jpg）
        $abs = storage_path('app/public/'.$relPath);
        if (!is_file($abs)) return [];

        $dir = dirname($relPath);
        $name = pathinfo($relPath, PATHINFO_FILENAME);

        // 出力ファイル名
        $targets = [
            'large'  => "$dir/{$name}_large.jpg",
            'medium' => "$dir/{$name}_medium.jpg",
            'thumb'  => "$dir/{$name}_thumb.jpg",
        ];

        // 生成（JPEG基準・必要なら品質調整）
        $img = Image::make($abs);

        // LARGE（横1280）
        $this->resizeSave($img, 1280, $targets['large'], 'jpg', 82);
        // MEDIUM（横960）
        $this->resizeSave($img, 960,  $targets['medium'], 'jpg', 80);
        // THUMB（横480）
        $this->resizeSave($img, 480,  $targets['thumb'],  'jpg', 78);

        // WebP / AVIF も同名で出力
        $this->toFormat($targets, 'webp', 78);
        $this->toFormat($targets, 'avif', 60);

        return $targets;
    }

    private function resizeSave($image, int $width, string $relOut, string $format, int $quality): void
    {
        $out = storage_path('app/public/'.$relOut);
        @mkdir(dirname($out), 0775, true);
        $im = clone $image;
        $im->resize($width, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        if ($format === 'jpg') {
            $im->save($out, $quality);
        } else {
            $im->encode($format, $quality)->save($out);
        }
    }

    private function toFormat(array $targets, string $ext, int $quality): void
    {
        foreach ($targets as $key => $relJpg) {
            $src = storage_path('app/public/'.$relJpg);
            if (!is_file($src)) continue;
            $out = preg_replace('/\.jpg$/', ".{$ext}", $src);
            try {
                $im = Image::make($src);
                $im->encode($ext, $quality)->save($out);
            } catch (\Throwable $e) {
                // Skip if format not supported
            }
        }
    }

    /** ビュー側で使う <picture> 用の候補URLを返す（public/storage ベース） */
    public function sourcesFor(string $relBase, string $size='large'): array
    {
        // $relBase: makeVariantsで作るキーの拡張子なしベースを想定しないため、_large.jpg 等の完全パスを渡す
        $public = fn($p)=>asset('storage/'.$p);
        $cands = [
            'avif' => preg_replace('/\.jpg$/', '.avif', $relBase),
            'webp' => preg_replace('/\.jpg$/', '.webp', $relBase),
            'jpg'  => $relBase,
        ];
        $out = [];
        foreach ($cands as $fmt=>$rel) {
            $p = storage_path('app/public/'.$rel);
            if (is_file($p)) $out[$fmt] = $public($rel);
        }
        return $out;
    }
}