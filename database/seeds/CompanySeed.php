<?php

use Illuminate\Database\Seeder;

class CompanySeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Company::class, 50)->create()->each(function ($company) {
            $company->invoices()->save(factory(App\Invoice::class)->make());
        });
    }
}
