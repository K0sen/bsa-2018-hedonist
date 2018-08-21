<?php

namespace Hedonist\Http\Controllers\Api\User\UserList;

use Hedonist\Http\Controllers\Api\ApiController;
use Hedonist\Actions\UserList\Places\{
    AttachPlaceAction,
    AttachPlaceRequest
};
use Hedonist\Http\Requests\UserList\AttachPlaceHttpRequest;
use Illuminate\Http\JsonResponse;
use Hedonist\Exceptions\DomainException;

class UserListPlaceController extends ApiController
{
    public $attachPlaceAction;

    public function __construct(AttachPlaceAction $attachPlaceAction)
    {
        $this->attachPlaceAction = $attachPlaceAction;
    }

    public function attachPlace(int $listId, AttachPlaceHttpRequest $httpRequest): JsonResponse
    {
        try {
            $response = $this->attachPlaceAction->execute(
                new AttachPlaceRequest($listId, $httpRequest->id)
            );
        } catch (DomainException $e) {
            return $this->errorResponse($e->getMessage(), 400);
        }
        return $this->emptyResponse(201);
    }
}
