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

class FetchIssuersRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = new FetchIssuersRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'acquirer' => 'ing',
            'merchantId' => 'abc123',
            'privateKeyPath' => TESTS_DIR.'/test.key',
            'publicKeyPath' => TESTS_DIR.'/test.crt',
            'testMode' => true,
        ));
    }

    public function testGetData()
    {
        $this->request->setMerchantId('abc123');

        $data = $this->request->getData();

        $this->assertSame('abc123', (string) $data->Merchant->merchantID);
    }

    public function testSendSuccess()
    {
        $this->setMockHttpResponse('FetchIssuersSuccess.txt');
        $response = $this->request->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertNull($response->getMessage());
        $this->assertNull($response->getCode());
        $this->assertSame(array('INGBNL2A' => 'Issuer Simulation V3 - ING', 'RABONL2U' => 'Issuer Simulation V3 - RABO'), $response->getIssuers());
    }

    public function testSendFailure()
    {
        $this->setMockHttpResponse('FetchIssuersFailure.txt');
        $response = $this->request->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertNull($response->getTransactionReference());
        $this->assertSame('Mandatory value missing (Field generating error: merchantID)', $response->getMessage());
        $this->assertSame('IX1600', $response->getCode());
        $this->assertNull($response->getIssuers());
    }
}
