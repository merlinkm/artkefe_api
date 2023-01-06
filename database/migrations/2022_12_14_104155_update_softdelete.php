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
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('countries', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('states', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_details', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('artist_details', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('address', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('user_address', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('countries', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('states', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('user_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('artist_details', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('address', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('user_address', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

    }
};
