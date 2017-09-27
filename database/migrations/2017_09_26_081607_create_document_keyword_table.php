<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentKeywordTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_keyword', function (Blueprint $table) {
            $table->integer('document_id')->unsigned();
            $table->foreign('document_id')
                ->references('id')->on('documents')
                ->onDelete('cascade')->onUpdate('cascade');
            
            $table->integer('keyword_id')->unsigned();
            $table->foreign('keyword_id')
                ->references('id')->on('keywords')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_keyword');
    }
}
