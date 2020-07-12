<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('currtimedate'))
{
	//current date and time
	function currtimedate($type=NULL)
	{
		$date = date("d");
		$month = date("m");
		$year = date("Y");
		$hours = date("H");
		$minutes = date("i");
		$seconds = date("s");
		$fulldate = $year."-".$month."-".$date;
		$time = $hours.":".$minutes.":".$seconds;
		$dateAndTime = $fulldate." ".$time;
		$datetimestr = strtotime($dateAndTime);
		
		$checkarra = array("datetime","fulldate","time","date","month","year","hours","minutes","seconds","datetimestr");
		
		$timearra = array("datetime"=>$dateAndTime,"fulldate"=>$fulldate,"time"=>$time,"date"=>$date,"month"=>$month,"year"=>$year,"hours"=>$hours,"minutes"=>$minutes,"seconds"=>$seconds,"datetimestr"=>$datetimestr);
		
		if(($type != NULL) && (in_array($type,$checkarra)))
		{
			return $timearra[$type];
		}
		
		return $timearra;
	}
	//End
}

if(! function_exists("get_month_in_alpha"))
{
	//get month in alpha from numeric
	function get_month_in_alpha($num=0,$type=true)
	{
		$returnstr = '';
		$fullmonthArr = array("January","February","March","April","May","June","July","August","September","October","November","December");
		$shortmonthArr = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
		
		if($num > 0)
		{
			$num--;
			if($type)
			{
				if(array_key_exists($num, $fullmonthArr))
				{
					$returnstr = $fullmonthArr[$num];
				}
			}else
			{
				if(array_key_exists($num, $fullmonthArr))
				{
					$returnstr = $shortmonthArr[$num];
				}
			}
		}
		
		return $returnstr;
	}
	//End
}

if(! function_exists("get_month_in_num"))
{
	//get month in numeric from alpha
	function get_month_in_num($str='')
	{
		$returnstr = '';
		$fullmonthArr = array("january"=>1,"february"=>2,"march"=>3,"april"=>4,"may"=>5,"june"=>6,"july"=>7,"august"=>8,"deptember"=>9,"october"=>10,"november"=>11,"december"=>12,"jan"=>1,"feb"=>2,"mar"=>3,"apr"=>4,"may"=>5,"jun"=>6,"jul"=>7,"aug"=>8,"sep"=>9,"oct"=>10,"nov"=>11,"dec"=>12);
		
		$str = strtolower($str);
		if(array_key_exists($str, $fullmonthArr))
		{
			$returnstr = $fullmonthArr[$str];
		}
		
		return $returnstr;
	}
	//End
}


/* End of file currenttime_helper.php */
/* Location: ./application/helpers/currenttime_helper.php */