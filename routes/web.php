<?php

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dragonpay')->group(function () {
    Route::get('post-checkout', 'DragonPayController@postCheckout')
        ->name('dragonpay.post_checkout');

    Route::get('transaction-status-inquiry', 'DragonPayController@transactionStatusInquiry')
        ->name('dragonpay.transaction_status_inquiry');

    Route::get('retrieve-available-payment-channels', 'DragonPayController@retrieveAllAvailablePaymentChannels')
        ->name('dragonpay.available_payment_channels');

    Route::get('cancel-transaction', 'DragonPayController@cancelTransaction')->name('dragonpay.cancel_transaction');

});
