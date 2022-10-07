<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('email')->unique();
            $table->string('phone')->unique();
            $table->string('password');
            $table->string('name');
            $table->string('image_address')->nullable();
            $table->string('role')->default('user');
            $table->boolean('status')->default(false);
            $table->timestamp('email_verified_at')->nullable();
            // $table->boolean('confirm')->default(false);
            // $table->string('confirmation_code')->nullable();
            // $table->dateTime('confirmation_code_expired_in')->nullable();
            $table->rememberToken();
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
