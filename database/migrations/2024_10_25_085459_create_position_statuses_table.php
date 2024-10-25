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
        Schema::create('position_statuses', function (Blueprint $table) {
            $table->comment('职位状态表');
            $table->id();
            $table->string('name', 50)->comment('状态名称');
            $table->string('description', 255)->nullable()->comment('状态描述');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('position_statuses');
    }
};
