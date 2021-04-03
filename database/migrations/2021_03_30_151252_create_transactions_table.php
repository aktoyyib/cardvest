<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                    ->nullable()
                    ->constrained()
                    ->onUpdate('cascade')
                    ->onDelete('set null');
            $table->enum('type', ['sell', 'buy', 'payout']);
            // Should be foreign, but I keep getting errors
            $table->unsignedInteger('card_id')->nullable();
            $table->string('reference');
            $table->string('bank_id')->nullable();
            $table->text('images')->nullable();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('unit');
            $table->unsignedBigInteger('balance')->nullable();
            $table->enum('status', ['rejected', 'pending', 'succeed'])->default('pending'); 
            $table->enum('payment_status', ['pending', 'succeed', 'failed'])->default('pending'); 
            $table->enum('role', ['funder', 'admin', 'user'])->default('user');
            $table->foreignId('recipient')
                    ->nullable()
                    ->constrained('users')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
            $table->text('comment')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}