<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInventoryFieldsToItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('quantity')->nullable();
            $table->foreignId('income_account_id')->nullable()->constrained('accounts');
            $table->foreignId('expense_account_id')->nullable()->constrained('accounts');
            $table->foreignId('unit_id')->nullable()->constrained('units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {

        });
    }
}
