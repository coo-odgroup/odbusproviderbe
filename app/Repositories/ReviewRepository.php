<?php

namespace App\Repositories;

use App\Models\Review;
use App\Models\User;

class ReviewRepository
{
    /**
     * @var Review
     */
    protected $review;
    protected $user;

    /**
     * ReviewRepository constructor.
     *
     * @param Review $review
     */
    public function __construct(Review $review, User $user)
    {
        $this->review = $review;
        $this->user = $user;
    }

    /**
     * Get all review.
     *
     * @return Review $review
     */
    public function getAll()
    {
        return $this->review->whereNotIn('status', [2])->get();
    }

    /**
     * Get review by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->review
            ->where('id', $id)
            ->get();
    }
    public function getreviewBid($bid)
    {
        return $this->review::addSelect(['cname' => $this->user::select('first_name')
        ->whereColumn('Review.customer_id', 'id')])
        // ->addSelect(['Average Rating' => $this->review->avg('rating_overall')])
        ->whereNotIn('status', [2])
        ->where('bus_id', $bid)
        ->orderBy('id', 'desc')
        ->limit(10)
        ->get();

        // return $this->review->avg('rating_overall');
    }
    /**
     * Save review
     *
     * @param $data
     * @return Review
     */
    public function save($data)
    {
        $post = new $this->review;

        $post->pnr = $data['pnr'];
        $post->bus_id  = $data['bus_id'];
        $post->customer_id = $data['customer_id'];
        $post->reference_key = $data['reference_key'];
        $post->rating_overall = $data['rating_overall'];
        $post->rating_comfort = $data['rating_comfort'];
        $post->rating_clean = $data['rating_clean'];
        $post->rating_behavior = $data['rating_behavior'];
        $post->rating_timing = $data['rating_timing'];
        $post->comments = $data['comments'];
        // $post->created_date = date('Y-m-d H:i:s');
        $post->created_by = "Admin";

        $post->save();

        return $post->fresh();
    }

    /**
     * Update review
     *
     * @param $data
     * @return Review
     */
    public function update($data, $id)
    {
        
        $post = $this->review->find($id);

        $post->pnr = $data['pnr'];
        $post->bus_id  = $data['bus_id'];
        $post->customer_id = $data['customer_id'];
        $post->reference_key = $data['reference_key'];
        $post->rating_overall = $data['rating_overall'];
        $post->rating_comfort = $data['rating_comfort'];
        $post->rating_clean = $data['rating_clean'];
        $post->rating_behavior = $data['rating_behavior'];
        $post->rating_timing = $data['rating_timing'];
        $post->comments = $data['comments'];
        $post->update();

        return $post;
    }

    /**
     * Update review
     *
     * @param $data
     * @return Review
     */
    public function delete($id)
    {
        
        $post = $this->review->find($id);
        $post->status = 2;
        $post->update();

        return $post;
    }

}