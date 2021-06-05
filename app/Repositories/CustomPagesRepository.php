<?php

namespace App\Repositories;

use App\Models\CustomPages;

class CustomPagesRepository
{
    
    protected $customPages;

    
    public function __construct(CustomPages $customPages)
    {
        $this->customPages = $customPages;
    }

    
    public function getAll()
    {
        return $this->customPages->get();
    }

    
    public function getById($id)
    {
        return $this->customPages ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $custompages = new $this->customPages;
        $custompages->origin = $data['origin'];
        $custompages->type = $data['type'];
        $custompages->source_id = $data['source_id'];
        $custompages->destination_id = $data['destination_id'];
        $custompages->name = $data['name'];
        $custompages->url = $data['url'];
        $custompages->content = $data['content'];
        $custompages->meta_title = $data['meta_title'];
        $custompages->meta_keyword = $data['meta_keyword'];
        $custompages->meta_descriptiom = $data['meta_descriptiom'];
        $custompages->created_by = $data['created_by'];

        
        $custompages->save();

        return $custompages->fresh();
    }

    
    public function update($data, $id)
    {
        
        $custompages = $this->customPages->find($id);

        $custompages->origin = $data['origin'];
        $custompages->type = $data['type'];
        $custompages->source_id = $data['source_id'];
        $custompages->destination_id = $data['destination_id'];
        $custompages->name = $data['name'];
        $custompages->url = $data['url'];
        $custompages->content = $data['content'];
        $custompages->meta_title = $data['meta_title'];
        $custompages->meta_keyword = $data['meta_keyword'];
        $custompages->meta_descriptiom = $data['meta_descriptiom'];
        $custompages->created_by = $data['created_by'];

        $custompages->update();

        return $custompages;
    }

    /**
     * Update Post
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        
        $custompages = $this->customPages->find($id);
        $custompages->delete();

        return $custompages;
    }

}