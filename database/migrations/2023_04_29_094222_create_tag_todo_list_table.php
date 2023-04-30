<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagTodoListTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tag_todo_list', function (Blueprint $table) {
			$table->id()->comment('ID');
			$table->bigInteger('todo_list_id')->unsigned()->index()->comment('ID списка дел');
			$table->bigInteger('tag_id')->unsigned()->index()->comment('ID тега');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tag_todo_list');
    }
}
