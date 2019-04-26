<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Crazymeeks\Foundation\PaymentGateway\Dragonpay;

class DragonPayController extends Controller
{
    public function postCheckout()
    {
        $parameters = [
            'txnid'       => 'APDP-0000004',
            'amount'      => 1000,
            'ccy'         => 'PHP',
            'description' => 'Test',
            'email'       => 'denneth.dacara@webuffsolutions.com',
            'param1'      => 'http://webuffsolutions.com',
            'param2'      => 'http://academeportal.com',
        ];

        $merchant_account = [
            'merchantid' => 'WEBUFF',
            'password'   => 'R21X4QiYUAB9DHW'
        ];

        $dragonpay = new Dragonpay($merchant_account);

        try {
            $dragonpay->setParameters($parameters)->away();
        } catch(PaymentException $e){
           echo $e->getMessage(); exit;
        } catch(\Exception $e){
           echo $e->getMessage();
        }

    }

    public function transactionStatusInquiry()
    {
        $merchant_account = [
            'merchantid' => 'WEBUFF',
            'password'   => 'R21X4QiYUAB9DHW'
        ];

        $txnid = 'APDP-0000001';
        $dragonpay = new Dragonpay($merchant_account);

        try {
            $status = $dragonpay->action(new \Crazymeeks\Foundation\PaymentGateway\Dragonpay\Action\CheckTransactionStatus($txnid));
        } catch(PaymentException $e){
           echo $e->getMessage(); exit;
        } catch(\Exception $e){
           echo $e->getMessage();
        }

    }

    public function retrieveAllAvailablePaymentChannels()
    {
        $merchant_account = [
            'merchantid' => 'WEBUFF',
            'password'   => 'R21X4QiYUAB9DHW'
        ];

        $dragonpay = new Dragonpay($merchant_account);
        $amount = Dragonpay::ALL_PROCESSORS;
        $processors = $dragonpay->getPaymentChannels($amount);
    }
}
