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
        Schema::create('classroom_user_payment', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('new_credit')->default(0);
            $table->timestamps();

            $table->foreignId('classroom_user_id')->constrained('classroom_user')->onDelete('cascade');
            $table->foreignId('payment_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('classroom_user_payment');
    }
};
