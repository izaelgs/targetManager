<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_id')
                ->constrained();
            $table->unsignedBigInteger("stageid", false, true)
                ->nullable();
            $table->index("stageid");
            $table->foreign("stageid")
                ->references('id')
                ->on('stages');
            $table->string('title');
            $table->text('description');
            $table->date('deadline');
            $table->tinyInteger('tolerance');
            $table->tinyInteger('complexity');
            $table->boolean('status')->default(false);
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
        Schema::dropIfExists('stages');
    }
}
