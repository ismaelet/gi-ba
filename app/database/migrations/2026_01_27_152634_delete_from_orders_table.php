<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        // Delete orders that don't have any order_items
        DB::table('orders')
            ->whereNotIn('id', function ($query) {
                $query->select('order_id')->from('order_items');
            })
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
    }
};
