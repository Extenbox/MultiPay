<?php

use Extenbox\Multipay\Constants\IranCurrency;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | This value determines which of the following gateway to use.
    | You can switch to a different driver at runtime.
    |
    */
    'default' => 'zarinpal',

    /*
    |--------------------------------------------------------------------------
    | List of Drivers
    |--------------------------------------------------------------------------
    |
    | These are the list of drivers to use for this package.
    | You can change the name. Then you'll have to change
    | it in the map array too.
    |
    */
    'drivers' => [
        'bajet' => [
            'apiUrl' => 'https://jetpay.mybajet.ir',
            'username' => '',
            'password' => '',
            'terminalId' => '',
            'callbackUrl' => '',
            'currency' => IranCurrency::TOMAN,
            'apiCurrency' => IranCurrency::RIAL,
        ],
        'local' => [
            'callbackUrl' => '/callback',
            'title' => 'درگاه پرداخت تست',
            'description' => 'این درگاه *صرفا* برای تست صحت روند پرداخت و لغو پرداخت میباشد',
            'orderLabel' => 'شماره سفارش',
            'amountLabel' => 'مبلغ قابل پرداخت',
            'payButton' => 'پرداخت موفق',
            'cancelButton' => 'پرداخت ناموفق',
        ],
        'gooyapay' => [
            'apiPurchaseUrl' => 'https://gooyapay.ir/webservice/rest/PaymentRequest',
            'apiVerificationUrl' => 'https://gooyapay.ir/webservice/rest/PaymentVerification',
            'apiPaymentUrl' => 'https://gooyapay.ir/startPay/',
            'merchantId' => 'XXXX-XXXX-XXXX-XXXXXXXXXXXXXXXXXXXXX',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'currency' => IranCurrency::TOMAN,
        ],
        'fanavacard' => [
            'baseUri' => 'https://fcp.shaparak.ir',
            'apiPaymentUrl' => '_ipgw_//payment/',
            'apiPurchaseUrl' => 'ref-payment/RestServices/mts/generateTokenWithNoSign/',
            'apiVerificationUrl' => 'ref-payment/RestServices/mts/verifyMerchantTrans/',
            'apiReverseAmountUrl' => 'ref-payment/RestServices/mts/reverseMerchantTrans/',
            'username' => 'xxxxxxx',
            'password' => 'xxxxxxx',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'currency' => IranCurrency::TOMAN,
        ],
        'atipay' => [
            'atipayTokenUrl' => 'https://mipg.atipay.net/v1/get-token',
            'atipayRedirectGatewayUrl' => 'https://mipg.atipay.net/v1/redirect-to-gateway',
            'atipayVerifyUrl' => 'https://mipg.atipay.net/v1/verify-payment',
            'apikey' => '',
            'currency' => IranCurrency::RIAL,
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using Atipay',
        ],
        'asanpardakht' => [
            'apiPaymentUrl' => 'https://asan.shaparak.ir',
            'apiRestPaymentUrl' => 'https://ipgrest.asanpardakht.ir/v1/',
            'username' => '',
            'password' => '',
            'merchantConfigID' => '',
            'currency' => IranCurrency::TOMAN,
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using asanpardakht',
        ],
        'behpardakht' => [
            'apiPurchaseUrl' => 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl',
            'apiPaymentUrl' => 'https://bpm.shaparak.ir/pgwchannel/startpay.mellat',
            'apiVerificationUrl' => 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl',
            'terminalId' => '',
            'username' => '',
            'password' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using behpardakht',
            'currency' => IranCurrency::TOMAN,
            'cumulativeDynamicPayStatus' => false,
        ],
        'digipay' => [
            'apiPaymentUrl' => 'https://api.mydigipay.com', // with out '/' at the end
            'username' => 'username',
            'password' => 'password',
            'client_id' => '',
            'client_secret' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'currency' => IranCurrency::RIAL,
        ],
        'etebarino' => [
            'apiPurchaseUrl' => 'https://api.etebarino.com/public/merchant/request-payment',
            'apiPaymentUrl' => 'https://panel.etebarino.com/gateway/public/ipg',
            'apiVerificationUrl' => 'https://api.etebarino.com/public/merchant/verify-payment',
            'merchantId' => '',
            'terminalId' => '',
            'username' => '',
            'password' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using etebarino',
        ],
        'irandargah' => [
            /* Normal api */
            'apiPurchaseUrl' => 'https://dargaah.com/payment',
            'apiPaymentUrl' => 'https://dargaah.com/ird/startpay/',
            'apiVerificationUrl' => 'https://dargaah.com/verification',

            /* Sandbox api */
            'sandboxApiPurchaseUrl' => 'https://dargaah.com/sandbox/payment',
            'sandboxApiPaymentUrl' => 'https://dargaah.com/sandbox/ird/startpay/',
            'sandboxApiVerificationUrl' => 'https://dargaah.com/sandbox/verification',

            'sandbox' => false, // Set it to true for test environments
            'merchantId' => '', // Set `TEST` for test environments (sandbox)
            'callbackUrl' => '',
            'currency' => IranCurrency::RIAL,
        ],
        'irankish' => [
            'apiPurchaseUrl' => 'https://ikc.shaparak.ir/api/v3/tokenization/make',
            'apiPaymentUrl' => 'https://ikc.shaparak.ir/iuiv3/IPG/Index/',
            'apiVerificationUrl' => 'https://ikc.shaparak.ir/api/v3/confirmation/purchase',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using irankish',
            'terminalId' => '',
            'password' => '',
            'acceptorId' => '',
            'pubKey' => '',
            'currency' => IranCurrency::TOMAN,
        ],
        'jibit' => [
            'apiPaymentUrl' => 'https://napi.jibit.ir/ppg/v3',
            'apiKey' => '',
            'apiSecret' => '',
            // You can change the token storage path in Laravel like this
            // 'tokenStoragePath' => function_exists('storage_path') ? storage_path('jibit/') : 'jibit/'
            'tokenStoragePath' => 'jibit/',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using jibit',
            'currency' => IranCurrency::TOMAN, // Can be R, T (Rial, Toman)
        ],
        'nextpay' => [
            'apiPurchaseUrl' => 'https://nextpay.org/nx/gateway/token',
            'apiPaymentUrl' => 'https://nextpay.org/nx/gateway/payment/',
            'apiVerificationUrl' => 'https://nextpay.org/nx/gateway/verify',
            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using nextpay',
            'currency' => IranCurrency::TOMAN,
        ],
        'omidpay' => [
            'apiGenerateTokenUrl' => 'https://ref.sayancard.ir/ref-payment/RestServices/mts/generateTokenWithNoSign/',
            'apiPaymentUrl' => 'https://say.shaparak.ir/_ipgw_/MainTemplate/payment/',
            'apiVerificationUrl' => 'https://ref.sayancard.ir/ref-payment/RestServices/mts/verifyMerchantTrans/',
            'username' => '',
            'merchantId' => '',
            'password' => '',
            'callbackUrl' => '',
            'description' => 'payment using omidpay',
            'currency' => IranCurrency::TOMAN,
        ],
        'parsian' => [
            'apiPurchaseUrl' => 'https://pec.shaparak.ir/NewIPGServices/Sale/SaleService.asmx?wsdl',
            'apiPaymentUrl' => 'https://pec.shaparak.ir/NewIPG/',
            'apiVerificationUrl' => 'https://pec.shaparak.ir/NewIPGServices/Confirm/ConfirmService.asmx?wsdl',
            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using parsian',
            'currency' => IranCurrency::TOMAN,
        ],
        'parspal' => [
            /* Normal api */
            'apiPurchaseUrl' => 'https://api.parspal.com/v1/payment/request',
            'apiVerificationUrl' => 'https://api.parspal.com/v1/payment/verify',

            /* Sandbox api */
            'sandboxApiPurchaseUrl' => 'https://sandbox.api.parspal.com/v1/payment/request',
            'sandboxApiVerificationUrl' => 'https://sandbox.api.parspal.com/v1/payment/verify',

            // You can change the cache path in Laravel like this
            // 'cachePath' => function_exists('storage_path') ? storage_path('parspal/') : 'parspal/'
            'cachePath' => 'parspal/',
            'cacheExpireTTL' => 3600, // Cache expire time in seconds

            'sandbox' => false, // Set it to true for test environments
            'merchantId' => '', // Set `00000000aaaabbbbcccc000000000000` for test environments (sandbox)
            'callbackUrl' => '',
            'currency' => IranCurrency::TOMAN, // Can be R, T (Rial, Toman)
        ],
        'pasargad' => [
            'baseUrl' => 'https://pep.shaparak.ir/dorsa1',
            'userName' => '',
            'password' => '',
            'merchantId' => '',
            'terminalCode' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'currency' => IranCurrency::TOMAN,
        ],
        'panapal' => [
            'apiPurchaseUrl' => 'https://paanapardakht.com/webservice/rest/PaymentRequest',
            'apiVerificationUrl' => 'https://paanapardakht.com/webservice/rest/PaymentVerification',
            'apiPaymentUrl' => 'https://paanapardakht.com/startPay/',
            'merchantId' => 'XXXX-XXXX-XXXX-XXXXXXXXXXXXXXXXXXXXX',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'currency' => IranCurrency::TOMAN,
        ],
        'paypal' => [
            /* normal api */
            'accessTokenUrl' => 'https://api-m.paypal.com/v1/oauth2/token',
            'purchaseUrl' => 'https://api-m.paypal.com/v2/checkout/orders',
            'paymentUrl' => 'https://www.paypal.com/checkoutnow?token=',
            'verificationUrl' => 'https://api-m.paypal.com/v2/checkout/orders/{order_id}/capture', // {order_id} will be replaced automatically

            /* sandbox api */
            'sandboxAccessTokenUrl' => 'https://api-m.sandbox.paypal.com/v1/oauth2/token',
            'sandboxPurchaseUrl' => 'https://api-m.sandbox.paypal.com/v2/checkout/orders',
            'sandboxPaymentUrl' => 'https://www.sandbox.paypal.com/checkoutnow?token=',
            'sandboxVerificationUrl' => 'https://api-m.sandbox.paypal.com/v2/checkout/orders/{order_id}/capture', // {order_id} will be replaced automatically

            'mode' => 'normal', // Can be 'normal' or 'sandbox'
            'clientId' => '',
            'clientSecret' => '',
            'callbackUrl' => 'https://yoursite.com/path/to',
            'currency' => 'USD', // See https://developer.paypal.com/api/rest/reference/currency-codes for a list of available currency codes
        ],
        'payping' => [
            'apiPurchaseUrl' => 'https://api.payping.ir/v3/pay/',
            'apiPaymentUrl' => 'https://api.payping.ir/v3/pay/start/',
            'apiVerificationUrl' => 'https://api.payping.ir/v3/pay/verify/',
            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using payping',
            'currency' => IranCurrency::TOMAN,
        ],
        'paystar' => [
            'apiPurchaseUrl' => 'https://core.paystar.ir/api/pardakht/create/',
            'apiPaymentUrl' => 'https://core.paystar.ir/api/pardakht/payment/',
            'apiVerificationUrl' => 'https://core.paystar.ir/api/pardakht/verify/',
            'gatewayId' => '', // your gateway id
            'signKey' => '', // sign key of your gateway
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using paystar',
            'currency' => IranCurrency::RIAL,
        ],
        'poolam' => [
            'apiPurchaseUrl' => 'https://poolam.ir/invoice/request/',
            'apiPaymentUrl' => 'https://poolam.ir/invoice/pay/',
            'apiVerificationUrl' => 'https://poolam.ir/invoice/check/',
            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using poolam',
            'currency' => IranCurrency::TOMAN,
        ],
        'pna' => [
            'apiNormalSale' => 'https://pna.shaparak.ir/mhipg/api/Payment/NormalSale',
            'apiPaymentUrl' => 'https://pna.shaparak.ir/mhui/home/index/',
            'apiConfirmationUrl' => 'https://pna.shaparak.ir/mhipg/api/Payment/confirm',
            'CorporationPin' => '',
            'currency' => IranCurrency::RIAL,
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using pna',
        ],
        'sadad' => [
            'apiPaymentByMultiIdentityUrl' => 'https://sadad.shaparak.ir/VPG/api/v0/PaymentByMultiIdentityRequest',
            'apiPaymentByIdentityUrl' => 'https://sadad.shaparak.ir/api/v0/PaymentByIdentity/PaymentRequest',
            'apiPaymentUrl' => 'https://sadad.shaparak.ir/api/v0/Request/PaymentRequest',
            'apiPurchaseUrl' => 'https://sadad.shaparak.ir/Purchase',
            'apiVerificationUrl' => 'https://sadad.shaparak.ir/VPG/api/v0/Advice/Verify',
            'key' => '',
            'merchantId' => '',
            'terminalId' => '',
            'callbackUrl' => '',
            'currency' => IranCurrency::TOMAN,
            'mode' => 'normal', // can be normal, PaymentByIdentity, PaymentByMultiIdentity,
            'PaymentIdentity' => '',
            'MultiIdentityRows' => [
                [
                    'IbanNumber' => '', // Sheba number (with IR)
                    'Amount' => 0,
                    'PaymentIdentity' => '',
                ],
            ],
            'description' => 'payment using sadad',
        ],
        'saman' => [
            'apiPurchaseUrl' => 'https://sep.shaparak.ir/Payments/InitPayment.asmx?WSDL',
            'apiPaymentUrl' => 'https://sep.shaparak.ir/payment.aspx',
            'apiVerificationUrl' => 'https://sep.shaparak.ir/payments/referencepayment.asmx?WSDL',
            'merchantId' => '',
            'password' => '',
            'callbackUrl' => '',
            'description' => 'payment using saman',
            'currency' => IranCurrency::TOMAN,
        ],
        'sep' => [
            'apiGetToken' => 'https://sep.shaparak.ir/OnlinePG/OnlinePG',
            'apiPaymentUrl' => 'https://sep.shaparak.ir/OnlinePG/OnlinePG',
            'apiVerificationUrl' => 'https://sep.shaparak.ir/verifyTxnRandomSessionkey/ipg/VerifyTransaction',
            'terminalId' => '',
            'callbackUrl' => '',
            'description' => 'Saman Electronic Payment for Saderat & Keshavarzi',
            'currency' => IranCurrency::TOMAN,
        ],
        'sepehr' => [
            'apiGetToken' => 'https://sepehr.shaparak.ir:8081/V1/PeymentApi/GetToken',
            'apiPaymentUrl' => 'https://sepehr.shaparak.ir:8080/Pay',
            'apiVerificationUrl' => 'https://sepehr.shaparak.ir:8081/V1/PeymentApi/Advice',
            'terminalId' => '',
            'callbackUrl' => '',
            'description' => 'payment using sepehr(saderat)',
            'currency' => IranCurrency::TOMAN,
        ],
        'yekpay' => [
            'apiPurchaseUrl' => 'https://gate.yekpay.com/api/payment/server?wsdl',
            'apiPaymentUrl' => 'https://gate.yekpay.com/api/payment/start/',
            'apiVerificationUrl' => 'https://gate.yekpay.com/api/payment/server?wsdl',
            'fromCurrencyCode' => 978,
            'toCurrencyCode' => 364,
            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using yekpay',
        ],
        'zarinpal' => [
            /* normal api */
            'apiPurchaseUrl' => 'https://api.zarinpal.com/pg/v4/payment/request.json',
            'apiPaymentUrl' => 'https://www.zarinpal.com/pg/StartPay/',
            'apiVerificationUrl' => 'https://api.zarinpal.com/pg/v4/payment/verify.json',

            /* sandbox api */
            'sandboxApiPurchaseUrl' => 'https://sandbox.zarinpal.com/pg/v4/payment/request.json',
            'sandboxApiPaymentUrl'  => 'https://sandbox.zarinpal.com/pg/StartPay/',
            'sandboxApiVerificationUrl' => 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json',

            /* zarinGate api */
            'zaringateApiPurchaseUrl' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',
            'zaringateApiPaymentUrl' => 'https://www.zarinpal.com/pg/StartPay/:authority/ZarinGate',
            'zaringateApiVerificationUrl' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',

            'mode' => 'normal', // can be normal, sandbox, zaringate
            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using zarinpal',
            'currency' => IranCurrency::TOMAN,
        ],
        'zibal' => [
            /* normal api */
            'apiPurchaseUrl' => 'https://gateway.zibal.ir/v1/request',
            'apiPaymentUrl' => 'https://gateway.zibal.ir/start/',
            'apiVerificationUrl' => 'https://gateway.zibal.ir/v1/verify',

            'mode' => 'normal', // can be normal, direct

            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using zibal',
            'referer' => null,
            'currency' => IranCurrency::TOMAN,
        ],
        'sepordeh' => [
            'apiPurchaseUrl' => 'https://sepordeh.com/merchant/invoices/add',
            'apiPaymentUrl' => 'https://sepordeh.com/merchant/invoices/pay/id:',
            'apiDirectPaymentUrl' => 'https://sepordeh.com/merchant/invoices/pay/automatic:true/id:',
            'apiVerificationUrl' => 'https://sepordeh.com/merchant/invoices/verify',
            'mode' => 'normal', // can be normal, direct
            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using sepordeh',
            'currency' => IranCurrency::TOMAN,
        ],
        'rayanpay' => [
            'apiPurchaseUrl' => 'https://bpm.shaparak.ir/pgwchannel/startpay.mellat',
            'apiTokenUrl' => 'https://pms.rayanpay.com/api/v1/auth/token/generate',
            'apiPayStart' => 'https://pms.rayanpay.com/api/v1/ipg/payment/start',
            'apiPayVerify' => 'https://pms.rayanpay.com/api/v1/ipg/payment/response/parse',
            'username' => '',
            'client_id' => '',
            'password' => '',
            'callbackUrl' => '',
            'currency' => IranCurrency::RIAL,
        ],
        'shepa' => [
            /* Normal api */
            'apiPurchaseUrl' => 'https://merchant.shepa.com/api/v1/token',
            'apiPaymentUrl' => 'https://merchant.shepa.com/v1/',
            'apiVerificationUrl' => 'https://merchant.shepa.com/api/v1/verify',

            /* Sandbox api */
            'sandboxApiPurchaseUrl' => 'https://sandbox.shepa.com/api/v1/token',
            'sandboxApiPaymentUrl' => 'https://sandbox.shepa.com/v1/',
            'sandboxApiVerificationUrl' => 'https://sandbox.shepa.com/api/v1/verify',

            'sandbox' => false, // Set it to true for test environments
            'merchantId' => '', // Set `sandbox` for test environments (sandbox)
            'callbackUrl' => '',
            'currency' => IranCurrency::RIAL,
        ],
        'sizpay' => [
            'apiPurchaseUrl' => 'https://rt.sizpay.ir/KimiaIPGRouteService.asmx?WSDL',
            'apiPaymentUrl' => 'https://rt.sizpay.ir/Route/Payment',
            'apiVerificationUrl' => 'https://rt.sizpay.ir/KimiaIPGRouteService.asmx?WSDL',
            'merchantId' => '',
            'terminal' => '',
            'username' => '',
            'password' => '',
            'SignData' => '',
            'callbackUrl' => '',
            'currency' => IranCurrency::RIAL,
        ],
        'vandar' => [
            'apiPurchaseUrl' => 'https://ipg.vandar.io/api/v3/send',
            'apiPaymentUrl' => 'https://ipg.vandar.io/v3/',
            'apiVerificationUrl' => 'https://ipg.vandar.io/api/v3/verify',
            'callbackUrl' => '',
            'merchantId' => '',
            'description' => 'payment using Vandar',
            'currency' => IranCurrency::TOMAN,
        ],
        'aqayepardakht' => [
            'apiPurchaseUrl' => 'https://panel.aqayepardakht.ir/api/v2/create',
            'apiPaymentUrl' => 'https://panel.aqayepardakht.ir/startpay/',
            'apiPaymentUrlSandbox' => 'https://panel.aqayepardakht.ir/startpay/sandbox/',
            'apiVerificationUrl' => 'https://panel.aqayepardakht.ir/api/v2/verify',
            'mode' => 'normal', //normal | sandbox
            'callbackUrl' => '',
            'pin' => '',
            'invoice_id' => '',
            'mobile' => '',
            'email' => '',
            'description' => 'payment using Aqayepardakht',
            'currency' => IranCurrency::TOMAN,
        ],
        'azki' => [
            'apiPaymentUrl' => 'https://api.azkivam.com',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'fallbackUrl' => 'http://yoursite.com/path/to',
            'merchantId' => '',
            'key' => '',
            'currency' => IranCurrency::TOMAN,
            'description' => 'payment using azki',
        ],
        'payfa' => [
            'apiPurchaseUrl' => 'https://payment.payfa.com/v2/api/Transaction/Request',
            'apiPaymentUrl' => 'https://payment.payfa.ir/v2/api/Transaction/Pay/',
            'apiVerificationUrl' => 'https://payment.payfa.com/v2/api/Transaction/Verify/',
            'callbackUrl' => '',
            'apiKey' => '',
            'currency' => IranCurrency::TOMAN,
        ],
        'toman' => [
            'base_url' => 'https://escrow-api.toman.ir/api/v1',
            'shop_slug' => '',
            'auth_code' => '',
            'data' => ''
        ],
        'bitpay' => [
            'apiPurchaseUrl' => 'https://bitpay.ir/payment/gateway-send',
            'apiPaymentUrl' => 'https://bitpay.ir/payment/gateway-{id_get}-get',
            'apiVerificationUrl' => 'https://bitpay.ir/payment/gateway-result-second',
            'callbackUrl' => '',
            'api_token' => '',
            'description' => 'payment using Bitpay',
            'currency' => IranCurrency::RIAL,
        ],
        'minipay' => [
            'apiPurchaseUrl' => 'https://v1.minipay.me/api/pg/request/',
            'apiPaymentUrl' => 'https://ipg.minipay.me/',
            'apiVerificationUrl' => 'https://v1.minipay.me/api/pg/verify/',
            'merchantId' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using Minipay.',
            'currency' => IranCurrency::TOMAN,
        ],
        'snapppay' => [
            'apiPaymentUrl' => 'https://fms-gateway-staging.apps.public.teh-1.snappcloud.io',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'username' => 'username',
            'password' => 'password',
            'client_id' => '',
            'client_secret' => '',
            'description' => 'payment using Snapp Pay.',
            'currency' => IranCurrency::TOMAN,
        ],
        'daracard' => [
            'apiPurchaseUrl' => 'https://ipg.daracard.co/api/v0/Request/PaymentRequest/',
            'getAction' => 'https://ipg.daracard.co/#/purchase/',
            'apiPaymentUrl' => 'https://ipg.daracard.co/#/purchase/',
            'apiVerificationUrl' => 'https://ipg.daracard.co/api/v0/Advice/Verify/',
            'merchantId' => '',
            'terminalId' => '',
            'username' => '',
            'password' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using daracard',
            'orderId' => '',
        ],
        'digify' => [
            // the base url of the gateway, as given to you by digify
            'baseUrl' => '',
            'apiKey' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
        ],
        'stripe' => [
            'name'     => 'Stripe',
            'secret'   => 'STRIPE_SECRET',
            'currency' => 'USD',
            'success_url' => 'http://yoursite.com/path/success',
            'cancel_url' => 'http://yoursite.com/path/fail',
        ],
        'torobpay' => [
            'api_url' => 'https://cpg.torobpay.com',
            'client_id' => '',
            'client_secret' => '',
            'username' =>'',
            'password' => '',
            'currency' => IranCurrency::TOMAN, // یا Currency::RIAL
            'callbackUrl' => 'http://yoursite.com/path/to',
        ],
        'xendit' => [
            'apiUrl' => 'https://api.xendit.co/',
            'secretKey' => '', // Your Xendit secret key
            'currency' => 'IDR', // Can be IDR, PHP, THB, VND, MYR, SGD, USD
            'invoiceDuration' => 86400, // Invoice duration in seconds (24 hours default)
            'payerEmail' => '', // Optional: payer email for invoice
            'customerName' => '', // Optional: customer name
            'notificationEmail' => '', // Optional: email for notifications
            'successReturnUrl' => 'http://yoursite.com/payment/success',
            'failureReturnUrl' => 'http://yoursite.com/payment/failure',
            'paymentMethods' => ['BANK_TRANSFER', 'EWALLET', 'CREDIT_CARD'], // Available payment methods
            'description' => 'Payment via Xendit',
        ],
        'refah' => [
            'apiPurchaseUrl' => 'https://pna.shaparak.ir/refipg/api/purchase',
            'apiPaymentUrl' => 'https://pna.shaparak.ir/refui',
            'apiVerificationUrl' => 'https://pna.shaparak.ir/refipg/api/confirm-transaction',
            'username' => '',
            'password' => '',
            'terminalNumber' => '',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using refah',
            'currency' => IranCurrency::RIAL,
        ],
        'cryptomus' => [
            'apiPurchaseUrl' => 'https://api.cryptomus.com/v1/payment',
            'apiVerificationUrl' => 'https://api.cryptomus.com/v1/payment/info',
            'merchantId' => '', // The UUID of your merchant
            'apiKey' => '', // The payment API key of your merchant
            'currency' => 'USD', // Any currency the gateway takes: USD, EUR, USDT, ...
            'callbackUrl' => 'http://yoursite.com/path/to',
            'webhookUrl' => null, // Optional: where the gateway posts the status of the payment
        ],
        'novinopay' => [
            'apiPurchaseUrl' => 'https://api.novinopay.com/payment/ipg/v2/request',
            'apiVerificationUrl' => 'https://api.novinopay.com/payment/ipg/v2/verification',
            'merchantId' => '', // Set it to "test" to try the gateway out
            'callbackUrl' => 'http://yoursite.com/path/to',
            'description' => 'payment using novinopay',
            'currency' => IranCurrency::TOMAN,
        ],
        'skrill' => [
            'apiPaymentUrl' => 'https://pay.skrill.com',
            'apiQueryUrl' => 'https://www.skrill.com/app/query.pl',
            'merchantEmail' => '', // The email address of your Skrill merchant account
            // The API/MQI password: enable the MQI and allow the IP of your server in Settings > Developer Settings
            'mqiPassword' => '',
            'currency' => 'USD', // A 3-letter ISO 4217 code: USD, EUR, GBP, ...
            'language' => 'EN',
            'callbackUrl' => 'http://yoursite.com/path/to',
            'webhookUrl' => null, // Optional: the status_url the gateway posts the details of the payment to
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Maps
    |--------------------------------------------------------------------------
    |
    | This is the array of Classes that maps to Drivers above.
    | You can create your own driver if you like and add the
    | config in the drivers array and the class to use for
    | here with the same name. You will have to extend
    | Extenbox\Multipay\Abstracts\Driver in your driver.
    |
    */
    'map' => [
        'bajet' => \Extenbox\Multipay\Drivers\Bajet\Bajet::class,
        'local' => \Extenbox\Multipay\Drivers\Local\Local::class,
        'panapal' => \Extenbox\Multipay\Drivers\Panapal\Panapal::class,
        'gooyapay' => \Extenbox\Multipay\Drivers\Gooyapay\Gooyapay::class,
        'fanavacard' => \Extenbox\Multipay\Drivers\Fanavacard\Fanavacard::class,
        'asanpardakht' => \Extenbox\Multipay\Drivers\Asanpardakht\Asanpardakht::class,
        'atipay' => \Extenbox\Multipay\Drivers\Atipay\Atipay::class,
        'behpardakht' => \Extenbox\Multipay\Drivers\Behpardakht\Behpardakht::class,
        'digipay' => \Extenbox\Multipay\Drivers\Digipay\Digipay::class,
        'etebarino' => \Extenbox\Multipay\Drivers\Etebarino\Etebarino::class,
        'irandargah' => \Extenbox\Multipay\Drivers\IranDargah\IranDargah::class,
        'irankish' => \Extenbox\Multipay\Drivers\Irankish\Irankish::class,
        'jibit' => \Extenbox\Multipay\Drivers\Jibit\Jibit::class,
        'nextpay' => \Extenbox\Multipay\Drivers\Nextpay\Nextpay::class,
        'omidpay' => \Extenbox\Multipay\Drivers\Omidpay\Omidpay::class,
        'parsian' => \Extenbox\Multipay\Drivers\Parsian\Parsian::class,
        'parspal' => \Extenbox\Multipay\Drivers\Parspal\Parspal::class,
        'pasargad' => \Extenbox\Multipay\Drivers\Pasargad\Pasargad::class,
        'paypal' => \Extenbox\Multipay\Drivers\Paypal\Paypal::class,
        'payping' => \Extenbox\Multipay\Drivers\Payping\Payping::class,
        'paystar' => \Extenbox\Multipay\Drivers\Paystar\Paystar::class,
        'poolam' => \Extenbox\Multipay\Drivers\Poolam\Poolam::class,
        'sadad' => \Extenbox\Multipay\Drivers\Sadad\Sadad::class,
        'saman' => \Extenbox\Multipay\Drivers\Saman\Saman::class,
        'sep' => \Extenbox\Multipay\Drivers\SEP\SEP::class,
        'sepehr' => \Extenbox\Multipay\Drivers\Sepehr\Sepehr::class,
        'yekpay' => \Extenbox\Multipay\Drivers\Yekpay\Yekpay::class,
        'zarinpal' => \Extenbox\Multipay\Drivers\Zarinpal\Zarinpal::class,
        'zibal' => \Extenbox\Multipay\Drivers\Zibal\Zibal::class,
        'sepordeh' => \Extenbox\Multipay\Drivers\Sepordeh\Sepordeh::class,
        'rayanpay' => \Extenbox\Multipay\Drivers\Rayanpay\Rayanpay::class,
        'shepa' => \Extenbox\Multipay\Drivers\Shepa\Shepa::class,
        'sizpay' => \Extenbox\Multipay\Drivers\Sizpay\Sizpay::class,
        'vandar' => \Extenbox\Multipay\Drivers\Vandar\Vandar::class,
        'aqayepardakht' => \Extenbox\Multipay\Drivers\Aqayepardakht\Aqayepardakht::class,
        'azki' => \Extenbox\Multipay\Drivers\Azki\Azki::class,
        'payfa' => \Extenbox\Multipay\Drivers\Payfa\Payfa::class,
        'toman' => \Extenbox\Multipay\Drivers\Toman\Toman::class,
        'bitpay' => \Extenbox\Multipay\Drivers\Bitpay\Bitpay::class,
        'minipay' => \Extenbox\Multipay\Drivers\Minipay\Minipay::class,
        'snapppay' => \Extenbox\Multipay\Drivers\SnappPay\SnappPay::class,
        'pna' => \Extenbox\Multipay\Drivers\Pna\Pna::class,
        'stripe' => \Extenbox\Multipay\Drivers\Stripe\Stripe::class,
        'torobpay' =>\Extenbox\Multipay\Drivers\TorobPay\TorobPay::class,
        'xendit' => \Extenbox\Multipay\Drivers\Xendit\Xendit::class,
        'refah' => \Extenbox\Multipay\Drivers\Refah\Refah::class,
        'daracard' => \Extenbox\Multipay\Drivers\Daracard\Daracard::class,
        'digify' => \Extenbox\Multipay\Drivers\Digify\Digify::class,
        'cryptomus' => \Extenbox\Multipay\Drivers\Cryptomus\Cryptomus::class,
        'novinopay' => \Extenbox\Multipay\Drivers\Novinopay\Novinopay::class,
        'skrill' => \Extenbox\Multipay\Drivers\Skrill\Skrill::class,

    ]
];
