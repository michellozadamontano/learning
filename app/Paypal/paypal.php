<?php

namespace App\Paypal;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

class Paypal {

    protected $apiContext;

    public function __construct()
    {
      /*  $paypal_conf = \Config::get('paypal');
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $paypal_conf['client_id'],
                $paypal_conf['secret']
            )
        );*/
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('services.paypal.id'),
                config('services.paypal.secret')
            )
        );
        $this->apiContext->setConfig(config('services.paypal.settings'));
    }

    protected function details(): Details {
        $details = new Details();

        $details->setShipping(1.2)->setTax(1.3)->setSubtotal(17.50);

        return $details;

    }


}
