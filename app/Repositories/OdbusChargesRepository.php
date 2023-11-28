<?php

namespace App\Repositories;
use App\Models\OdbusCharges;
use App\Models\BusOperator;
use App\Models\User;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
class OdbusChargesRepository 
{
    protected $odbusCharges;
    protected $user;
    public function __construct(OdbusCharges $odbusCharges, User $user )
    {
        $this->odbusCharges = $odbusCharges;
        $this->user = $user;
    }
    public function getData($request)
    {
        $paginate = $request['per_page'] ;
        $name = $request['name'] ;
        $userID = $request['userID'] ;
        $role_id = $request['role_id'] ;

        $data= OdbusCharges::with('user')->whereNotIn('status', [2])->orderBy('id','desc');

        if($paginate=='all') 
        {
            $paginate = Config::get('constants.ALL_RECORDS');
        }
        elseif ($paginate == null) 
        {
            $paginate = 10 ;
        }
         if($userID!= null && $role_id!= 1 )
          {
            $data = $data->Where('user_id', $userID);
          }

        if($name!=null)
        {
            $data = $data->WhereHas('user', function ($query) use ($name) {$query->where('name', 'like', '%' .$name . '%');})
                        ->orWhereHas('user', function ($query) use ($name) {$query->where('phone', 'like', '%' .$name . '%');})
                        ->orWhereHas('user', function ($query) use ($name) {$query->where('email', 'like', '%' .$name . '%');});                       
        }     
        $data=$data->paginate($paginate);
        
        $response = array(
             "count" => $data->count(), 
             "total" => $data->total(),
             "data" => $data
           );   
           return $response; 
    }

    public function getAll()
    {
        return $this->odbusCharges->whereNotIn('status', [2])->get();
    }
    
    public function getById($id)
    {
        return $this->odbusCharges
            ->where('id', $id)
            ->get();
    }
    
