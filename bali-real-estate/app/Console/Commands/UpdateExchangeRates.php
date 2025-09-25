<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ExchangeRate;
use Illuminate\Support\Facades\Http;

class UpdateExchangeRates extends Command
{
    protected $signature = 'rates:update';
    protected $description = 'Fetch and update exchange rates (JPY, USD, IDR)';

    public function handle(): int
    {
        try {
            // 無料API例: exchangerate.host
            $base = 'USD';
            $symbols = 'JPY,IDR,USD';
            $res = Http::get("https://api.exchangerate.host/latest",[
                'base'=>$base,'symbols'=>$symbols
            ]);
            if(!$res->ok()){ $this->error('API error'); return 1; }
            $data=$res->json();
            
            if(!isset($data['rates'])){ $this->error('No rates data'); return 1; }

            foreach($data['rates'] as $cur=>$rate){
                ExchangeRate::updateOrCreate(
                    ['base_currency'=>$base,'target_currency'=>$cur],
                    ['rate'=>$rate]
                );
            }
            $this->info('Rates updated');
            return 0;
        } catch(\Throwable $e){
            $this->error($e->getMessage());
            return 1;
        }
    }
}