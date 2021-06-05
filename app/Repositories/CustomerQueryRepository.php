<?php

namespace App\Repositories;

use App\Models\CustomerQuery;

class CustomerQueryRepository
{
    /**
     * @var CustomerQuery
     */
    protected $customerQuery;

    /**
     * CustomerQueryRepository constructor.
     *
     * @param CustomerQuery $customerQuery
     */
    public function __construct(CustomerQuery $customerQuery)
    {
        $this->customerQuery = $customerQuery;
    }

    /**
     * Get all customerQuery.
     *
     * @return CustomerQuery $customerQuery
     */
    public function getAll()
    {
        return $this->customerQuery->whereNotIn('status', [2])->get();
    }

    /**
     * Get customerQuery by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->customerQuery
            ->where('id', $id)
            ->get();
    }

    /**
     * Save customerQuery
     *
     * @param $data
     * @return CustomerQuery
     */
    public function save($data)
    {
        $post = new $this->customerQuery;

        $post->email = $data['email'];
        $post->phone = $data['phone'];
        $post->query_typ = $data['query_typ'];
        $post->data = $data['data'];
        $post->created_by = "Admin";

        $post->save();

        return $post->fresh();
    }

    /**
     * Update customerQuery
     *
     * @param $data
     * @return CustomerQuery
     */
    public function update($data, $id)
    {
        
        $post = $this->customerQuery->find($id);

        $post->email = $data['email'];
        $post->phone = $data['phone'];
        $post->query_typ = $data['query_typ'];
        $post->data = $data['data'];

        $post->update();

        return $post;
    }

    /**
     * Update customerQuery
     *
     * @param $data
     * @return CustomerQuery
     */
    public function delete($id)
    {
        
        $post = $this->customerQuery->find($id);
        $post->status = 2;
        $post->update();

        return $post;
    }

}