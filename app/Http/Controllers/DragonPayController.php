<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay;
use Crazymeeks\Foundation\PaymentGateway\Options\Processor;
use App\DragonpayTransaction;

class DragonPayController extends Controller
{
    //composer require crazymeeks/dragonpay dev-master
    public function postCheckout()
    {
        $uid = uniqid();

        $parameters = [
            'txnid'       => $uid,
            'amount'      => 1000,
            'ccy'         => 'PHP',
            'description' => 'Test',
            'email'       => 'denneth.dacara@webuffsolutions.com',
            'param1'      => 'http://webuffsolutions.com',
            'param2'      => 'http://academeportal.com',
        ];

        $merchant_account = ['merchantid' => 'MERCHANT_ID', 'password' => 'MERCHANT_PASS'];
        $testing = true; # Set Payment mode to production(false) / testing(true)
        $dragonpay = new Dragonpay($merchant_account, $testing);
        // $dragonpay->filterPaymentChannel( Dragonpay::OTC_NON_BANK );

        // List of available payment channels
        // Dragonpay::ONLINE_BANK
        // Dragonpay::OTC_BANK
        // Dragonpay::OTC_NON_BANK
        // Dragonpay::PAYPAL
        // Dragonpay::GCASH
        // Dragonpay::INTL_OTC

        try {

            //handle postback and return url
            $dragonpay->handlePostback(function($data){
                DragonpayTransaction::create([
                    'txn_id'      => $data['txnid'],
                    'ref_no'      => $data['refno'],
                    'merchant'    => $merchant_account['merchantid'],
                    'amount'      => 0,
                    'ccy'         => 'PHP',
                    'status'      => $data['status'],
                    'description' => $data['description']
                ]);
            });

            $dragonpay->setParameters($parameters)
                //force user to use bayad center
                // ->withProcid(Processor::BAYADCENTER)
                ->away();

        } catch(PaymentException $e) {
            echo $e->getMessage(); exit;
        } catch(\Exception $e) {
            echo $e->getMessage();
        }

    }

    public function transactionStatusInquiry()
    {
        $merchant_account = ['merchantid' => 'MERCHANT_ID', 'password' => 'MERCHANT_PASS'];
        $txnid = '5cc283a273d17';
        $testing = true; # Set Payment mode to production(false) / testing(true)
        $dragonpay = new Dragonpay($merchant_account, $testing);

        try {
            return $status = $dragonpay->action(new \Crazymeeks\Foundation\PaymentGateway\Dragonpay\Action\CheckTransactionStatus($txnid));
        } catch(PaymentException $e){
            echo $e->getMessage(); exit;
        } catch(\Exception $e){
            echo $e->getMessage();
        }

    }

    public function retrieveAllAvailablePaymentChannels()
    {
        $merchant_account = ['merchantid' => 'MERCHANT_ID', 'password' => 'MERCHANT_PASS'];
        $testing = true; # Set Payment mode to production(false) / testing(true)
        $dragonpay = new Dragonpay($merchant_account, $testing);

        $amount = Dragonpay::ALL_PROCESSORS;
        return $processors = $dragonpay->getPaymentChannels($amount);
    }

    public function cancelTransaction()
    {
        $merchant_account = ['merchantid' => 'MERCHANT_ID', 'password' => 'MERCHANT_PASS'];
        $txnid = '5cc283a273d17';
        $testing = true; # Set Payment mode to production(false) / testing(true)
        $dragonpay = new Dragonpay($merchant_account, $testing);

        try {
            return $dragonpay->action(new \Crazymeeks\Foundation\PaymentGateway\Dragonpay\Action\CancelTransaction($txnid));
        } catch(\Crazymeeks\Foundation\Exceptions\Action\CancelTransactionException $e){
            // Error transaction cancellation
            return $e->getMessage();
        }

    }
}
