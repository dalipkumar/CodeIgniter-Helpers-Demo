<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('search_function'))
{
	function search_function($tablename,$page=1,$searchString=NULL,$dbColName=array(),$totalrecords=5,$totalbottompagenum=5)
	{
		(($page <= 0)?$page=1:'');
		$totalrecords = $totalrecords;
		
		$maxdata = search_num_rows($tablename,$searchString,$dbColName);
		$totalpages = ceil($maxdata/$totalrecords);
		(($page > $totalpages)?$page=$totalpages:'');
		
		$nextpage = $page + 1;
		(($nextpage > $totalpages)?$nextpage = $totalpages:'');
		
		$prevpage = $page - 1;
		(($prevpage <= 0)?$prevpage=1:'');
		
		$startindex = $totalrecords * ($page - 1);
		(($startindex <= 0)?$startindex = 0:'');
		
		$totalpageviews = $totalbottompagenum;
		$roundofup = ceil($totalpageviews/2);
		$roundofdown = floor($totalpageviews/2);
		(($page <= $roundofup)?$centerofpagenum=$roundofup:$centerofpagenum=$page);
		
		((($centerofpagenum + $roundofdown) > $totalpages)?$greaterthenmax = true:$greaterthenmax = false);
		
		if($greaterthenmax)
		{
			$centerofpagenum = $totalpages - $roundofdown;
		}
		
		$pervpagenums = array();
		for($i=($centerofpagenum - $roundofdown); $i<($centerofpagenum) ; $i++)
		{
			(($i > 0)?$pervpagenums[] = $i:'');
		}
		
		for($i=($centerofpagenum); $i<=($centerofpagenum + $roundofdown) ; $i++)
		{
			((($i <= $totalpages) && ($i > 0))?$pervpagenums[] = $i:'');
		}
		
		$totalrecordsfound = search_num_rows($tablename,$searchString,$dbColName);
		$tempnum = ($page * $totalrecords);
		(($tempnum > $totalrecordsfound)?$tempnum = $totalrecordsfound:'');
		
		
		$totalrecordsfound = $totalrecordsfound;
		$max_page_num = $totalpages;
		$curr_page_num = $page;
		$prev = $prevpage;
		$next = $nextpage;
		$last = $totalpages;
		$first = 1;
		$pagenums = $pervpagenums;
		$curr_num_of_data = $tempnum;
		
		//run query for fetch data from database
		$menuArr = search_data_function($tablename,$searchString,$dbColName,$totalrecords,$startindex);
		//End
		
		
		return array('limit'=>$totalrecords,'startindex'=>$startindex,'totalrecordsfound'=>$totalrecordsfound,'max_page_num'=>$max_page_num,'curr_page_num'=>$curr_page_num,'prev'=>$prev,'next'=>$next,'last'=>$last,'first'=>$first,'pagenums'=>$pagenums,'curr_num_of_data'=>$curr_num_of_data,'data_exists'=>$menuArr[0],'feteched_data'=>$menuArr['resultArr']);
	}
}

if(! function_exists('search_num_rows'))
{
	function search_num_rows($tablename=NULL, $searchString=NULL, $searchCol=array())
	{
		$returnnum = 0;
		$firstLike = true;
		$ci =& get_instance(); 
		if(($tablename != NULL) && (strlen(trim($tablename)) > 0))
		{
			if(($searchString != NULL) && (strlen(trim($searchString)) > 0) && (count($searchCol) > 0))
			{
				foreach($searchCol as $dbcol)
				{
					(($firstLike)?$ci->db->like($dbcol, $searchString):$ci->db->or_like($dbcol, $searchString));
					$firstLike = false;
				}
				
				$explodeSearchStr = explode(' ',$searchString);
				if(count($explodeSearchStr) > 1)
				{
					$removeStrArr = array(",","-","_");
					foreach($explodeSearchStr as $searchstr)
					{
						$searchstr = str_replace($removeStrArr,"",$searchstr);
						foreach($searchCol as $dbcol)
						{
							$ci->db->or_like($dbcol, $searchstr);
						}
					}
				}
			}
			
			$returnnum = $ci->db->count_all_results($tablename);
			
		}
		
		return $returnnum;
	}
}


if(! function_exists('search_data_function'))
{
	function search_data_function($tablename=NULL,$searchString=NULL,$searchCol=array(),$limit=0,$startindex=0)
	{
		$returArr = array(false,NULL,NULL,"num"=>NULL,"resultArr"=>NULL);
		$firstLike = true;
				
		if(($tablename != NULL) && (strlen(trim($tablename)) > 0))
		{
			$ci =& get_instance();
			
			if(($searchString != NULL) && (strlen(trim($searchString)) > 0) && (count($searchCol) > 0))
			{
				foreach($searchCol as $dbcol)
				{
					(($firstLike)?$ci->db->like($dbcol, $searchString):$ci->db->or_like($dbcol, $searchString));
					$firstLike = false;
				}
				
				$explodeSearchStr = explode(' ',$searchString);
				if(count($explodeSearchStr) > 1)
				{
					$removeStrArr = array(",","-","_");
					foreach($explodeSearchStr as $searchstr)
					{
						$searchstr = str_replace($removeStrArr,"",$searchstr);
						foreach($searchCol as $dbcol)
						{
							$ci->db->or_like($dbcol, $searchstr);
						}
					}
				}
			}
			
			(($limit > 0)?(($startindex > 0)?$ci->db->limit($limit, $startindex):$ci->db->limit($limit)):'');
			$query = $query = $ci->db->get($tablename);
			
			if(@$query->num_rows() > 0)
			{
				$numdata = $query->num_rows();
				$result = $query->result_array();
				$returArr = array(true,$numdata,$result,"num"=>$numdata,"resultArr"=>$result);
			}
			
			$query->free_result();
		}

		return $returArr;
	}
}

/* End of file search_helper.php */
/* Location: ./application/helpers/search_helper.php */