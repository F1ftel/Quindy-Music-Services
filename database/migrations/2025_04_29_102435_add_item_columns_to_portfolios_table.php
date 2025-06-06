<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->string('item_type');
        });
    }
    
    public function down()
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['item_id', 'item_type']);
        });
    }
    
};
