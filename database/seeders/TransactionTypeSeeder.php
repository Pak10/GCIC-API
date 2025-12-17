<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transactions\TransactionType;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $deposit = TransactionType::create([

            'transaction_type' => 'Deposit',
            'booking' => 'credit',
            'affect_investments' => true,
    
        ]);

        $withdrawal = TransactionType::create([

            'transaction_type' => 'Withdrawal',
            'booking' => 'debit',
            'affect_investments' => true,
    
        ]);
    }
}
