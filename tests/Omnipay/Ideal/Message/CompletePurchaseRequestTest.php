<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Ideal\Message;

use Omnipay\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'acquirer' => 'ing',
            'merchantId' => 'abc123',
            'privateKeyPath' => TESTS_DIR.'/test.key',
            'publicKeyPath' => TESTS_DIR.'/test.crt',
            'testMode' => true,
            'issuer' => 'INGBNL2A',
            'amount' => 1200,
            'currency' => 'EUR',
            'returnUrl' => 'https://www.example.com/return',
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $this->assertSame('abc123', (string) $data->Merchant->merchantID);
    }
}
