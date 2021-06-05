<?php

namespace App\Repositories;

use App\Models\AppDownload;

class AppDownloadRepository
{
    /**
     * @var AppDownload
     */
    protected $appDownload;

    /**
     * AppDownloadRepository constructor.
     *
     * @param AppDownload $appDownload
     */
    public function __construct(AppDownload $appDownload)
    {
        $this->appDownload = $appDownload;
    }

    /**
     * Get all appDownload.
     *
     * @return AppDownload $appDownload
     */
    public function getAll()
    {
        return $this->appDownload->whereNotIn('status', [2])->get();
    }

    /**
     * Get appDownload by id
     *
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->appDownload
            ->where('id', $id)
            ->get();
    }

    /**
     * Save appDownload
     *
     * @param $data
     * @return AppDownload
     */
    public function save($data)
    {
        $post = new $this->appDownload;

        $post->mobileno = $data['mobileno'];
        // $post->created_date = date('Y-m-d H:i:s');
        $post->created_by = "Admin";

        $post->save();

        return $post->fresh();
    }

    /**
     * Update appDownload
     *
     * @param $data
     * @return AppDownload
     */
    public function update($data, $id)
    {
        
        $post = $this->appDownload->find($id);

        $post->mobileno = $data['mobileno'];

        $post->update();

        return $post;
    }

    /**
     * Update appDownload
     *
     * @param $data
     * @return AppDownload
     */
    public function delete($id)
    {
        
        $post = $this->appDownload->find($id);
        $post->status = 2;
        $post->update();

        return $post;
    }

}