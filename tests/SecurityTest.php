<?php

namespace Extenbox\Multipay\Tests;

use Extenbox\Multipay\Drivers\Local\Local;
use Extenbox\Multipay\Exceptions\InvalidPaymentException;
use Extenbox\Multipay\Invoice;
use Extenbox\Multipay\RedirectionForm;
use Extenbox\Multipay\Request;

/**
 * Attack-oriented tests: the forms the package renders to a buyer and the
 * callbacks the local driver accepts back must not be injectable by user
 * input.
 */
class SecurityTest extends TestCase
{
    public function testTheRedirectFormEscapesUserControlledValues(): void
    {
        $form = new RedirectionForm(
            'https://example.com/pay"><script>alert(1)</script>',
            ['token"><script>alert(2)</script>' => 'value"><script>alert(3)</script>'],
            'POST"><script>alert(4)</script>'
        );

        $html = $form->render();

        $this->assertStringNotContainsString('<script>alert(', $html);
        $this->assertStringContainsString(
            'action="https://example.com/pay&quot;&gt;&lt;script&gt;alert(1)&lt;/script&gt;"',
            $html
        );
        $this->assertStringContainsString(
            'method="POST&quot;&gt;&lt;script&gt;alert(4)&lt;/script&gt;"',
            $html
        );
        $this->assertStringContainsString(
            'name="token&quot;&gt;&lt;script&gt;alert(2)&lt;/script&gt;"',
            $html
        );
        $this->assertStringContainsString(
            'value="value&quot;&gt;&lt;script&gt;alert(3)&lt;/script&gt;"',
            $html
        );
    }

    public function testTheLocalGatewayPageEscapesUserControlledText(): void
    {
        $driver = $this->getLocalDriver([
            'title' => '<script>alert(1)</script>',
            'description' => '<script>alert(2)</script>',
            'orderLabel' => '<script>alert(3)</script>',
            'payButton' => '<script>alert(4)</script>',
            'cancelButton' => '<script>alert(5)</script>',
            'callbackUrl' => '/callback?q="><script>alert(6)</script>',
        ]);
        $driver->detail('orderId', '<script>alert(7)</script>');
        $driver->purchase();

        $html = $driver->pay()->render();

        $this->assertStringNotContainsString('<script>alert(', $html);

        foreach ([1, 2, 3, 4, 5, 6, 7] as $number) {
            $this->assertStringContainsString(
                '&lt;script&gt;alert('.$number.')&lt;/script&gt;',
                $html,
                'alert('.$number.') should be escaped'
            );
        }
    }

    public function testAVerificationCallbackWithAnArrayTransactionIdIsRejected(): void
    {
        $this->fakeRequest(['transactionId' => ['1234567']]);

        $this->expectException(InvalidPaymentException::class);

        $this->getLocalDriver()->verify();
    }

    public function testAVerificationCallbackWithAFloatTransactionIdIsRejected(): void
    {
        $this->fakeRequest(['transactionId' => 12.5]);

        $this->expectException(InvalidPaymentException::class);

        $this->getLocalDriver()->verify();
    }

    public function testAVerificationCallbackWithAnArrayCancelFlagIsRejected(): void
    {
        $this->fakeRequest(['transactionId' => '1234567', 'cancel' => [true]]);

        $this->expectException(InvalidPaymentException::class);

        $this->getLocalDriver()->verify();
    }

    private function getLocalDriver(array $settings = []) : Local
    {
        return new Local(new Invoice, array_merge($this->config()['drivers']['local'], ['callbackUrl' => '/callback'], $settings));
    }

    private function fakeRequest(array $data) : void
    {
        Request::overwrite('input', fn ($name) => $data[$name] ?? null);
    }
}
