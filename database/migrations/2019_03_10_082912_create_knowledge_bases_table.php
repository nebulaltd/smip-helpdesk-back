<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnowledgeBasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knowledge_bases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('department_id');
            $table->string('title');
            $table->text('content');
            $table->boolean('pinned')->default(0)->comment('pinned =>1, nonpinned => 0');
            $table->integer('view_count')->default(0);
            $table->integer('user_id');
            $table->integer('status')->default(0)->comment('0 => draft, 1=> published');
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
        Schema::dropIfExists('knowledge_bases');
    }
}
