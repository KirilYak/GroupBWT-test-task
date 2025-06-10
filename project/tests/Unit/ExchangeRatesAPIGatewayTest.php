<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Components\ExchangeRatesApi\Infrastructure\APIGateway;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ExchangeRatesAPIGatewayTest extends TestCase
{
    private string $baseUrl = 'https://test.com';
    private float $timeout = 2.0;
    private string $accessKey = 'test';

    public function testGetDataSuccessResponse(): void
    {
        $mockClient = $this->createMock(Client::class);
        $mockResponse = new Response(200, [], $this->mockSuccessResponseBody());

        $mockClient->method('request')
            ->with('GET')
            ->willReturn($mockResponse);

        $apiGateway = new APIGateway($this->baseUrl, $this->timeout, $this->accessKey);
        $this->setPrivateProperty($apiGateway, 'client', $mockClient);

        $response = $apiGateway->getRates();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->mockSuccessResponseBody(), $response->getBody()->getContents());
    }

    public function testGetRatesThrowsExceptionRequest(): void
    {
        $mockClient = $this->createMock(Client::class);
        $cardNumber = 'invalidCardDigitsData';

        $mockException = new \Exception('Request failed');

        $mockClient->method('request')
            ->with('GET')
            ->willThrowException($mockException);

        $apiGateway = new APIGateway($this->baseUrl, $this->timeout, $this->accessKey);
        $this->setPrivateProperty($apiGateway, 'client', $mockClient);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Request failed');

        $apiGateway->getRates($cardNumber);
    }

    private function mockSuccessResponseBody(): string
    {
        return '{
            "success":true,
            "timestamp":1749547155,
            "base":"EUR",
            "date":"2025-06-10",
            "rates":{
               "AED":4.190562,
               "EUR":1,
               "FJD":2.562895,
               "FKP":0.841205,
               "GBP":0.845695,
            }
        }';
    }

    private function setPrivateProperty(object $object, string $property, $value): void
    {
        $reflection = new \ReflectionClass($object);
        $propertyReflection = $reflection->getProperty($property);
        $propertyReflection->setAccessible(true);
        $propertyReflection->setValue($object, $value);
    }
}