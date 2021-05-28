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
            $table->foreign(
                'transaction_status_id', 
                'FK_Transactions_TransactionStatusId_TransactionStatus_Id'
            )->references('id')->on('transaction_status')->onUpdate('NO ACTION')->onDelete('NO ACTION');

            $table->foreign(
                'payee_id', 
                'FK_Transactions_PayeeId_Users_Id'
            )->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            
            $table->foreign(
                'payer_id', 
                'FK_Transactions_PayerId_Users_Id'
            )->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('NO ACTION');
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
            $table->dropForeign('FK_Transactions_TransactionStatusId_TransactionStatus_Id');
            $table->dropForeign('FK_Transactions_PayeeId_Users_Id');
            $table->dropForeign('FK_Transactions_PayerId_Users_Id');
        });
    }
}
