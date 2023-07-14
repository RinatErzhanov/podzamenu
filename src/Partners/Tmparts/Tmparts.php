<?php

namespace App\Partners\Tmparts;

use GuzzleHttp\Exception\GuzzleException;

class Tmparts
{
    private ApiClient $apiClient;

    public function __construct(string $apiKey)
    {
        $this->apiClient = new ApiClient($apiKey);
    }

    /**
     * @param string $article
     * @return array
     * @throws GuzzleException
     */
    public function getOrdersInfo(string $article): array
    {
        $ordersItems = $this->apiClient->getOrdersItems([$article]);

        list('Brand' => $brand, 'Article_Name' => $name, 'Quantity' => $quantity, 'Price' => $price) = current($ordersItems);

        $stockByArticleList = $this->apiClient->getStockByArticle($article, $brand);
        $warehouseOffers = $stockByArticleList['warehouse_offers'];
        // в тз не указано какой склад выбираем(или все), выберем первое попавшееся
        $warehouse = current($warehouseOffers);
        list('delivery_period' => $deliveryPeriodInDays, 'id' => $vendorId, 'branch_code' => $warehouseAlias) = $warehouse;

        return [
            'brand' => $brand,
            'article' => $article,
            'name' => $name,
            'quantity' => $quantity,
            'price' => $price * 1000, // в копейках
            'delivery_duration' => $deliveryPeriodInDays * 24 * 60 * 60, // в секундах
            'vendorId' => $vendorId,
            'warehouseAlias' => $warehouseAlias,
        ];

    }
}