<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(! function_exists('run_clock'))
{
	function run_clock($type=NULL)
	{
		$ci =& get_instance();
		echo '<span id="Clock"></span>';
		echo '<script type="text/javascript">
				function tick() {
	var hours, minutes, seconds, ap;
	var intHours, intMinutes, intSeconds;
	var today;

	today = new Date();
	intHours = today.getHours();
	intMinutes = today.getMinutes();
	intSeconds = today.getSeconds();

		if (intHours == 0) {
			hours = "12:";
		} else if (intHours < 12) {
			hours = intHours+":";
		} else if (intHours == 12) {
			hours = "12:";
		} else {
			intHours = intHours - 12
			hours = intHours + ":";
		}
	
		if (intMinutes < 10) {
			minutes = "0"+intMinutes+":";
		} else {
			minutes = intMinutes+":";
		}

		if (intSeconds < 10) {
			seconds = "0"+intSeconds+" ";
		} else {
			seconds = intSeconds+" ";
		}

	timeString1 = fun1();
	timeString = hours+minutes+seconds;//+ap;
	document.getElementById(\'Clock\').innerHTML = "Today is " + timeString1 + "&nbsp;&nbsp;" + timeString;
	setTimeout("tick();", 100);
}

function fun1()
{
	var mydate=new Date()
	var theYear=mydate.getFullYear()
	var day=mydate.getDay()
	var month=mydate.getMonth()
	var daym=mydate.getDate()

	if (daym<10)
		daym="0"+daym;

	var dayarray=new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat")

	var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")

	return(dayarray[day]+", "+montharray[month]+" "+daym+", "+theYear);
}

tick();
var aaj = new Date();
var montharray=new Array("January","February","March","April","May","June","July","August","September","October","November","December")
var aajtak = (montharray[aaj.getMonth()])+ " " + aaj.getDate()  + "," + aaj.getFullYear();
				</script>';
	}
}


/* End of file clock_helper.php */
/* Location: ./application/helpers/clock_helper.php */