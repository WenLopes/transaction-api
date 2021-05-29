<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->foreign('transaction_status_id', 'fk_transactions_transaction_status_id')
                ->references('id')
                ->on('transaction_status')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign( 'payee_id', 'fk_transactions_payee_id_users_id')
                ->references('id')
                ->on('users')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            
            $table->foreign( 'payer_id', 'fk_transactions_payer_id_users_id')
                ->references('id')
                ->on('users')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign('fk_transactions_transaction_status_id');
            $table->dropForeign('fk_transactions_payee_id_users_id');
            $table->dropForeign('fk_transactions_payer_id_users_id');
        });
    }
}
