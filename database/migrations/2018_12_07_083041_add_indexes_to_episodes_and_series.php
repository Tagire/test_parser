<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToEpisodesAndSeries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('episodes', function(Blueprint $table) {
            $table->index(['series_id'], 'series_id');
            $table->index(['release_date_ru'], 'release_date_ru');

            $table->foreign('series_id')
            ->references('id')->on('series')
            ->onDelete('cascade');
        });
        Schema::table('series', function(Blueprint $table) {
            $table->index(['name'], 'name');
        });

        DB::statement('ALTER TABLE episodes ADD FULLTEXT episodes_fulltext_index (name_ru, name_en)');
        DB::statement('ALTER TABLE series ADD FULLTEXT series_fulltext_index (name)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('episodes', function(Blueprint $table) {
            $table->dropForeign(['series_id']);

            $table->dropIndex('series_id');
            $table->dropIndex('release_date_ru');
        });
        Schema::table('series', function(Blueprint $table) {
            $table->dropIndex('name');
        });

        DB::statement('ALTER TABLE episodes DROP INDEX episodes_fulltext_index');
        DB::statement('ALTER TABLE series DROP INDEX series_fulltext_index');
    }
}
