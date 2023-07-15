<?php

namespace App\Controller;

use App\Partners\Tmparts\Tmparts;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TmpartsConntroller extends AbstractController
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function getOrdersInfo(Request $request): jsonResponse
    {
        $apiKey = $request->get('Api-key');
        $article = $request->get('article');

        $orderInfo = (new Tmparts($apiKey))->getOrdersInfo($article);

        return new JsonResponse($orderInfo);
    }

}