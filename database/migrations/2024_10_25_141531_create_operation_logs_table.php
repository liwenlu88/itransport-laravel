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
        Schema::create('operation_logs', function (Blueprint $table) {
            $table->comment('操作日志表');
            $table->id();
            $table->string('account')->comment('用户账号');
            $table->string('path')->comment('请求路径');
            $table->string('method')->comment('请求方法');
            $table->string('operation')->comment('操作');
            $table->string('ip_address')->comment('IP');
            $table->string('user_agent')->comment('用户代理');
            $table->longText('request_data')->nullable()->comment('请求数据');
            $table->longText('response_data')->nullable()->comment('响应数据');
            $table->longText('original_data')->nullable()->comment('原始数据');
            $table->longText('new_data')->nullable()->comment('新数据');
            $table->bigInteger('status_code')->nullable()->comment('状态码');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operation_logs');
    }
};
