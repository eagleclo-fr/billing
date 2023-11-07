<?php
namespace src\Billing;
use src\Router\AltoRouter;

class BillingModules
{
    public function __construct(AltoRouter $router)
    {
        $router->map('GET|POST', '/shop', 'manager/billing/products/index', 'manager.billing.products');

        $router->map('GET|POST', '/billing', 'manager/billing/balance', 'manager.billing.balance');


        $router->map('GET|POST', '/billing/topup', 'manager/billing/topup', 'manager.billing.topup');
        $router->map('GET|POST', '/billing/topup/stripe/[a:id]', 'manager/billing/payment/stripe', 'manager.billing.topup.stripe');
        $router->map('GET|POST','/billing/topup/paypal/[a:id]', 'manager/billing/payment/paypal', 'manager.billing.topup.paypal');
        $router->map('GET|POST','/billing/topup/paypal/process/[*:paymentid]/[*:token]/[*:payerid]/[*:uuid]', 'manager/billing/payment/paypal.process', 'manager.billing.topup.process.paypal');


        $router->map('GET','/billing/topup/success/[a:id]', 'manager/billing/payment/checkPayment', 'manager.billing.topup.check');

        $router->map('GET|POST', '/billing/invoices', 'manager/billing/invoices', 'manager.billing.invoices');
        $router->map('GET|POST', '/billing/invoices/[i:id]', 'manager/billing/invoice.edit', 'manager.billing.invoices.edit');

        $router->map('GET|POST', '/billing/renew/[*:idservice]', 'manager/billing/renew', 'manager.billing.renew');
    }
}