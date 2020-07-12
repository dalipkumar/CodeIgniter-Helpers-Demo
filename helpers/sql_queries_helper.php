<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('gen_fetchdbdata'))
{
	//fetch data from database
	function gen_fetchdbdata($str='',$fullArr=true)
	{
		$specialIndex='resultArr';
		$returArr = array(false,NULL,NULL,"num"=>NULL,"resultArr"=>NULL);
		$temp = false;
		if(strlen(trim($str)) > 0)
		{
			$ci =& get_instance();
			$query = $ci->db->query($str);
			if(@$query->num_rows() > 0)
			{
				$numdata = $query->num_rows();
				$result = $query->result_array();
				$returArr = array(true,$numdata,$result,"num"=>$numdata,"resultArr"=>$result);
				$temp = true;
			}
			
			$query->free_result();
		}
		
		return (($fullArr == true)?$returArr:array($temp,$specialIndex=>$returArr[$specialIndex]));
	}
	//End
}

if(! function_exists('gen_activefetchdbdata'))
{
	//fetch data from database
	function gen_activefetchdbdata($tablename=NULL,$param=array(),$orderby=NULL,$limit=0,$startindex=0,$fullArr=true,$colnum=NULL)
	{
		$specialIndex='resultArr';
		$returArr = array(false,NULL,NULL,"num"=>NULL,"resultArr"=>NULL);
		$temp = false;
		if(($tablename != NULL) && (strlen(trim($tablename)) > 0))
		{
			$ci =& get_instance();
			((count($param) > 0)?$ci->db->where($param):'');
			//$ci->db->order_by('title desc, name asc'); 
			((($colnum != NULL) && ($colnum != ''))?$ci->db->select($colnum):'');
			((($orderby != NULL) && ($orderby != ''))?$ci->db->order_by($orderby):'');
			(($limit > 0)?(($startindex > 0)?$ci->db->limit($limit, $startindex):$ci->db->limit($limit)):'');
			$query = $query = $ci->db->get($tablename);
			
			if(@$query->num_rows() > 0)
			{
				$numdata = $query->num_rows();
				$result = $query->result_array();
				$returArr = array(true,$numdata,$result,"num"=>$numdata,"resultArr"=>$result);
				$temp = true;
			}
			
			$query->free_result();
		}
		
		return (($fullArr == true)?$returArr:array($temp,$specialIndex=>$returArr[$specialIndex]));
	}
	//End
}


if(! function_exists('gen_numrows'))
{
	//count number of records according query
	function gen_numrows($str='')
	{
		$returnnum = 0;
		
		if(strlen(trim($str)) > 0)
		{
			$ci =& get_instance();
			$query = $ci->db->query($str);
			
			if(@$query->num_rows() > 0)
			{
				$returnnum = $query->num_rows();
			}
			
			$query->free_result();
		}
		
		return $returnnum;
	}
	//End
}

if(!function_exists('gen_activenumrows'))
{
	//count number of records according query
	function gen_activenumrows($tablename=NULL, $param=array(), $orderby=NULL)
	{
		$returnnum = 0;
		
		if($tablename != NULL)
		{
			$ci =& get_instance();
			((count($param)>0)?$ci->db->where($param):'');
			((($orderby != NULL) && ($orderby != ''))?$ci->db->order_by($orderby):'');
			$returnnum = $ci->db->count_all_results($tablename);
		}
		
		return $returnnum;
	}
	//End
}

if(! function_exists('gen_insertquery'))
{
	//insert query
	function gen_insertquery($tablename,$dataArr,$get_id=false)
	{
		if(count($dataArr) && (strlen($tablename) > 0))
		{
			$ci =& get_instance();
			$query = $ci->db->insert($tablename, $dataArr);
			$id=$ci->db->insert_id();
			if(strlen($ci->db->_error_message()) <= 0)
			{
				
				return (($get_id)?array(true,$id):true);
			}
			$query->free_result();
		}
		
		return (($get_id)?array(false,0):false);
	}
	//End
}

if(! function_exists('gen_updatequery'))
{
	//update query
	function gen_updatequery($tablename="",$dataArr=array(),$condition=array(),$get_id=false)
	{
		if(count($dataArr) && (strlen($tablename) > 0))
		{
			//print_r($dataArr); 
			//echo $tablename.'<br>'; print_r($condition);
			$ci =& get_instance();
			$query = $ci->db->update($tablename,$dataArr,$condition);
			$rows=$ci->db->affected_rows();
			//echo $ci->db->last_query(); 
			if(strlen($ci->db->_error_message()) <= 0)
			{
				return (($get_id)?array(true):true);
			}
			$query->free_result();
		}
		
		return (($get_id)?array(false):false);
	}
	//End
}

if(! function_exists("gen_deletefun"))
{
	//Delete function
	function gen_deletefun($tablename=NULL,$param=array())
	{
		if(($tablename != NULL) && (strlen(trim($tablename)) > 0))
		{
			if(count($param) > 0)
			{
				$ci =& get_instance();
				$query = $ci->db->delete($tablename, $param); 
				if(strlen($ci->db->_error_message()) <= 0)
				{
					return true;
				}
				$query->free_result();
			}
		}
		
		return false;
	}
	//End
}

//delete entries from array first param is table name, second param is array of condition which match in database,
if(! function_exists("delete_entry_from_array"))
{
	//delete all entry from an array
	function delete_entry_from_array($tablename=NULL,$param=array())
	{
		if(($tablename != NULL) && (strlen(trim($tablename)) > 0))
		{
			if(((count($param) > 0)) && (is_array($param)))
			{
				foreach($param as $item)
				{
					gen_deletefun($tablename,$item);
				}
				
				return true;
			}
		}
		
		return false;
	}
	//End
}
//End


//get value from single col in table
if(! function_exists("get_single_val"))
{
	function get_single_val($tablename=NULL,$cols=NULL,$condition=array(),$orderby=NULL,$multicols=false)
	{
		$returnvar = (($multicols)?array():0);
		if((strlen(trim($tablename)) > 0))
		{
			$multicols = (($multicols)?true:((strlen(trim($cols)) > 0)?false:true));
			$fetch_data = gen_activefetchdbdata($tablename,$condition,$orderby,1,0,false,$cols);
			$returnvar = (($fetch_data[0])?(($multicols)?$fetch_data["resultArr"][0]:((($cols != NULL) && (strlen(trim($cols)) > 0))?$fetch_data["resultArr"][0][$cols]:$fetch_data["resultArr"][0])):0);
		}
		
		return $returnvar;
	}
}
//End

//empty full table
if(! function_exists("truncate_fun"))
{
	function truncate_fun($tablename=NULL)
	{
		if($tablename != NULL && (strlen(trim($tablename)) > 0))
		{
			$ci =& get_instance();
			$ci->db->truncate($tablename); 
			return true;
		}
		return false;
	}
}
//End

//get slideshow images
if(!function_exists("get_slideshow_images"))
{
    function get_slideshow_images()
    {
        $return_arr = array(false);
        $ci =& get_instance();
        
        $get_arr = gen_activefetchdbdata("slide_images",array("status" => 1),"img_index ASC",0,0,true,"title, image_path");
        if($get_arr[0])
        {
            $return_arr[0] = $get_arr[0];
            $return_arr["num"] = $get_arr["num"];
            $return_arr["resultArr"] = $get_arr["resultArr"];
        }
        
        return $return_arr;
    }
}
//End


/* End of file sql_queries_helper.php */
/* Location: ./application/helpers/sql_queries_helper.php */