<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->string('package_name');
            $table->string('package_no', 50)->unique();
            $table->decimal('package_price', 10, 2)->default(0);
            $table->decimal('return_amount', 10, 2)->default(0);
            $table->string('return_time');
            $table->decimal('extra_benefit', 10, 2)->default(0);
            $table->decimal('share_count', 10, 2)->default(0);
            $table->longText('description')->nullable();
            $table->tinyInteger('status')->default('1')->comment('0 = inactive,1 = active');
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
