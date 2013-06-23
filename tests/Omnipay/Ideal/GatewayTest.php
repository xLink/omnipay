<?php

/*
 * This file is part of the Omnipay package.
 *
 * (c) Adrian Macneil <adrian@adrianmacneil.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Omnipay\Ideal;

use Omnipay\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testFetchIssuers()
    {
        $request = $this->gateway->fetchIssuers(array('merchantId' => 'abc123'));

        $this->assertInstanceOf('Omnipay\Ideal\Message\FetchIssuersRequest', $request);
        $this->assertSame('abc123', $request->getMerchantId());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array('amount' => 123));

        $this->assertInstanceOf('Omnipay\Ideal\Message\PurchaseRequest', $request);
        $this->assertSame(123, $request->getAmount());
    }

    public function testPurchaseReturn()
    {
        $request = $this->gateway->completePurchase(array('amount' => 123));

        $this->assertInstanceOf('Omnipay\Ideal\Message\CompletePurchaseRequest', $request);
        $this->assertSame(123, $request->getAmount());
    }
}
