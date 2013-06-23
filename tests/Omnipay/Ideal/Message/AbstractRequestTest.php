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

use DOMDocument;
use Mockery as m;
use Omnipay\TestCase;

class AbstractRequestTest extends TestCase
{
    public function setUp()
    {
        $this->request = m::mock('Omnipay\Ideal\Message\AbstractRequest[getData]');
        $this->request->initialize(array(
            'privateKeyPath' => TESTS_DIR.'/test.key',
            'publicKeyPath' => TESTS_DIR.'/test.crt',
        ));
    }

    public function testSignXML()
    {
        $xml = $this->request->signXML('<DirectoryReq xmlns="http://www.idealdesk.com/ideal/messages/mer-acq/3.3.1" version="3.3.1"><createDateTimestamp>2013-06-22T06:54:03.000Z</createDateTimestamp><Merchant><merchantID>abc123</merchantID><subID>0</subID></Merchant></DirectoryReq>');

        $this->assertContains('<KeyName>4658929E6D3C8BC63BD82395110AF17EFABE45A3</KeyName>', $xml);
        $this->assertContains('<DigestValue>GeHYFkW9viTVipf7RtluuT6BK4/I3Wrx25Jm4/5/Hcc=</DigestValue>', $xml);
        $this->assertContains('<SignatureValue>gNliyG6MEhSNuCOhyI9NsCGetLdjKU4lH+/OqsD8bhWCDbiRXLzkTO1m+HPetBqkMBWrxagUHDGw5Bved5DmTxBzgckCyGKP7Q7F9M52vj2AuPe4WkiwVzjs7fk00QY4SYsCsasQ+133wneiLu6MpGyeqXLE7NAaR0ODWUpkfJ/Sz70YIzzEinkJRPiv4MNZBBmTci5jEs4RqHdV9JJcjeTbJulrvd7fekJUXDgyJdgotBV2eJ5yKFdyt5vG3gU6J/gEadQqF2q1BcuRSBb8tjiwJVVEWyLpfe2v98JCYIFZ69yI6kvN5fBQ952VuFKCFlcvMF47lLhHEsdg2FwZVg==</SignatureValue>', $xml);
    }

    public function testGenerateDigest()
    {
        $xml = new DOMDocument;
        $xml->loadXML('<DirectoryReq xmlns="http://www.idealdesk.com/ideal/messages/mer-acq/3.3.1" version="3.3.1"><createDateTimestamp>2013-06-22T06:54:03.000Z</createDateTimestamp><Merchant><merchantID>abc123</merchantID><subID>0</subID></Merchant></DirectoryReq>');
        $digest = $this->request->generateDigest($xml);

        $this->assertSame('GeHYFkW9viTVipf7RtluuT6BK4/I3Wrx25Jm4/5/Hcc=', $digest);
    }

    public function testGenerateDigestStripsSignature()
    {
        $xml = new DOMDocument;
        $xml->loadXML('<DirectoryReq xmlns="http://www.idealdesk.com/ideal/messages/mer-acq/3.3.1" version="3.3.1"><createDateTimestamp>2013-06-22T06:54:03.000Z</createDateTimestamp><Merchant><merchantID>abc123</merchantID><subID>0</subID></Merchant><Signature xmlns="http://www.w3.org/2000/09/xmldsig#"><Irrelevant /></Signature></DirectoryReq>');
        $digest = $this->request->generateDigest($xml);

        $this->assertSame('GeHYFkW9viTVipf7RtluuT6BK4/I3Wrx25Jm4/5/Hcc=', $digest);
    }

    public function testGenerateSignature()
    {
        $xml = new DOMDocument;
        $xml->loadXML('<SignedInfo xmlns="http://www.w3.org/2000/09/xmldsig#"><CanonicalizationMethod Algorithm="http://www.w3.org/2001/10/xml-exc-c14n#"/><SignatureMethod Algorithm="http://www.w3.org/2001/04/xmldsig-more#rsa-sha256"/><Reference URI=""><Transforms><Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature"/></Transforms><DigestMethod Algorithm="http://www.w3.org/2001/04/xmlenc#sha256"/><DigestValue>GeHYFkW9viTVipf7RtluuT6BK4/I3Wrx25Jm4/5/Hcc=</DigestValue></Reference></SignedInfo>');
        $signature = $this->request->generateSignature($xml);

        $this->assertSame('gNliyG6MEhSNuCOhyI9NsCGetLdjKU4lH+/OqsD8bhWCDbiRXLzkTO1m+HPetBqkMBWrxagUHDGw5Bved5DmTxBzgckCyGKP7Q7F9M52vj2AuPe4WkiwVzjs7fk00QY4SYsCsasQ+133wneiLu6MpGyeqXLE7NAaR0ODWUpkfJ/Sz70YIzzEinkJRPiv4MNZBBmTci5jEs4RqHdV9JJcjeTbJulrvd7fekJUXDgyJdgotBV2eJ5yKFdyt5vG3gU6J/gEadQqF2q1BcuRSBb8tjiwJVVEWyLpfe2v98JCYIFZ69yI6kvN5fBQ952VuFKCFlcvMF47lLhHEsdg2FwZVg==', $signature);
    }

    public function testGetPublicKeyDigest()
    {
        $digest = $this->request->getPublicKeyDigest();

        $this->assertSame('4658929E6D3C8BC63BD82395110AF17EFABE45A3', $digest);
    }
}
