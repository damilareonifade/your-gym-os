<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Rinvex\Country\CountryLoader;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = countries();

        foreach ($countries as $country) {
            $country = country(\strtolower($country["iso_3166_1_alpha2"]));
            Country::create([
                'id' => Str::ulid(),
                'name' => $country->getName(),
                'code' => $country->getIsoAlpha2(),
                'currency' => $country->getCurrency()['name'] ?? null,
                'currency_code' => $country->getCurrency()['iso_4217_code'] ?? null,
                'currency_symbol' => $country->getCurrency()['symbol'] ?? null,
                'phone_code' => $country->getCallingCode() ?? null,
                "language_code" => $country->getLanguages(),
                "default_language" => $country->getLanguage(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
