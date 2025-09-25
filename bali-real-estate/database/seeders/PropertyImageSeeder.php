<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PropertyImageSeeder extends Seeder
{
    public function run(): void
    {
        // 物件IDを取得（名前で紐付け）
        $props = DB::table('properties')->pluck('id','title');

        $rows = [
            ['prop' => 'Modern Villa with Pool',       'url' => 'https://picsum.photos/id/1018/1200/800', 'cover' => true,  'order' => 0],
            ['prop' => 'Modern Villa with Pool',       'url' => 'https://picsum.photos/id/1015/1200/800', 'cover' => false, 'order' => 1],
            ['prop' => 'Uluwatu Cliff Villa',          'url' => 'https://picsum.photos/id/1025/1200/800', 'cover' => true,  'order' => 0],
            ['prop' => 'Uluwatu Cliff Villa',          'url' => 'https://picsum.photos/id/1035/1200/800', 'cover' => false, 'order' => 1],
            ['prop' => 'Seminyak Cozy House',          'url' => 'https://picsum.photos/id/1040/1200/800', 'cover' => true,  'order' => 0],
            ['prop' => 'Ubud Rice Field Retreat',      'url' => 'https://picsum.photos/id/1041/1200/800', 'cover' => true,  'order' => 0],
            ['prop' => 'Canggu Surf Lodge',            'url' => 'https://picsum.photos/id/1042/1200/800', 'cover' => true,  'order' => 0],
            ['prop' => 'Sanur Beachfront Apartment',   'url' => 'https://picsum.photos/id/1043/1200/800', 'cover' => true,  'order' => 0],
            ['prop' => 'Jimbaran Bay Luxury Estate',   'url' => 'https://picsum.photos/id/1044/1200/800', 'cover' => true,  'order' => 0],
        ];

        foreach ($rows as $r) {
            $pid = $props[$r['prop']] ?? null;
            if (!$pid) continue;
            DB::table('property_images')->insert([
                'property_id' => $pid,
                'url'         => $r['url'],
                'caption'     => null,
                'sort_order'  => $r['order'],
                'is_cover'    => $r['cover'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }
    }
}