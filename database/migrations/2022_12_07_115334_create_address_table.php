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
        Schema::create('address', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('phone_number',50);
            $table->string('street_address',100)->nullable();
            $table->longText('address_line_1')->nullable();
            $table->longText('address_line_2')->nullable();
            $table->string('city',20);
            $table->foreignId('state_id')->references('id')->on('states')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->foreignId('country_id')->references('id')->on('countries')->onUpdate('cascade')->onDelete('cascade')->nullable();
            $table->longText('postal_code');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->float('is_default', 1,0);
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
        Schema::dropIfExists('address');
    }
};
