<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
require_once base_path('vendor/codeman/laravel-payu/src/PayU.php');
require_once base_path('vendor/codeman/laravel-payu/src/PayU/util/PayUParameters.php');
require_once base_path('vendor/codeman/laravel-payu/src/PayU/PayUSubscriptionPlans.php');
require_once base_path('vendor/codeman/laravel-payu/src/PayU/api/Environment.php');
//use App\Payu\lib\Payu;

class PayuController extends Controller
{
    
    private $apiKey;
    private $apiLogin;
    private $merchantId;   
    private $isTest;
    private $token;

    public function __construct(){

        $this->apiKey       = env('PAYU_API_KEY');
        $this->apiLogin     = env('PAYU_API_LOGIN');
        $this->merchantId   = env('PAYU_MERCHANT_ID');
        $this->token        = env('PAYU_TOKEN_ID');
        $this->isTest       = true;

        
      /*  \PayU::$apiKey       = env('PAYU_API_KEY');
        \PayU::$apiLogin     = env('PAYU_API_LOGIN');
        \PayU::$merchantId   = env('PAYU_MERCHANT_ID');
        \PayU::$isTest       = true;    */
        // URL de Pagos
  
    \Environment::setSubscriptionsCustomUrl("https://sandbox.api.payulatam.com/payments-api/rest/v4.9/");

    }
    public function payu_plan() {
        $parameters = array(
            // Ingresa aquí el número de cuotas a pagar.
            \PayUParameters::INSTALLMENTS_NUMBER => "1",
            // Ingresa aquí la cantidad de días de prueba
            \PayUParameters::TRIAL_DAYS => "3",
        
            // -- Parámetros del cliente --
            // Ingresa aquí el nombre del cliente
            \PayUParameters::CUSTOMER_NAME => "pepe el cojo",
            // Ingresa aquí el email del cliente
            \PayUParameters::CUSTOMER_EMAIL => "pepe@gmail.com",
        
            // -- Parámetros de la tarjeta de crédito --
            // Ingresa aquí el nombre del pagador.
            \PayUParameters::PAYER_NAME => "Sample User Name",
            // Ingresa aquí el número de la tarjeta de crédito
            \PayUParameters::CREDIT_CARD_NUMBER => "4242424242424242",
            // Ingresa aquí la fecha de expiración de la tarjeta de crédito en formato AAAA/MM
            \PayUParameters::CREDIT_CARD_EXPIRATION_DATE => "2019/12",
            // Ingresa aquí el nombre de la franquicia de la tarjeta de crédito
            \PayUParameters::PAYMENT_METHOD => "VISA",
                // Ingresa aquí el documento de identificación asociado a la tarjeta
                \PayUParameters::CREDIT_CARD_DOCUMENT => "1020304050",
            // (OPCIONAL) Ingresa aquí el documento de identificación del pagador
            \PayUParameters::PAYER_DNI => "1020304050",
            // (OPCIONAL) Ingresa aquí la primera línea de la dirección del pagador
            \PayUParameters::PAYER_STREET => "Address Name",
            // (OPCIONAL) Ingresa aquí la segunda línea de la dirección del pagador
            \PayUParameters::PAYER_STREET_2 => "17 25",
            // (OPCIONAL) Ingresa aquí la tercera línea de la dirección del pagador
            \PayUParameters::PAYER_STREET_3 => "Of 301",
            // (OPCIONAL) Ingresa aquí la ciudad de la dirección del pagador
            \PayUParameters::PAYER_CITY => "City Name",
            // (OPCIONAL) Ingresa aquí el estado o departamento de la dirección del pagador
            \PayUParameters::PAYER_STATE => "State Name",
            // (OPCIONAL) Ingresa aquí el código del país de la dirección del pagador
            \PayUParameters::PAYER_COUNTRY => "CO",
            // (OPCIONAL) Ingresa aquí el código postal de la dirección del pagador
            \PayUParameters::PAYER_POSTAL_CODE => "00000",
            // (OPCIONAL) Ingresa aquí el número telefónico del pagador
            \PayUParameters::PAYER_PHONE => "300300300",
        
            // -- Parámetros del plan --
            // Ingresa aquí la descripción del plan
            \PayUParameters::PLAN_DESCRIPTION => "Plan Mensual",
            // Ingresa aquí el código de identificación para el plan
            \PayUParameters::PLAN_CODE => "001",
            // Ingresa aquí el intervalo del plan
            \PayUParameters::PLAN_INTERVAL => "MONTH",
            // Ingresa aquí la cantidad de intervalos
            \PayUParameters::PLAN_INTERVAL_COUNT => "1",
            // Ingresa aquí la moneda para el plan
            \PayUParameters::PLAN_CURRENCY => "COP",
            // Ingresa aquí el valor del plan
            \PayUParameters::PLAN_VALUE => "90000",
            //(OPCIONAL) Ingresa aquí el valor del impuesto
            \PayUParameters::PLAN_TAX => "1600",
            //(OPCIONAL) Ingresa aquí la base de devolución sobre el impuesto
            \PayUParameters::PLAN_TAX_RETURN_BASE => "8400",
            // Ingresa aquí la cuenta Id del plan
            \PayUParameters::ACCOUNT_ID => "751661",
            // Ingresa aquí el intervalo de reintentos
            \PayUParameters::PLAN_ATTEMPTS_DELAY => "10",
            // Ingresa aquí la cantidad de cobros que componen el plan
            \PayUParameters::PLAN_MAX_PAYMENTS => "12",
            // Ingresa aquí la cantidad total de reintentos para cada pago rechazado de la suscripción
            \PayUParameters::PLAN_MAX_PAYMENT_ATTEMPTS => "3",
            // Ingresa aquí la cantidad máxima de pagos pendientes que puede tener una suscripción antes de ser cancelada.
            \PayUParameters::PLAN_MAX_PENDING_PAYMENTS => "1",
            // Ingresa aquí la cantidad de días de prueba de la suscripción.
            \PayUParameters::TRIAL_DAYS => "0",
        );
        
        $response = \PayUSubscriptions::createSubscription($parameters);
        dd($response);
        if($response){
            $response->id;
            $response->plan->id;
            $response->customer->id;
        }
    }
}
