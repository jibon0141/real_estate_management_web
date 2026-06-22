<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('share_in_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('package_id');
            $table->decimal('stock',15,2);
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('share_in_stocks');
    }
};
