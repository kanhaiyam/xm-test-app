<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('symbols', function (Blueprint $table) {
            $table->uuid('id');
            $table->text('c_name');
            $table->text('s_name');
            $table->string('symbol', 6)->index();
            $table->string('f_status', 1);
            $table->string('m_category', 1);
            $table->float('lot_size', 8, 2);
            $table->enum('is_test', ['Y', 'N']);
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symbols');
    }
};
