<?php

namespace App\Repositories;

use App\Models\Reason;

class ReasonRepository
{
    
    protected $reason;

    
    public function __construct(Reason $reason)
    {
        $this->reason = $reason;
    }

    
    public function getAll()
    {
        return $this->reason->get();
    }

    
    public function getById($id)
    {
        return $this->reason ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $reasons = new $this->reason;
        $reasons->name = $data['name'];
        $reasons->created_by = $data['created_by'];
        
        $reasons->save();

        return $reasons->fresh();
    }

    
    public function update($data, $id)
    {
        
        $reasons = $this->reason->find($id);

        // $boardingdroping = new $this->boardingDroping; 
        $reasons->name = $data['name'];
        $reasons->created_by = $data['created_by'];
        $reasons->update();

        return $reasons;
    }

    /**
     * Update Post
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        
        $reasons = $this->reason->find($id);
        $reasons->delete();

        return $reasons;
    }

}