<?php

namespace App\Repositories;

use App\Models\AppVersion;

class AppVersionRepository
{
    
    protected $appVersion;

    
    public function __construct(AppVersion $appVersion)
    {
        $this->appVersion = $appVersion;
    }

    
    public function getAll()
    {
        return $this->appVersion->whereNotIn('status', [2])->get();
    }

    
    public function getById($id)
    {
        return $this->appVersion ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $appversion = new $this->appVersion;
        $appversion->info = $data['info'];
        $appversion->name = $data['name'];
        $appversion->mandatory = $data['mandatory'];
        $appversion->version = $data['version'];
        $appversion->new_version_names = $data['new_version_names'];
        $appversion->new_version_codes = $data['new_version_codes'];
        $appversion->allowed_days = $data['allowed_days'];
        $appversion->has_issues = $data['has_issues'];
        $appversion->created_by = $data['created_by'];
        
        $appversion->save();

        return $appversion->fresh();
    }

    
    public function update($data, $id)
    {
        
        $appversion = $this->appVersion->find($id);

        $appversion->info = $data['info'];
        $appversion->name = $data['name'];
        $appversion->mandatory = $data['mandatory'];
        $appversion->version = $data['version'];
        $appversion->new_version_names = $data['new_version_names'];
        $appversion->new_version_codes = $data['new_version_codes'];
        $appversion->allowed_days = $data['allowed_days'];
        $appversion->has_issues = $data['has_issues'];
        $appversion->created_by = $data['created_by'];

        $appversion->update();

        return $appversion;
    }

    /**
     * Update Post
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        
        $appversion = $this->appVersion->find($id);
        $appversion->status = 2;
        $appversion->delete();

        return $appversion;
    }

}