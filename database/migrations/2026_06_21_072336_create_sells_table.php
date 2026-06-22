<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->string('sell_voucher');
            $table->date('sell_date');
            $table->string('sell_type')->comment('on_cash,on_installment');
            $table->decimal('sell_quantity', 10, 2)->default(0);
            $table->decimal('total_share_sell', 10, 2)->default(0);
            $table->decimal('total_amount',15,2);
            $table->decimal('paid_amount',15,2);
            $table->decimal('total_due_amount',15,2);
            $table->decimal('current_due_amount',15,2);
            $table->tinyInteger('take_return')->comment('0 = no,1 = yes')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
            $table->integer('installment_number')->default(0);
            $table->decimal('installment_amount', 15, 2)->default(0);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('sells');
    }
};
