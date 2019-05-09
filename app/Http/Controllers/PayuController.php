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
    private $accountId;   
    private $isTest;
    private $token;

    public function __construct(){

        $this->apiKey       = env('PAYU_API_KEY');
        $this->apiLogin     = env('PAYU_API_LOGIN');
        $this->merchantId   = env('PAYU_MERCHANT_ID');
        $this->accountId    = env('PAYU_ACCOUNT_ID');
        $this->token        = env('PAYU_TOKEN_ID');
        $this->isTest       = true;

        
        \PayU::$apiKey       = env('PAYU_API_KEY');
        \PayU::$apiLogin     = env('PAYU_API_LOGIN');
        \PayU::$merchantId   = env('PAYU_MERCHANT_ID');
        \PayU::$isTest       = true;    
        // URL de Pagos  
      //  \Environment::setPaymentsCustomUrl("https://api.payulatam.com/payments-api/4.0/service.cgi") ;
    //\Environment::setPaymentsCustomUrl("https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi");
  //  \Environment::setReportsCustomUrl("https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi"); 
   // \Environment::setSubscriptionsCustomUrl("https://sandbox.api.payulatam.com/payments-api/rest/v4.9");

    // URL de Pagos
    \Environment::setPaymentsCustomUrl("https://api.payulatam.com/payments-api/4.0/service.cgi");
    \Environment::setReportsCustomUrl("https://api.payulatam.com/reports-api/4.0/service.cgi"); 
    \Environment::setSubscriptionsCustomUrl("https://api.payulatam.com/payments-api/rest/v4.9/");

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
            \PayUParameters::PAYER_NAME => "APPROVED",
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
            \PayUParameters::PLAN_CODE => "0011",
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
            \PayUParameters::ACCOUNT_ID => $this->accountId,//"512321",
            // Ingresa aquí el intervalo de reintentos
            \PayUParameters::PLAN_ATTEMPTS_DELAY => "10",
            // Ingresa aquí la cantidad de cobros que componen el plan
            \PayUParameters::PLAN_MAX_PAYMENTS => "12",
            // Ingresa aquí la cantidad total de reintentos para cada pago rechazado de la suscripción
            \PayUParameters::PLAN_MAX_PAYMENT_ATTEMPTS => "2",
            // Ingresa aquí la cantidad máxima de pagos pendientes que puede tener una suscripción antes de ser cancelada.
            \PayUParameters::PLAN_MAX_PENDING_PAYMENTS => "1",
            // Ingresa aquí la cantidad de días de prueba de la suscripción.
            \PayUParameters::TRIAL_DAYS => "0",
        );
       /* $parameters = array(
            // Ingresa aquí la descripción del plan
            \PayUParameters::PLAN_DESCRIPTION => "Sample Plan 001",
            // Ingresa aquí el código de identificación para el plan
            \PayUParameters::PLAN_CODE => "sample-plan-841651",
            // Ingresa aquí el intervalo del plan
            //DAY||WEEK||MONTH||YEAR
            \PayUParameters::PLAN_INTERVAL => "MONTH",
            // Ingresa aquí la cantidad de intervalos
            \PayUParameters::PLAN_INTERVAL_COUNT => "1",
            // Ingresa aquí la moneda para el plan
            \PayUParameters::PLAN_CURRENCY => "COP",
            // Ingresa aquí el valor del plan
            \PayUParameters::PLAN_VALUE => "10000",
            //(OPCIONAL) Ingresa aquí el valor del impuesto
            \PayUParameters::PLAN_TAX => "0",
            //(OPCIONAL) Ingresa aquí la base de devolución sobre el impuesto
            \PayUParameters::PLAN_TAX_RETURN_BASE => "0",
            // Ingresa aquí la cuenta Id del plan
            \PayUParameters::ACCOUNT_ID => $this->accountId,
            // Ingresa aquí el intervalo de reintentos
            \PayUParameters::PLAN_ATTEMPTS_DELAY => "1",
            // Ingresa aquí la cantidad de cobros que componen el plan
            \PayUParameters::PLAN_MAX_PAYMENTS => "12",
            // Ingresa aquí la cantidad total de reintentos para cada pago rechazado de la suscripción
            \PayUParameters::PLAN_MAX_PAYMENT_ATTEMPTS => "3",
            // Ingresa aquí la cantidad máxima de pagos pendientes que puede tener una suscripción antes de ser cancelada.
            \PayUParameters::PLAN_MAX_PENDING_PAYMENTS => "1",
        );

        $response = \PayUSubscriptionPlans::create($parameters);
        if ($response) {
            $response->id;
        }
        print_r($response);*/
        
        $response = \PayUSubscriptions::createSubscription($parameters);
        dd($response);
        if($response){
            $response->id;
            $response->plan->id;
            $response->customer->id;
        }
    }
    public function payucheckout(Request $request) { 
             
        $value = $request['valor'];
        $reference = time();
        $fecha_exp = $request['year'].'/'.$request['month'];
        $ip = getenv('HTTP_CLIENT_IP')?:
        getenv('HTTP_X_FORWARDED_FOR')?:
        getenv('HTTP_X_FORWARDED')?:
        getenv('HTTP_FORWARDED_FOR')?:
        getenv('HTTP_FORWARDED')?:
        getenv('REMOTE_ADDR');
        

        $parameters = array(
            //Ingrese aquí el identificador de la cuenta.
            \PayUParameters::ACCOUNT_ID => $this->accountId,
            //Ingrese aquí el código de referencia.
            \PayUParameters::REFERENCE_CODE => $reference,
            //Ingrese aquí la descripción.
            \PayUParameters::DESCRIPTION => "pago de cursos",

                // -- Valores --
                //Ingrese aquí el valor de la transacción.
                \PayUParameters::VALUE => $value,
                //Ingrese aquí el valor del IVA (Impuesto al Valor Agregado solo valido para Colombia) de la transacción,
                //si se envía el IVA nulo el sistema aplicará el 19% automáticamente. Puede contener dos dígitos decimales.
                //Ej: 19000.00. En caso de no tener IVA debe enviarse en 0.
                \PayUParameters::TAX_VALUE => "0",
                //Ingrese aquí el valor base sobre el cual se calcula el IVA (solo valido para Colombia).
                //En caso de que no tenga IVA debe enviarse en 0.
                \PayUParameters::TAX_RETURN_BASE => "0",
            //Ingrese aquí la moneda.
            \PayUParameters::CURRENCY => "COP",

            // -- Comprador
            //Ingrese aquí el nombre del comprador.
            \PayUParameters::BUYER_NAME => $request['clientName'],
            //Ingrese aquí el email del comprador.
            \PayUParameters::BUYER_EMAIL => $request['email'],
            //Ingrese aquí el teléfono de contacto del comprador.
            \PayUParameters::BUYER_CONTACT_PHONE => $request['phone'],
            //Ingrese aquí el documento de contacto del comprador.
            \PayUParameters::BUYER_DNI => $request['dni'],
            //Ingrese aquí la dirección del comprador.
            \PayUParameters::BUYER_STREET => "calle 100",
            \PayUParameters::BUYER_STREET_2 => "5555487",
            \PayUParameters::BUYER_CITY => "Medellin",
            \PayUParameters::BUYER_STATE => "Antioquia",
            \PayUParameters::BUYER_COUNTRY => "CO",
            \PayUParameters::BUYER_POSTAL_CODE => "000000",
            \PayUParameters::BUYER_PHONE => $request['phone'],

            // -- pagador --
            //Ingrese aquí el nombre del pagador.
            \PayUParameters::PAYER_NAME => $request['clientName'],
            //Ingrese aquí el email del pagador.
            \PayUParameters::PAYER_EMAIL => $request['email'],
            //Ingrese aquí el teléfono de contacto del pagador.
            \PayUParameters::PAYER_CONTACT_PHONE => $request['phone'],
            //Ingrese aquí el documento de contacto del pagador.
            \PayUParameters::PAYER_DNI => $request['dni'],            //Ingrese aquí la dirección del pagador.
            \PayUParameters::PAYER_STREET => "calle 93",
            \PayUParameters::PAYER_STREET_2 => "125544",
            \PayUParameters::PAYER_CITY => "Bogota",
            \PayUParameters::PAYER_STATE => "Bogota",
            \PayUParameters::PAYER_COUNTRY => "CO",
            \PayUParameters::PAYER_POSTAL_CODE => "000000",
            \PayUParameters::PAYER_PHONE => $request['phone'],

            // -- Datos de la tarjeta de crédito --
            //Ingrese aquí el número de la tarjeta de crédito
            \PayUParameters::CREDIT_CARD_NUMBER => $request['credit'],
            //Ingrese aquí la fecha de vencimiento de la tarjeta de crédito
            \PayUParameters::CREDIT_CARD_EXPIRATION_DATE => $fecha_exp,
            //Ingrese aquí el código de seguridad de la tarjeta de crédito
            \PayUParameters::CREDIT_CARD_SECURITY_CODE=> $request['code'],
            //Ingrese aquí el nombre de la tarjeta de crédito
            //VISA||MASTERCARD||AMEX||DINERS
            \PayUParameters::PAYMENT_METHOD => $request['tipo'],

            //Ingrese aquí el número de cuotas.
            \PayUParameters::INSTALLMENTS_NUMBER => "1",
            //Ingrese aquí el nombre del pais.
            \PayUParameters::COUNTRY => \PayUCountries::CO,

            //Session id del device.
            \PayUParameters::DEVICE_SESSION_ID => "vghs6tvkcle931686k1900o6e1",
            //IP del pagadador
            \PayUParameters::IP_ADDRESS => $ip,
            //Cookie de la sesión actual.
            \PayUParameters::PAYER_COOKIE=>"pt1t38347bs6jc9ruv2ecpv7o2",
            //Cookie de la sesión actual.
            \PayUParameters::USER_AGENT=>"Mozilla/5.0 (Windows NT 5.1; rv:18.0) Gecko/20100101 Firefox/18.0"
        );
        
        $response = \PayUPayments::doAuthorizationAndCapture($parameters);        

        if ($response) {
            dd($response,'entre aqui');
            $response->transactionResponse->orderId;
            $response->transactionResponse->transactionId;
            $response->transactionResponse->state;
            if ($response->transactionResponse->state=="PENDING") {
                $response->transactionResponse->pendingReason;
            }
            $response->transactionResponse->paymentNetworkResponseCode;
            $response->transactionResponse->paymentNetworkResponseErrorMessage;
            $response->transactionResponse->trazabilityCode;
            $response->transactionResponse->responseCode;
            $response->transactionResponse->responseMessage;
        }
    }

    public function api_payu_checkout() {       
        $client = new \GuzzleHttp\Client();
       // dd($client);
        $response = $client->request('POST', 'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi', [
            'json' => [
                "language"=> "es",
                "command"=> "SUBMIT_TRANSACTION",
                "merchant"=> [
                    "apiLogin"=> "pRRXKOl8ikMmt9u",
                    "apiKey"=> "4Vj8eK4rloUd272L48hsrarnUA"
                ],
                "transaction"=> [
                    "order"=> [
                        "accountId"=> "512326",
                        "referenceCode"=> "michelito",
                        "description"=> "Test order Panama",
                        "language"=> "en",
                        "notifyUrl"=> "http://pruebaslap.xtrweb.com/lap/pruebconf.php",
                        "signature"=> "a2de78b35599986d28e9cd8d9048c45d",
                        "shippingAddress"=> [
                            "country"=> "PA"
                        ],
                        "buyer"=> [
                            "fullName"=> "APPROVED",
                            "emailAddress"=> "test@payulatam.com",
                            "dniNumber"=> "1155255887",
                            "shippingAddress"=> [
                            "street1"=> "Calle 93 B 17 – 25",
                            "city"=> "Panama",
                            "state"=> "Panama",
                            "country"=> "PA",
                            "postalCode"=> "000000",
                            "phone"=> "5582254"
                            ]
                        ],
                        "additionalValues"=> [
                            "TX_VALUE"=> [
                            "value"=> 5,
                            "currency"=> "USD"
                            ]
                        ]
                    ],
                    "creditCard"=> [
                        "number"=> "4111111111111111",
                        "securityCode"=> "123",
                        "expirationDate"=> "2019/08",
                        "name"=> "test"
                    ],
                    "type"=> "AUTHORIZATION_AND_CAPTURE",
                    "paymentMethod"=> "VISA",
                    "paymentCountry"=> "PA",
                    "payer"=> [
                        "fullName"=> "APPROVED",
                        "emailAddress"=> "test@payulatam.com"
                    ],
                    "ipAddress"=> "127.0.0.1",
                    "cookie"=> "cookie_52278879710130",
                    "userAgent"=> "Firefox",
                    "extraParameters"=> [
                        "INSTALLMENTS_NUMBER"=> 1,
                        "RESPONSE_URL"=> "http://learning.online/courses/payed"
                    ]
                ],
                "test"=> true
            ]
        ]);
        $response = $response->getBody()->getContents();
        echo '<pre>';
        print_r($response);
    }
}
