<?php

namespace App\Repositories;

use App\Models\CustomerQueryCategory;
use App\Models\CustomerQueryCategoryIssues;
use Illuminate\Support\Str;


class CustomerQueryCategoryRepository
{
    /**
     * @var CustomerQueryCategory
     */
    protected $customerQueryCategory;
    protected $customerQueryCategoryIssues;

    /**
     * PostRepository constructor.
     *
     * @param Post $BusType
     */
    public function __construct(CustomerQueryCategory $customerQueryCategory, CustomerQueryCategoryIssues $customerQueryCategoryIssues)
    {
        $this->customerQueryCategory = $customerQueryCategory;
        $this->customerQueryCategoryIssues = $customerQueryCategoryIssues;
    }

    
    public function getAll()
    {
         
        return $this->customerQueryCategory::with('customerQueryCategoryIssues')->get();
    }

    
    public function getById($id)
    {
        return $this->customerQueryCategory::with('customerQueryCategoryIssues')->where('id', $id)->get();
    }

    
    
    public function save($data)
    {
        $user = new $this->customerQueryCategory;
        $user->name= $data['name'];
        $user->created_by= "Admin";
        $user->save();

        foreach($data['customerQueryCategoryIssues'] as $user_code){
          $locdetRecord = new $this->customerQueryCategoryIssues;   
          $locdetRecord->customer_query_category_id = $user->id;     
          $locdetRecord->name =  $user_code['name'];
          $locdetRecord->created_by =  "Admin";
          $user->customerQueryCategoryIssues[] = $locdetRecord;

        }   
        $user->push();
        return $user->fresh();
    }

    public function update($data, $id)
    {
        
       

    }
    public function delete($id)
    {
        
        /*$customerQueryCategoryIssues = $this->customerQueryCategoryIssues->find($id);
        $customerQueryCategoryIssues->delete();
        return $customerQueryCategoryIssues;
        $user */
    }


   






}