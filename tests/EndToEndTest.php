<?php

namespace Extenbox\Multipay\Tests;

use Extenbox\Multipay\Exceptions\InvalidPaymentException;
use Extenbox\Multipay\Payment;
use Extenbox\Multipay\Request;

/**
 * A full run of the payment flow through the public API of the package:
 * the purchase, the rendered gateway page, the buyer pressing one of its
 * buttons and the verification of the callback the button opens.
 *
 * Everything runs against the local driver, so the whole flow of a real
 * gateway (minus the external HTTP calls) is exercised end to end.
 */
class EndToEndTest extends TestCase
{
    public function testTheWholeLocalPaymentFlowWorksEndToEnd(): void
    {
        $payment = $this->newLocalPayment();

        // The buyer places an order and the gateway gives it a transaction id.
        $capturedId = null;
        $payment
            ->amount(150000)
            ->detail('orderId', 'order-42')
            ->purchase(null, function ($driver, $transactionId) use (&$capturedId): void {
                $capturedId = $transactionId;
            });

        $this->assertNotEmpty($capturedId);

        // The gateway page is rendered with the details of the order on it.
        $html = $payment->pay()->render();

        $this->assertStringContainsString('id="success_form"', $html);
        $this->assertStringContainsString('id="cancel_form"', $html);
        $this->assertStringContainsString('order-42', $html);
        $this->assertStringContainsString('150,000', $html);

        // The buyer presses the pay button, so the browser opens the callback url.
        $callback = $this->followFormButton($html, 'success_form');

        $this->assertSame((string) $capturedId, (string) ($callback['transactionId'] ?? null));

        // The callback page verifies the payment.
        Request::overwrite('input', fn ($name) => $callback[$name] ?? null);

        $receipt = $payment->verify();

        $this->assertSame('local', $receipt->getDriver());
        $this->assertSame((string) $capturedId, (string) $receipt->getReferenceId());
        $this->assertSame((string) $capturedId, (string) $receipt->getDetail('referenceNo'));
        $this->assertSame('order-42', $receipt->getDetail('orderId'));
        $this->assertNotEmpty($receipt->getDetail('traceNo'));
        $this->assertNotEmpty($receipt->getDetail('cardNo'));
    }

    public function testCancellingAtTheGatewayFailsTheVerification(): void
    {
        $payment = $this->newLocalPayment();
        $payment->amount(150000)->detail('orderId', 'order-42')->purchase();

        $html = $payment->pay()->render();

        $this->assertStringContainsString('id="cancel_form"', $html);

        // The buyer presses the cancel button instead of the pay button.
        $callback = $this->followFormButton($html, 'cancel_form');

        $this->assertSame('true', $callback['cancel'] ?? null);
        $this->assertNotEmpty($callback['transactionId'] ?? null);

        Request::overwrite('input', fn ($name) => $callback[$name] ?? null);

        $this->expectException(InvalidPaymentException::class);
        $this->expectExceptionMessage('تراکنش توسط خریدار لغو شده است.');

        $payment->verify();
    }

    /**
     * Read the callback url of one of the buttons of the rendered gateway page
     * and turn its query string into the request data the callback page receives.
     */
    private function followFormButton(string $html, string $formId) : array
    {
        $pattern = '/<form action="([^"]*)" method="post" id="' . preg_quote($formId, '/') . '"/';

        $this->assertSame(
            1,
            preg_match($pattern, $html, $matches),
            'The '.$formId.' form was not rendered on the gateway page.'
        );

        $action = html_entity_decode($matches[1], ENT_QUOTES);
        parse_str((string) parse_url($action, PHP_URL_QUERY), $query);

        return $query;
    }

    private function newLocalPayment() : Payment
    {
        return (new Payment($this->config()))->via('local');
    }
}