    public function getModel($data, OdbusCharges $odbusCharges)
    {

        $odbusCharges->user_id = $data['user_id'];
        $odbusCharges->payment_gateway_charges = $data['payment_gateway_charges'];
        $odbusCharges->email_sms_charges = $data['email_sms_charges'];
        $odbusCharges->odbus_gst_charges = $data['odbus_gst_charges'];
        $odbusCharges->customer_gst = $data['customer_gst'];
        $odbusCharges->bus_list_sequence = $data['busListingseq'];
        $odbusCharges->advance_days_show = $data['advance_days_show'];
        $odbusCharges->support_email = $data['support_email'];
        $odbusCharges->booking_email = $data['booking_email'];
        $odbusCharges->request_email = $data['request_email'];
        $odbusCharges->other_email = ($data['other_email']!='' && $data['other_email'] !='null') ? $data['other_email'] : null;

        $odbusCharges->has_issues = $data['has_issues'];
        $odbusCharges->maintenance = $data['maintenance'];
        
        $odbusCharges->mobile_no_1 = $data['mobile_no_1'];
        $odbusCharges->mobile_no_2 = $data['mobile_no_2'];
        $odbusCharges->mobile_no_3 = $data['mobile_no_3'];

        $odbusCharges->mobile_no_4 = ($data['mobile_no_4']!='' && $data['mobile_no_4'] !='null') ? $data['mobile_no_4'] : null;

        $odbusCharges->seo_script = ($data['seo_script']!='' && $data['seo_script'] !='null') ? $data['seo_script'] : null;

        $odbusCharges->google_verification_code = ($data['google_verification_code']!='' && $data['google_verification_code'] !='null') ? $data['google_verification_code'] : null;

        $odbusCharges->bing_verification_code = ($data['bing_verification_code']!='' && $data['bing_verification_code'] !='null') ? $data['bing_verification_code'] : null;

        $odbusCharges->pintrest_verification_code = ($data['pintrest_verification_code']!='' && $data['pintrest_verification_code'] !='null') ? $data['pintrest_verification_code'] : null;

        $odbusCharges->google_analytics = ($data['google_analytics']!='' && $data['google_analytics'] !='null') ? $data['google_analytics'] : null;

        $odbusCharges->fb_page_id = ($data['fb_page_id']!='' && $data['fb_page_id'] !='null') ? $data['fb_page_id'] : null;

        $odbusCharges->twitter_page_id = ($data['twitter_page_id']!='' && $data['twitter_page_id'] !='null') ? $data['twitter_page_id'] : null;

        $odbusCharges->no_script = ($data['no_script']!='' && $data['no_script'] !='null') ? $data['no_script'] : null;

        $odbusCharges->operator_slogan = ($data['operator_slogan']!='' && $data['operator_slogan'] !='null') ? $data['operator_slogan'] : null;

        $odbusCharges->operator_home_content =($data['operator_home_content']!='' && $data['operator_home_content'] !='null') ? $data['operator_home_content'] : null;

        $odbusCharges->created_by = $data['created_by'];

       // Log::info($odbusCharges);exit;


        return $odbusCharges;
    }
    /**
     * Save Settings
     *
     * @param $data
     * @return Settings
     */
    public function save($data)
    {  
        $duplicate_data = $this->odbusCharges
                               ->where('user_id',$data['user_id'])
                               ->where('status','!=',2)
                               ->get();
        if(count($duplicate_data)==0)
        {
            $odbusChargesObject = new $this->odbusCharges;
            $odbusCharges=$this->getModel($data,$odbusChargesObject);

            $favIcon_file = collect($data)->get('favicon_image');     
            if($favIcon_file!="" ){
                $filename  = $favIcon_file->getClientOriginalName();
                $extension = $favIcon_file->getClientOriginalExtension();
                $favIcon_picture   =  rand().'-'.$filename;
                $odbusCharges->favicon_image = $favIcon_picture;
                $favIcon_file->move(public_path('uploads/favIcon/'), $favIcon_picture);          
           }

           $logo_file = collect($data)->get('logo_image');     
            if(($logo_file)!=""){

                $filename  = $logo_file->getClientOriginalName();
                $extension = $logo_file->getClientOriginalExtension();
                $logo_picture   =  rand().'-'.$filename;

                $odbusCharges->logo_image = $logo_picture;
                $logo_file->move(public_path('uploads/logo/'), $logo_picture); 
           }

           $footer_logo = collect($data)->get('footer_logo');     
            if(($footer_logo)!=""){

                $filename  = $footer_logo->getClientOriginalName();
                $extension = $footer_logo->getClientOriginalExtension();
                $footer_picture   =  rand().'-'.$filename;

                $odbusCharges->footer_logo = $footer_picture;
                $footer_logo->move(public_path('uploads/logo/'), $footer_picture); 
           }


            $og_image = collect($data)->get('og_image');     
            if(($og_image)!=""){

                $filename  = $og_image->getClientOriginalName();
                $extension = $og_image->getClientOriginalExtension();
                $og_picture   =  rand().'-'.$filename;

                $odbusCharges->og_image = $og_picture;
                $og_image->move(public_path('uploads/og_image/'), $og_picture); 
           }


            $odbusCharges->save();
            return $odbusCharges;

        }
        else
        {
            return 'User already taken';
        }
        
    }
    /**
     * Update Settings
     *
     * @param $data
     * @return Settings
     */
    public function update($data)
    {
      
        $id = $data['id'] ;
        $duplicate_data = $this->odbusCharges
                               ->where('user_id',$data['user_id'])
                               ->where('id','!=',$id)
                               ->where('status','!=',2)
                               ->get();
        if(count($duplicate_data)==0)
        {
            $charges_detail  = $this->odbusCharges->where('id', $id)->get();
            $odbusCharges = $this->odbusCharges->find($id);

            $logo_file = collect($data)->get('logo_image');
            if($logo_file !="")
            {
                $odbusCharges=$this->getModel($data,$odbusCharges);
                $filename  = $logo_file->getClientOriginalName();
                $extension = $logo_file->getClientOriginalExtension();
                $logo_picture   =  rand().'-'.$filename;

                $odbusCharges->logo_image = $logo_picture;

                $logo_file->move(public_path('uploads/logo/'), $logo_picture);           

                if($charges_detail[0]->logo_image !='')
                {
                   $old_logo_path_consumer = public_path('uploads/logo/').$charges_detail[0]->logo_image;

                   if(File::exists($old_logo_path_consumer) )
                   {
                          unlink($old_logo_path_consumer);
                   }  
                }
                       
            }
            $favIcon_file = collect($data)->get('favicon_image'); 
            if ($favIcon_file!="" ) 
            {
                $filename  = $favIcon_file->getClientOriginalName();
                $extension = $favIcon_file->getClientOriginalExtension();
                $favIcon_picture   =  rand().'-'.$filename;

                $odbusCharges->favicon_image = $favIcon_picture;

                $favIcon_file->move(public_path('uploads/favIcon/'), $favIcon_picture);

                if($charges_detail[0]->favicon_image!='')
                {              
                   $old_favIcon_path_consumer = public_path('uploads/favIcon/').$charges_detail[0]->favicon_image;
                  
                    if(File::exists($old_favIcon_path_consumer))
                   {
                          unlink($old_favIcon_path_consumer);
                   }  
                }       
            }
            $footer_logo = collect($data)->get('footer_logo');
           
            if($footer_logo!=""){
                $filename  = $footer_logo->getClientOriginalName();
                $extension = $footer_logo->getClientOriginalExtension();
                $footer_picture   =  rand().'-'.$filename;

                $odbusCharges->footer_logo = $footer_picture;

                $footer_logo->move(public_path('uploads/logo/'), $footer_picture);

                if($charges_detail[0]->footer_logo!='')
                {              
                   $old_footIcon_path_consumer = public_path('uploads/logo/').$charges_detail[0]->footer_logo;
                  
                    if(File::exists($old_footIcon_path_consumer))
                   {
                          unlink($old_footIcon_path_consumer);
                   }  
                }       

            }

             $og_image_file = collect($data)->get('og_image'); 
            if ($og_image_file!="" ) 
            {
                $og_image_name  = $og_image_file->getClientOriginalName();
                $extension = $og_image_file->getClientOriginalExtension();
                $og_picture   =  rand().'-'.$og_image_name;

                $odbusCharges->og_image = $og_picture;

                $og_image_file->move(public_path('uploads/og_image/'), $og_picture);

                if($charges_detail[0]->og_image!='')
                {              
                   $old_og_path = public_path('uploads/og_image/').$charges_detail[0]->og_image;
                  
                    if(File::exists($old_og_path))
                   {
                          unlink($old_og_path);
                   }  
                }       
            }

            else
            {
                 $odbusCharges=$this->getModel($data,$odbusCharges);;
            }
            
            $odbusCharges->update();
            return $odbusCharges;
        }
        else
        {
            return 'User already taken';
        }
        
  
    }
    public function delete($id)
    {
        $charges = $this->odbusCharges->find($id);
        $charges->status = 2;
        $charges->update();

        return $charges;
    }
    public function changeStatus($id)
    {
        $charges = $this->odbusCharges->find($id);
        if($charges->status==0){
            $charges->status = 1;
        }elseif($charges->status==1){
            $charges->status = 0;
        }
        $charges->update();
        return $charges;
    }


}