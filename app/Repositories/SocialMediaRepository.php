<?php

namespace App\Repositories;

use App\Models\SocialMedia;


use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Config;


/*Priyadarshi to Review*/
class SocialMediaRepository
{
    
    protected $socialMedia;
    
    public function __construct(SocialMedia $socialMedia)
    {
        $this->socialMedia = $socialMedia; 
    }   

   
    public function getAll()
    {     
        return $data = $this->socialMedia->get();
    }

    public function updateData($request)
    {
         $social = $this->socialMedia->find(1);
         $social->facebook_link = $request['facebook_link'];
         $social->twitter_link = $request['twitter_link'];
         $social->instagram_link = $request['instagram_link'];
         $social->googleplus_link = $request['googleplus_link'];
         $social->linkedin_link = $request['linkedin_link'];   
         $social->update();
         // Log::info($social);

        return $social;

      


    }


}