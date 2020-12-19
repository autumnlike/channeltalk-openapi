<?php
declare(strict_types=1);

namespace Lib\Client;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\ResponseInterface;

/**
 * Base Class
 */
class Base
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $accessKey;

    /**
     * @var string
     */
    private $accessSecret;

    /**
     * construct
     */
    public function __construct()
    {
        $this->client = new Client();

        // .env での設定が必須
        $this->accessKey = $_ENV['CHANNEL_ACCESS_KEY'];
        $this->accessSecret = $_ENV['CHANNEL_ACCESS_SECRET'];
    }

    /**
     * リクエストヘッダー
     */
    protected function getHeaders(): array
    {
        return [
            'accept' => 'application/json',
            'x-access-key' => $this->accessKey,
            'x-access-secret' => $this->accessSecret,
        ];
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array $option
     * @return array
     * @throws FreenanceException
     */
    protected function send(string $method, string $uri, array $option)
    {
        try {

            $response = $this->client->request($method, $uri, $option);
            $responseBody = json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            // FIXME エラーレスポンスを正しく返したい
            throw new \Exception('failed to send:' . $e->getMessage() . 'status: ' . $e->getCode());
        }
        return $responseBody;
    }
}
