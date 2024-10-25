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
        Schema::create('users', function (Blueprint $table) {
            $table->comment('用户表');
            $table->id();
            $table->string('name')->comment('用户名');
            $table->string('contact_tel', 20)->comment('联系电话');
            $table->string('account', 20)->comment('账号');
            $table->string('password')->comment('密码');
            $table->rememberToken();
            $table->unsignedBigInteger('position_status_id')->nullable()->comment('职位状态');
            $table->string('description')->nullable()->comment('描述');
            $table->bigInteger('car_id')->nullable()->comment('车辆ID');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
