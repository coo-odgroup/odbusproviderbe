<?php

namespace App\Repositories;

use App\Models\SiteMaster;

class SiteMasterRepository
{
    
    protected $siteMaster;

    
    public function __construct(SiteMaster $siteMaster)
    {
        $this->siteMaster = $siteMaster;
    }

    
    public function getAll()
    {
        return $this->siteMaster->get();
    }

    
    public function getById($id)
    {
        return $this->siteMaster ->where('id', $id)->get();
    }

    
    public function save($data)
    {
        $sitemaster = new $this->siteMaster;
        $sitemaster->site_live = $data['site_live'];
        $sitemaster->live_at = $data['live_at'];
        $sitemaster->extra_price = $data['extra_price'];
        $sitemaster->calender_days = $data['calender_days'];
        $sitemaster->service_charge = $data['service_charge'];
        $sitemaster->per_trasaction = $data['per_trasaction'];
        $sitemaster->max_seat_booked = $data['max_seat_booked'];
        $sitemaster->support_email = $data['support_email'];
        $sitemaster->booking_email = $data['booking_email'];
        $sitemaster->request_email = $data['request_email'];
        $sitemaster->other_email = $data['other_email'];
        $sitemaster->contact_no1 = $data['contact_no1'];
        $sitemaster->contact_no2 = $data['contact_no2'];
        $sitemaster->contact_no3 = $data['contact_no3'];
        $sitemaster->contact_no4 = $data['contact_no4'];
        $sitemaster->facebook_url = $data['facebook_url'];
        $sitemaster->twitter_url = $data['twitter_url'];
        $sitemaster->linkedin_url = $data['linkedin_url'];
        $sitemaster->instagram_url = $data['instagram_url'];
        $sitemaster->googleplus_url = $data['googleplus_url'];
        $sitemaster->min_fare_amt = $data['min_fare_amt'];
        $sitemaster->earned_pts = $data['earned_pts'];
        
        $sitemaster->save();

        return $sitemaster->fresh();
    }

    
    public function update($data, $id)
    {
        
        $sitemaster = $this->siteMaster->find($id);

        $sitemaster->site_live = $data['site_live'];
        $sitemaster->live_at = $data['live_at'];
        $sitemaster->extra_price = $data['extra_price'];
        $sitemaster->calender_days = $data['calender_days'];
        $sitemaster->service_charge = $data['service_charge'];
        $sitemaster->per_trasaction = $data['per_trasaction'];
        $sitemaster->max_seat_booked = $data['max_seat_booked'];
        $sitemaster->support_email = $data['support_email'];
        $sitemaster->booking_email = $data['booking_email'];
        $sitemaster->request_email = $data['request_email'];
        $sitemaster->other_email = $data['other_email'];
        $sitemaster->contact_no1 = $data['contact_no1'];
        $sitemaster->contact_no2 = $data['contact_no2'];
        $sitemaster->contact_no3 = $data['contact_no3'];
        $sitemaster->contact_no4 = $data['contact_no4'];
        $sitemaster->facebook_url = $data['facebook_url'];
        $sitemaster->twitter_url = $data['twitter_url'];
        $sitemaster->linkedin_url = $data['linkedin_url'];
        $sitemaster->instagram_url = $data['instagram_url'];
        $sitemaster->googleplus_url = $data['googleplus_url'];
        $sitemaster->min_fare_amt = $data['min_fare_amt'];
        $sitemaster->earned_pts = $data['earned_pts'];
        $sitemaster->update();

        return $sitemaster;
    }

    /**
     * Update Post
     *
     * @param $data
     * @return Post
     */
    public function delete($id)
    {
        
        $sitemaster = $this->siteMaster->find($id);
        $sitemaster->delete();

        return $sitemaster;
    }

}