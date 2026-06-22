<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sell_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sell_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('package_id');
            $table->decimal('package_price', 10, 2)->default(0);
            $table->decimal('return_amount', 10, 2)->default(0);
            $table->string('return_time');
            $table->decimal('extra_benefit', 10, 2)->default(0);
            $table->decimal('share_count', 10, 2)->default(0);
            $table->decimal('booking_money', 10, 2)->default(0);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('sell_infos');
    }
};
