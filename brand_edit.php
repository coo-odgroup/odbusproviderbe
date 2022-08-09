<link href="<?=base_url()  ?>slimcrop/slim/slim.min.css" rel="stylesheet">
<script src="<?=base_url()  ?>slimcrop/slim/slim.kickstart.min.js"></script><!-- Content Wrapper. Contains page content -->

<style type="text/css">
  label
  { 
    font-weight: bolder !important;
  }
  .shadow
  {
    box-shadow: 1px 1px 7px #d6d6d6 !important;
  }
</style>
 <style type="text/css">
       #output_image
    {
     width:400px;
     height: 300px;

    }
     #output_image1
    {
     width:480px;
     height: 158px;

    }
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-9">
      <h2 class="text-capitalize"><b><?=str_replace("_"," ",$uri); ?></b></h2>    
    </div>
    <div class="col-lg-3 mt-4 fl_r" >
      <ol class="breadcrumb float-right" >
        <li class="breadcrumb-item">
          <a href="<?=base_url('admin/dashboard');?>"><small>Home</small></a>
        </li>
        <li class="breadcrumb-item">
          <small>Testimonial</small>
        </li>
        <li class="breadcrumb-item active">
          <strong class="text-capitalize"><?=str_replace("_"," ",$uri); ?></strong>
        </li>
      </ol>
    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">           
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox ">
        <div class="ibox-title">
          <a href="<?=base_url('admin/view_brand');?>" class="btn btn-sm btn-info"><i class="fa fa-folder-open-o"></i> View brand</a>
          <div class="ibox-tools">
              <a class="collapse-link">
                 <i class="fa fa-chevron-up"></i>
              </a>             
              <a class="close-link">
                 <i class="fa fa-times"></i>
              </a>
          </div>
        </div>       

        <div class="ibox-content">
          <?php  $this->load->view('admin/inc/flash.php') ;  ?>
          <form method="POST" enctype="multipart/form-data">
              <div class="row">
                  <div class="col-sm-12">
                    <div class="form-group">
                        <label class="font-normal">Brand Name <span class="text-danger">*</span></label>
                        <div class="input-group shadow">
                            <span class="input-group-addon"><i class="fa fa-header"></i></span>
                            <input type="text" class="form-control" name="brand_name" placeholder="Brand Name" required>
                        </div>
                    </div> 
                  </div>   

            </div>
              <div class="row mt-5">
                   <div class="form-group">
                              <div class="custom-file shadow">
                                <input type="file" class="custom-file-input" name="brand_image" accept="image/*" onchange="preview_image(event)">
                                <label class="custom-file-label" for="customFile">Choose Image file</label>
                              </div>
                              <span class="text-danger"><b>(Upload file Size 800X600)</b></span>
                            </div> 
                <!--   <div class="col-sm-12">
                     <label class=" control-label no-padding-right">Brand Image:&nbsp;<span class="text-danger">*</span></label>
                        <div class="input-group  col-md-12 col-sm-12">                   
                 

                          <input type="file"  name="brand_image" id="image-input" accept="image/jpeg, image/png, image/jpg">
                                                     
                        </div>
                        <p class="text-danger">(Upload file Size 800X600)</p> 
                    
                  </div> -->
              </div>
                <div class="form-group ">
                  <img id="output_image" class="shadow bg-light" />  
                </div> 

              <div class="hr-line-dashed"></div>
              <div class="form-group row">
                  <div class="col-sm-4 col-sm-offset-2">
                      <button class="btn btn-white btn-sm" type="submit">Cancel</button>
                      <button class="btn btn-primary btn-sm" type="submit">Add Data</button>
                  </div>
              </div>     
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script type='text/javascript'>
        function preview_image(event) 
        {
           var reader = new FileReader();
           reader.onload = function()
             {
                var output = document.getElementById('output_image');
                output.src = reader.result;
             }
           reader.readAsDataURL(event.target.files[0]);
        }
      </script>
