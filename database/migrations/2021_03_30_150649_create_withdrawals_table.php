<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('set null');
            $table->string('reference');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('balance');
            $table->enum('status', ['closed', 'pending', 'succeed'])->default('pending');
            $table->enum('payment_status', ['pending', 'succeed', 'failed'])->default('pending'); 
            $table->text('admin_comment')->nullable();
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
        Schema::dropIfExists('withdrawals');
    }
}