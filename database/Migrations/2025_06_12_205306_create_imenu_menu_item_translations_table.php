<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imenu__menu_item_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->tinyInteger('status')->default(0);
            $table->string('title')->nullable();
            $table->string('url')->nullable();
            $table->string('uri')->nullable();
            $table->string('description')->nullable();

            $table->integer('menu_item_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['menu_item_id', 'locale']);
            $table->foreign('menu_item_id')->references('id')->on('imenu__menu_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imenu__menu_item_translations', function (Blueprint $table) {
            $table->dropForeign(['menu_item_id']);
        });
        Schema::dropIfExists('imenu__menu_item_translations');
    }
};
