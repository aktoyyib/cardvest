<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_log_queries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('activity_log_id');
            $table->text('transaction_reference')->nullable() ;
            $table->string('admin_id')->nullable();
            $table->string('comment')->nullable();
            $table->enum('admin_type', ['admin', 'superadmin'])->default('superadmin'); 
            $table->enum('status', ['open', 'closed'])->default('open'); 
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
        Schema::dropIfExists('activity_log_queries');
    }
}
