<?php

namespace App\Partners\Tmparts;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class ApiClient
{
    private const APU_URL = 'https://api.tmparts.ru/';

    private Client $client;

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $params = [
            'headers' => ['Authorization' => 'Bearer '.$apiKey],
        ];

        $this->client = new Client($params);
    }

    /**
     * @param string $urn
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    private function get(string $urn, array $params): array
    {
        $jsonResponse = $this->client->get(self::APU_URL.$urn, $params)->getBody()->getContents();

        return json_decode($jsonResponse, true);
    }

    /**
     * @param string[] $idList
     * @return array
     * @throws GuzzleException
     */
    public function getOrdersItems(array $idList): array
    {
        $params = [
            'ID_List' => array_map(fn($id) => ['ID' => $id], $idList),
        ];

        $data =  $this->get('api/OrderItems', $params);

        return $data['Items'] ?? [];
    }

    /**
     * @param string $brand
     * @param string $article
     * @return array
     * @throws GuzzleException
     */
    public function getStockByArticle(string $brand, string $article): array
    {
        $params = [
            'Brand' => $brand,
            'Article' => $article,
            'is_main_warehouse' => 0,
            'Contract' => '',
        ];

        return $this->get('/api/StockByArticleList', $params);
    }
}