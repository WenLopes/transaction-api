<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $defaultTransactionStatus = config('constants.transaction.status.WAITING');

        Schema::create('transactions', function (Blueprint $table) use ($defaultTransactionStatus){
            $table->id();
            $table->unsignedBigInteger('payee_id')->index('FK_Transactions_PayeeId_Users_Idx');
            $table->unsignedBigInteger('payer_id')->index('FK_Transactions_PayerId_Users_Idx');
            $table->unsignedDecimal('value', 18, 2)->default(0);
            $table->integer('transaction_status_id')->default($defaultTransactionStatus)->index('FK_Transactions_TransactionStatusId_TransactionStatus_Idx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
