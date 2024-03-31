<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        config(['database.default' => 'mysql-test']);

        DB::delete('delete from users');
        DB::delete('delete from item_stocks');
        DB::delete('delete from items');
        DB::delete('delete from item_change_histories');
        DB::delete('delete from stock_by_periods');
        DB::delete('delete from item_transactions');
        DB::delete('delete from receipts');
        DB::delete('delete from detailed_receipts');
        DB::delete('delete from internal_uses');
    }
}
