<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_service_offerings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subservice_id')->constrained('subservices')->onDelete('cascade');
            $table->decimal('price', 8, 2);
            $table->string('time_slot');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_service_offerings', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['subservice_id']);
        });

        Schema::dropIfExists('vendor_service_offerings');
    }
};
