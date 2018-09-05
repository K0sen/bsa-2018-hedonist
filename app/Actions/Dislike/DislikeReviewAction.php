<?php

namespace Hedonist\Actions\Dislike;

use Hedonist\Exceptions\Review\ReviewNotFoundException;
use Hedonist\Repositories\Like\{LikeRepositoryInterface,LikeReviewCriteria};
use Hedonist\Repositories\Dislike\{DislikeRepositoryInterface,DislikeReviewCriteria};
use Hedonist\Entities\Review\Review;
use Hedonist\Entities\Dislike\Dislike;
use Hedonist\Repositories\Review\ReviewRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Hedonist\Events\Attitude\AttitudeSetEvent;

class DislikeReviewAction
{
    private $likeRepository;
    private $dislikeRepository;
    private $reviewRepository;

    public function __construct(
        LikeRepositoryInterface $likeRepository,
        DislikeRepositoryInterface $dislikeRepository,
        ReviewRepositoryInterface $reviewRepository
    ) {
        $this->likeRepository = $likeRepository;
        $this->dislikeRepository = $dislikeRepository;
        $this->reviewRepository = $reviewRepository;
    }

    public function execute(DislikeReviewRequest $request): DislikeReviewResponse
    {
        $reviewId = $request->getReviewId();
        $review = $this->reviewRepository->getById($reviewId);
        if (empty($review)) {
            throw new ReviewNotFoundException();
        }
        $userId = Auth::id();

        $like = $this->likeRepository->findByCriteria(
            new LikeReviewCriteria($reviewId, $userId)
        )->first();

        $dislike = $this->dislikeRepository->findByCriteria(
            new DislikeReviewCriteria($reviewId, $userId)
        )->first();
        
        if ($like) {
            broadcast(new AttitudeSetEvent(
                $reviewId,
                AttitudeSetEvent::LIKE_REMOVED
            ))->toOthers();

            $this->likeRepository->deleteById($like->id);
        }
        if (empty($dislike)) {
            broadcast(new AttitudeSetEvent(
                $reviewId,
                AttitudeSetEvent::DISLIKE_ADDED
            ))->toOthers();

            $dislike = new Dislike([
                'dislikeable_id' => $reviewId,
                'dislikeable_type' => Review::class,
                'user_id' => $userId
            ]);
            $this->dislikeRepository->save($dislike);
        } else {
            broadcast(new AttitudeSetEvent(
                $reviewId,
                AttitudeSetEvent::DISLIKE_REMOVED
            ))->toOthers();

            $this->dislikeRepository->deleteById($dislike->id);
        }
        
        return new DislikeReviewResponse();
    }
}
