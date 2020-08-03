<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * TODO項目テーブル
 */
class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->comment('ユーザID');
            $table->text('subject')->comment('タイトル');
            $table->text('detail')->nullable()->comment('詳細');
            // $table->integer('category_id')->comment('カテゴリーID');
            $table->dateTime('limit_at')->nullable()->comment('期限');
            $table->dateTime('complete_at')->nullable()->comment('完了日時');
            $table->softDeletes();
            $table->dateTime('created_at')->nullable()->comment('作成日時');
            $table->dateTime('updated_at')->nullable()->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
