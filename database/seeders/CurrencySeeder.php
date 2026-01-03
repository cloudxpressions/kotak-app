<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        // Base conversion system: USD = 1
        $currencies = [

            // Active currencies
            ['name' => 'US Dollar',      'code' => 'USD', 'symbol' => '$',  'conversion_rate' => 1,    'symbol_position' => 'before', 'decimal_places' => 2, 'is_default' => 1, 'is_active' => 1],
            ['name' => 'Indian Rupee',   'code' => 'INR', 'symbol' => '₹',  'conversion_rate' => 83.0, 'symbol_position' => 'before', 'decimal_places' => 2, 'is_default' => 0, 'is_active' => 1],

            // Inactive currencies — but available for selection
            ['name' => 'Euro',                 'code' => 'EUR', 'symbol' => '€'],
            ['name' => 'British Pound',        'code' => 'GBP', 'symbol' => '£'],
            ['name' => 'Australian Dollar',    'code' => 'AUD', 'symbol' => 'A$'],
            ['name' => 'Canadian Dollar',      'code' => 'CAD', 'symbol' => 'C$'],
            ['name' => 'Singapore Dollar',     'code' => 'SGD', 'symbol' => 'S$'],
            ['name' => 'Swiss Franc',          'code' => 'CHF', 'symbol' => 'CHF'],
            ['name' => 'Japanese Yen',         'code' => 'JPY', 'symbol' => '¥'],
            ['name' => 'Chinese Yuan',         'code' => 'CNY', 'symbol' => '¥'],
            ['name' => 'Hong Kong Dollar',     'code' => 'HKD', 'symbol' => 'HK$'],
            ['name' => 'UAE Dirham',           'code' => 'AED', 'symbol' => 'د.إ'],
            ['name' => 'Saudi Riyal',          'code' => 'SAR', 'symbol' => '﷼'],
            ['name' => 'Qatari Riyal',         'code' => 'QAR', 'symbol' => '﷼'],
            ['name' => 'Kuwaiti Dinar',        'code' => 'KWD', 'symbol' => 'KD'],
            ['name' => 'Bahraini Dinar',       'code' => 'BHD', 'symbol' => '.د.ب'],
            ['name' => 'Omani Rial',           'code' => 'OMR', 'symbol' => 'ر.ع.'],
            ['name' => 'Bangladeshi Taka',     'code' => 'BDT', 'symbol' => '৳'],
            ['name' => 'Pakistani Rupee',      'code' => 'PKR', 'symbol' => '₨'],
            ['name' => 'Sri Lankan Rupee',     'code' => 'LKR', 'symbol' => 'Rs'],
            ['name' => 'Nepalese Rupee',       'code' => 'NPR', 'symbol' => 'Rs'],
            ['name' => 'South African Rand',   'code' => 'ZAR', 'symbol' => 'R'],
            ['name' => 'New Zealand Dollar',   'code' => 'NZD', 'symbol' => 'NZ$'],
            ['name' => 'Turkish Lira',         'code' => 'TRY', 'symbol' => '₺'],
            ['name' => 'Russian Ruble',        'code' => 'RUB', 'symbol' => '₽'],
            ['name' => 'Brazilian Real',       'code' => 'BRL', 'symbol' => 'R$'],
            ['name' => 'Mexican Peso',         'code' => 'MXN', 'symbol' => 'MX$'],
            ['name' => 'Philippine Peso',      'code' => 'PHP', 'symbol' => '₱'],
            ['name' => 'Indonesian Rupiah',    'code' => 'IDR', 'symbol' => 'Rp'],
            ['name' => 'Malaysian Ringgit',    'code' => 'MYR', 'symbol' => 'RM'],
            ['name' => 'Thai Baht',            'code' => 'THB', 'symbol' => '฿'],
            ['name' => 'Vietnamese Dong',      'code' => 'VND', 'symbol' => '₫'],
            ['name' => 'South Korean Won',     'code' => 'KRW', 'symbol' => '₩'],
        ];

        $insert = [];

        foreach ($currencies as $c) {
            $insert[] = [
                'name'              => $c['name'],
                'code'              => $c['code'],
                'symbol'            => $c['symbol'],
                'conversion_rate'   => $c['conversion_rate'] ?? 1,
                'symbol_position'   => $c['symbol_position'] ?? 'before',
                'decimal_places'    => $c['decimal_places'] ?? 2,
                'is_default'        => $c['is_default'] ?? 0,
                'is_active'         => $c['is_active'] ?? 0,    // Only INR + USD active
                'created_at'        => now(),
                'updated_at'        => now(),
            ];
        }

        DB::table('currencies')->insert($insert);
    }
}
