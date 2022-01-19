<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ArticlesMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->increments('id');
            $table->string('category');
            $table->string('slug')->unique();
            $table->string('title')->default('');
            $table->longText('html');
            $table->boolean('published')->default(true);
            $table->boolean('isphp')->default('false');
            $table->boolean('noframe')->default('false');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
