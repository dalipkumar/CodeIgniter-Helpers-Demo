<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('pagination'))
{
	function pagination($tablename,$page=1,$condition=array(),$orderby=NULL,$totalrecords=5,$totalbottompagenum=5,$joincheck=false,$joinquery='')
	{
		(($page <= 0)?$page=1:'');
		$totalrecords = $totalrecords;
		
		$maxdata = (($joincheck)?num_join_query_rows($joinquery,$orderby):num_active_rows($tablename,$condition,$orderby));
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
		
		$totalrecordsfound = (($joincheck)?num_join_query_rows($joinquery,$orderby):num_active_rows($tablename,$condition,$orderby));
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
		//echo $totalrecords.' : '.$startindex.'<br>';
		//run query for fetch data from database
		(($joincheck)?$menuArr = fetched_data_from_query($joinquery,$orderby,$totalrecords,$startindex,false,"resultArr"):$menuArr = fetched_data_from_db($tablename,$condition,$orderby,$totalrecords,$startindex,false,"resultArr"));
		//End
		
		
		return array('limit'=>$totalrecords,'startindex'=>$startindex,'totalrecordsfound'=>$totalrecordsfound,'max_page_num'=>$max_page_num,'curr_page_num'=>$curr_page_num,'prev'=>$prev,'next'=>$next,'last'=>$last,'first'=>$first,'pagenums'=>$pagenums,'curr_num_of_data'=>$curr_num_of_data,'data_exists'=>$menuArr[0],'feteched_data'=>$menuArr['resultArr']);
	}
}

if(! function_exists('num_active_rows'))
{
	function num_active_rows($tablename=NULL, $param=array(), $orderby=NULL)
	{
		$returnnum = 0;
		$ci =& get_instance(); 
		if($tablename != NULL)
		{
			((count($param)>0)?$ci->db->where($param):'');
			((($orderby != NULL) && ($orderby != ''))?$ci->db->order_by($orderby):'');
			$returnnum = $ci->db->count_all_results($tablename);
		}
		
		return $returnnum;
	}
}

if(! function_exists('fetched_data_from_db'))
{
	function fetched_data_from_db($tablename=NULL,$param=array(),$orderby=NULL,$limit=0,$startindex=0,$fullArr=true,$specialIndex='resultArr')
	{
		$returArr = array(false,NULL,NULL,"num"=>NULL,"resultArr"=>NULL);
		$ci =& get_instance();
		$temp = false;
		if($tablename != NULL)
		{
			((count($param) > 0)?$ci->db->where($param):'');
			//$ci->db->order_by('title desc, name asc'); 
			(($orderby != NULL)?$ci->db->order_by($orderby):'');
			(($limit > 0)?(($startindex > 0)?$ci->db->limit($limit, $startindex):$ci->db->limit($limit)):'');
			$query = $ci->db->get($tablename);
			
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
}

if(! function_exists('num_join_query_rows'))
{
	function num_join_query_rows($query=NULL,$orderby=NULL)
	{
		$returnnum = 0;
		$ci =& get_instance(); 
		if(($query != NULL) && (trim($query) != ''))
		{
			$query = $query;
			((($orderby != NULL) && (strlen(trim($orderby)) > 0))?$query .= ' order by '.$orderby:'');
			$queryrun = $ci->db->query($query);
			if(@$queryrun->num_rows() > 0)
			{
				$returnnum = $queryrun->num_rows();
			}
		}
		
		return $returnnum;
	}
}

if(! function_exists('fetched_data_from_query'))
{
	function fetched_data_from_query($query=NULL,$orderby=NULL,$limit=0,$startindex=0,$fullArr=true,$specialIndex='resultArr')
	{
		//echo $limit.' : '.$startindex.'<br>';
		$returArr = array(false,NULL,NULL,"num"=>NULL,"resultArr"=>NULL);
		$ci =& get_instance();
		$temp = false;
		if(($query != NULL) && (trim($query) != ''))
		{
			$query = $query;
			((($orderby != NULL) && ($orderby != ''))?$query .= ' order by '.$orderby:'');
			$query .= ' LIMIT '.$startindex.', '.$limit;
			
			$queryrun = $ci->db->query($query);
			
			if(@$queryrun->num_rows() > 0)
			{
				$numdata = $queryrun->num_rows();
				$result = $queryrun->result_array();
				$returArr = array(true,$numdata,$result,"num"=>$numdata,"resultArr"=>$result);
				$temp = true;
			}
		}
		
		return (($fullArr == true)?$returArr:array($temp,$specialIndex=>$returArr[$specialIndex]));
	}
}

/* End of file pagination_helper.php */
/* Location: ./application/helpers/pagination_helper.php */