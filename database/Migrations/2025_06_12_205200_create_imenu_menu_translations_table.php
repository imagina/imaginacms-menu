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
        Schema::create('imenu__menu_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->tinyInteger('status')->default(0);
            $table->string('title')->nullable();

            $table->integer('menu_id')->unsigned();
            $table->string('locale')->index();
            $table->unique(['menu_id', 'locale']);
            $table->foreign('menu_id')->references('id')->on('imenu__menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imenu__menu_translations', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
        });
        Schema::dropIfExists('imenu__menu_translations');
    }
};
