<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('pembelians', function (Blueprint $table) {
        $table->string('no_telp')->nullable();
        $table->boolean('is_member')->default(false);
        $table->integer('poin_didapat')->default(0);
        $table->integer('poin_dipakai')->default(0);
        $table->bigInteger('total_bayar')->default(0);
        $table->bigInteger('kembalian')->default(0);
    });
}

public function down()
{
    Schema::table('pembelians', function (Blueprint $table) {
        $table->dropColumn([
            'no_telp',
            'is_member',
            'poin_didapat',
            'poin_dipakai',
            'total_bayar',
            'kembalian'
        ]);
    });
}
};
