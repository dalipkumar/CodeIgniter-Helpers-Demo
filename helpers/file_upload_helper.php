<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('image_upload_function'))
{
	function image_upload_function($field_name="",$upload_path='./',$width=0,$height=0,$max_width=800,$max_height=800,$max_size=500,$maintain_ratio=TRUE,$encrypt_name=TRUE,$create_thumb=FALSE,$thumb_width=280,$thumb_height=160,$thumb_maintain_ration=TRUE,$is_watermark=FALSE)
	{
		$ci =& get_instance(); 
        
        $return_arr = array(true,"error_msg"=>"Undefined error!");
        
        $config['upload_path'] = $upload_path;
		$config['allowed_types'] = 'gif|jpg|png|jpeg';
		$config['max_size']	= $max_size;
		$config['max_width']  = $max_width;
		$config['max_height']  = $max_height;
        $config['encrypt_name'] = $encrypt_name;
        $config['remove_spaces'] = TRUE;
        
        $ci->load->library('upload', $config);
        if (!$ci->upload->do_upload($field_name))
		{
			$return_arr["error_msg"] = $ci->upload->display_errors();
		}
		else
		{
            
            $upload_data = $ci->upload->data();
            
            
            //create thumbnail in same folder with thumb_ prefix
            if($create_thumb)
            {
                create_image_thumb($upload_data["full_path"],$thumb_width,$thumb_height,$thumb_maintain_ration);
            }
            //End
            
            if($is_watermark)
            {
                create_watermark($upload_data["full_path"]);
            }           
            
            
            $return_arr = array(false,'upload_data' => $upload_data);
		}
        
        return $return_arr;
	}
}

if(! function_exists('create_watermark'))
{
    function create_watermark($src="")
    {
        if((strlen(trim($src)) > 0))
        {
            //echo $src;
            $dir = dirname($src);
            $file_arr = explode("/",$src);
            //$file_name = "/thumb_".$file_arr[count($file_arr) - 1];
            
            $ci =& get_instance();
                
                
            $config['image_library'] = 'gd2';
            $config['source_image']	= $src;
            $config['new_image']        = $src;
            $config['wm_overlay_path'] = WATERMARK_PATH; /** give here watermark image path **/
            $config['wm_type'] = 'overlay';
            $config['wm_vrt_alignment'] = 'middle'; /** align watermark on image (top, middle, bottom) **/
            $config['wm_hor_alignment'] = 'center'; /** align watermark on image (left, center, right) **/              
            
            $ci->image_lib->initialize($config);
            $ci->image_lib->watermark();
            $ci->image_lib->clear();
        }
    }
}

if(! function_exists('create_image_thumb'))
{
    function create_image_thumb($src="",$height=0,$width=0,$maintain_ratio=false)
    {
        if((strlen(trim($src)) > 0) && ($height > 0) && ($width > 0))
        {
            $dir = dirname($src);
            $file_arr = explode("/",$src);
            $file_name = "/thumb_".$file_arr[count($file_arr) - 1];
            
            if(!file_exists($dir.$file_name))
            {
                $ci =& get_instance();
                
                
                $config['image_library'] = 'gd2';
                $config['source_image']	= $src;
                $config['new_image']        = $dir.$file_name;
                $config['maintain_ratio'] = $maintain_ratio;
                $config['width']	= $width;
                $config['height']	= $height;                
                $ci->image_lib->initialize($config);
                $ci->image_lib->resize();
                $ci->image_lib->clear();
            }
        }
    }
}


/* End of file file_upload_helper.php */
/* Location: ./application/helpers/file_upload_helper.php */