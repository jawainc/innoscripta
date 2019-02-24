<?php

namespace Tests\Unit;

use App\Company;
use App\Invoice;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_can_list_invoices() {
        $invoices = factory(Company::class, 2)->create()->each(function ($company) {
            $company->invoices()->save(factory(Invoice::class)->make());
        });
        $this->get('/api/search')
            ->assertStatus(200);

    }
}
