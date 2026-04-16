<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCardMobileDetailsToPenjualanTable extends Migration
{
    public function up()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->string('card_type')->nullable()->after('mobile_money_provider');        // Visa, Mastercard, etc.
            $table->string('card_last_four')->nullable()->after('card_type');               // Last 4 digits
            $table->string('payment_reference')->nullable()->after('card_last_four');       // Transaction ref / approval code
            $table->string('mobile_phone')->nullable()->after('payment_reference');         // Phone number for MoMo
        });
    }

    public function down()
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['card_type', 'card_last_four', 'payment_reference', 'mobile_phone']);
        });
    }
}
