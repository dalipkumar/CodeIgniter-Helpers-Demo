# CodeIgniter Helpers Demo
### Some Important codeigniter helpers

- Clock Timer helper
> In clock timer helper we have < run_clock() > function for running current clock.
- Current time helper
> In current time helper we have < currtimedate($type=NULL) > function for current date and time. < $type > param for getting special index from array("datetime","fulldate","time","date","month","year","hours","minutes","seconds","datetimestr").
> In current time helper we have < get_month_in_alpha($num=0,$type=true) > function for get month in alpha from numeric. < $num > param is numerical number of month from 1-12 and < $type > param is boolean, true return full month name and false return short month name.
> In current time helper we have < get_month_in_num($str='') > function for get month in numeric from alpha. < $str > param is string full or short name of month.
- File upload helper
> In file upload helper we have < image_upload_function($field_name="",$upload_path='./',$width=0,$height=0,$max_width=800,$max_height=800,$max_size=500,$maintain_ratio=TRUE,$encrypt_name=TRUE,$create_thumb=FALSE,$thumb_width=280,$thumb_height=160,$thumb_maintain_ration=TRUE,$is_watermark=FALSE) > function with some important parameters like size,watermark and thumb related.
> If you have watermark then set < WATERMARK_PATH > constant in constant file in config folder with watermark image path
- Pagination helper
> In pagination helper we have < pagination($tablename,$page=1,$condition=array(),$orderby=NULL,$totalrecords=5,$totalbottompagenum=5,$joincheck=false,$joinquery='') > function with some important parameters.
> return result array('limit'=>result,'startindex'=>result,'totalrecordsfound'=>result,'max_page_num'=>result,'curr_page_num'=>result,'prev'=>result,'next'=>result,'last'=>result,'first'=>result,'pagenums'=>result,'curr_num_of_data'=>result,'data_exists'=>result,'feteched_data'=>records;
- Search helper
> In search helper we have some important fuctions related to easy search functionalty for your site.
- SQL Queries helper
> In SQL Queries helper we have some important fuctions related to easy sql functionalty for your site.
