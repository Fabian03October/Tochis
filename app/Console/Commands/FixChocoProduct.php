<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;

class FixChocoProduct extends Command
{
    protected $signature = 'fix:choco';
    protected $description = 'Fix choco crispi product';

    public function handle()
    {
        $product = Product::where('name', 'choco crispi')->first();
        
        if ($product) {
            $product->update([
                'is_food' => true,
                'stock' => 50
            ]);
            $this->info('Platillo choco crispi actualizado como comida con stock 50');
        } else {
            $this->error('Platillo choco crispi no encontrado');
        }
    }
}
