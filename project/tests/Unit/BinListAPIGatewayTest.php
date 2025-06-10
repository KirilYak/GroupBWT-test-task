<?php declare(strict_types=1);

namespace App\Tests\Unit;

use App\Components\BinList\Infrastructure\APIGateway;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class BinListAPIGatewayTest extends TestCase
{
    private string $baseUrl = 'https://test.com';
    private float $timeout = 2.0;

    public function testGetDataSuccessResponse(): void
    {
        $mockClient = $this->createMock(Client::class);
        $cardNumber = '12345';
        $mockResponse = new Response(200, [], $this->mockSuccessResponseBody());

        $mockClient->method('request')
            ->with('GET', $cardNumber)
            ->willReturn($mockResponse);

        $apiGateway = new APIGateway($this->baseUrl, $this->timeout);
        $this->setPrivateProperty($apiGateway, 'client', $mockClient);

        $response = $apiGateway->getData($cardNumber);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($this->mockSuccessResponseBody(), $response->getBody()->getContents());
    }

    public function testGetDataThrowsExceptionRequest(): void
    {
        $mockClient = $this->createMock(Client::class);
        $cardNumber = 'invalidCardDigitsData';

        $mockException = new \Exception('Request failed');

        $mockClient->method('request')
            ->with('GET', $cardNumber)
            ->willThrowException($mockException);

        $apiGateway = new APIGateway($this->baseUrl, $this->timeout);
        $this->setPrivateProperty($apiGateway, 'client', $mockClient);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Request failed');

        $apiGateway->getData($cardNumber);
    }

    private function mockSuccessResponseBody(): string
    {
        return '
        {
            "number": {},
            "scheme": "visa",
            "type": "debit",
            "brand": "Visa Classic/Dankort",
            "country": {
              "numeric": "208",
              "alpha2": "DK",
              "name": "Denmark",
              "emoji": "🇩🇰",
              "currency": "DKK",
              "latitude": 56,
              "longitude": 10
            },
            "bank": {
              "name": "Jyske Bank A/S"
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