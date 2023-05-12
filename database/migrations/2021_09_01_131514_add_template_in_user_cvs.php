<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTemplateInUserCvs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_cvs', function (Blueprint $table) {
            $table->tinyInteger('template')->default(1)->comment('1 - template 1, 2 - template 2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_cvs', function (Blueprint $table) {
            $table->dropColumn('template');
        });
    }
}