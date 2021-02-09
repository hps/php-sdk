<?php

namespace Gateways\GpApiConnector;

use GlobalPayments\Api\Entities\Enums\Environment;
use GlobalPayments\Api\Entities\Enums\GpApi\Channels;
use GlobalPayments\Api\Entities\Enums\TransactionStatus;
use GlobalPayments\Api\PaymentMethods\EBTCardData;
use GlobalPayments\Api\PaymentMethods\EBTTrackData;
use GlobalPayments\Api\ServiceConfigs\Gateways\GpApiConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Tests\Data\TestCards;
use GlobalPayments\Api\Utils\AccessTokenInfo;
use PHPUnit\Framework\TestCase;

class EbtCardTest extends TestCase
{
    private $card;

    public function setup()
    {
        ServicesContainer::configureService($this->setUpConfig());
        $this->card = TestCards::asEBTManual(TestCards::visaManual(), '32539F50C245A6A93D123412324000AA');
    }

    public function setUpConfig()
    {
        $config = new GpApiConfig();
        $accessTokenInfo = new AccessTokenInfo();
        //this is gpapistuff stuff
        $config->setAppId('VuKlC2n1cr5LZ8fzLUQhA7UObVks6tFF');
        $config->setAppKey('NmGM0kg92z2gA7Og');
        $config->environment = Environment::TEST;
        $config->setAccessTokenInfo($accessTokenInfo);
        $config->setChannel(Channels::CardPresent);

        return $config;
    }

    public function testEbtSale()
    {
        $response = $this->card->charge(10)
            ->withCurrency('USD')
            ->execute();

        $this->assertNotNull($response);
        $this->assertEquals('SUCCESS', $response->responseCode);
        $this->assertEquals(TransactionStatus::CAPTURED, $response->responseMessage);
    }

    public function testEbtRefund()
    {
        $response = $this->card->refund(10)
            ->withCurrency('USD')
            ->execute();

        $this->assertNotNull($response);
        $this->assertEquals('SUCCESS', $response->responseCode);
        $this->assertEquals(TransactionStatus::CAPTURED, $response->responseMessage);
    }

}