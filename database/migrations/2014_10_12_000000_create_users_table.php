<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            // Unique id populated from ldap repository.
            $table->string('objectguid')->nullable()->unique();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email');
            $table->string('first_name');
            $table->string('last_name');
            $table->rememberToken();
            // Audit and timestamps.
            $table->auditable();
            $table->softDeletes();
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
        Schema::dropIfExists('users');
    }
}