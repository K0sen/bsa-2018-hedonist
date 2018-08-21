<?php

namespace Hedonist\Http\Controllers\Api\Place;

use Hedonist\Exceptions\DomainException;
use Hedonist\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;
use Hedonist\Http\Requests\Place\Rate\{
    SetRatingHttpRequest,
    GetRatingHttpRequest
};
use Hedonist\Actions\Place\GetPlaceRating\{
    GetPlaceRatingAction,
    GetPlaceRatingRequest
};
use Hedonist\Actions\Place\SetPlaceRating\{
    SetPlaceRatingAction,
    SetPlaceRatingRequest
};
use Hedonist\Actions\Place\GetPlaceRatingAverage\{
    GetPlaceRatingAvgRequest,
    GetPlaceRatingAvgAction
};

class PlaceRatingController extends ApiController
{
    private $getRatingAction;
    private $setRatingAction;
    private $getPlaceRatingAvgAction;

    public function __construct(
        GetPlaceRatingAction $getRatingAction,
        SetPlaceRatingAction $setRatingAction,
        GetPlaceRatingAvgAction $getPlaceRateAvgAction
    ) {
        $this->getRatingAction = $getRatingAction;
        $this->setRatingAction = $setRatingAction;
        $this->getPlaceRatingAvgAction = $getPlaceRateAvgAction;
    }

    public function setRating(SetRatingHttpRequest $httpRequest) : JsonResponse
    {
        try {
            $setPlaceRatingResponse = $this->setRatingAction->execute(
                new SetPlaceRatingRequest(
                    $httpRequest->rating,
                    $httpRequest->id,
                    null,
                    $httpRequest->place_id
                )
            );
        } catch (DomainException $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }

        return $this->successResponse([
            'id' => $setPlaceRatingResponse->getId(),
            'user_id' => $setPlaceRatingResponse->getUserId(),
            'place_id' => $setPlaceRatingResponse->getPlaceId(),
            'rating' => $setPlaceRatingResponse->getRatingValue(),
            'rating_avg' => $setPlaceRatingResponse->getRatingAvg(),
            'rating_count' => $setPlaceRatingResponse->getRatingCount()
        ], 201);
    }

    public function getRating(GetRatingHttpRequest $httpRequest, $id = null) : JsonResponse
    {
        try {
            $getPlaceRatingResponse = $this->getRatingAction->execute(
                new GetPlaceRatingRequest(
                    $id,
                    $httpRequest->user_id,
                    $httpRequest->place_id,
                    $httpRequest->rating
                )
            );
        } catch (DomainException $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }

        return $this->successResponse([
            'id' => $getPlaceRatingResponse->getId(),
            'user_id' => $getPlaceRatingResponse->getUserId(),
            'place_id' => $getPlaceRatingResponse->getPlaceId(),
            'rating' => $getPlaceRatingResponse->getRatingValue()
        ], 201);
    }

    public function getPlaceRatingAvg($placeId) : JsonResponse
    {
        try {
            $getPlaceRatingAvgResponse = $this->getPlaceRatingAvgAction->execute(
                new GetPlaceRatingAvgRequest(
                    $placeId
                )
            );
        } catch (DomainException $ex) {
            return $this->errorResponse($ex->getMessage(), 400);
        }

        return $this->successResponse([
            'place_id' => $getPlaceRatingAvgResponse->getPlaceId(),
            'rating' => $getPlaceRatingAvgResponse->getRatingAvgValue()
        ], 201);
    }
}
