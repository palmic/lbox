<?php
/**
 * 
 *
*/

class CalendarDZONE {

//
//Class    : Hold all the functions to help when calculating dates
//Params
//         : 
//

		public $holidays = array();
		public $holiday_dates = array();
		public $seconds_per_day = 86400;
  		public $sunday_val      = "0";
  		public $saturday_val    = "6";

	public function clsWorkDays( $p_type='UK' ) {
	
		//
		//Function : constructor to automaticaly set the list of holidays.
		//Params
		//         : p_type   this param can be used when adding other countrys holidays
		// Returns
		//         : sets of holidays

		//
		// future dates may be found from the same place i looked
		// http://www.dti.gov.uk/employment/bank-public-holidays/index.html
		// or for previous try
		// http://www.dti.gov.uk/employment/bank-public-holidays/bank-public-holidays/page18893.html
		//


		//

		/*if ( $p_type=='UK' ) {
		
			$this->holidays[] = new Holiday("New Years Day", $this->get_timestamp("2005-01-03"));
			$this->holidays[] = new Holiday("Good Friday", $this->get_timestamp("2005-03-25"));
			$this->holidays[] = new Holiday("Easter Monday", $this->get_timestamp("2005-03-28"));
			$this->holidays[] = new Holiday("May Day", $this->get_timestamp("2005-05-02"));
			$this->holidays[] = new Holiday("Spring Bank Holiday", $this->get_timestamp("2005-05-30"));
			$this->holidays[] = new Holiday("Summer Bank Holiday", $this->get_timestamp("2005-08-29"));
			$this->holidays[] = new Holiday("Christmas Day", $this->get_timestamp("2005-12-25"));
			$this->holidays[] = new Holiday("Boxing Day", $this->get_timestamp("2005-12-26"));
		
			$this->holidays[] = new Holiday("New Years Day", $this->get_timestamp("2006-01-02"));
			$this->holidays[] = new Holiday("Good Friday", $this->get_timestamp("2006-04-14"));
			$this->holidays[] = new Holiday("Easter Monday", $this->get_timestamp("2006-04-17"));
			$this->holidays[] = new Holiday("May Day", $this->get_timestamp("2006-05-01"));
			$this->holidays[] = new Holiday("Spring Bank Holiday", $this->get_timestamp("2006-05-29"));
			$this->holidays[] = new Holiday("Summer Bank Holiday", $this->get_timestamp("2006-08-28"));
			$this->holidays[] = new Holiday("Christmas Day", $this->get_timestamp("2006-12-27"));
			$this->holidays[] = new Holiday("Boxing Day", $this->get_timestamp("2006-12-27"));
		
			$this->holidays[] = new Holiday("New Years Day", $this->get_timestamp("2007-01-01"));
			$this->holidays[] = new Holiday("Good Friday", $this->get_timestamp("2007-04-06"));
			$this->holidays[] = new Holiday("Easter Monday", $this->get_timestamp("2007-04-09"));
			$this->holidays[] = new Holiday("May Day", $this->get_timestamp("2007-05-07"));
			$this->holidays[] = new Holiday("Spring Bank Holiday", $this->get_timestamp("2007-05-28"));
			$this->holidays[] = new Holiday("Summer Bank Holiday", $this->get_timestamp("2007-08-27"));
			$this->holidays[] = new Holiday("Christmas Day", $this->get_timestamp("2007-12-25"));
			$this->holidays[] = new Holiday("Boxing Day", $this->get_timestamp("2007-12-26"));

			$this->holidays[] = new Holiday("New Years Day", $this->get_timestamp("2008-01-01"));
			$this->holidays[] = new Holiday("Good Friday", $this->get_timestamp("2008-03-21"));
			$this->holidays[] = new Holiday("Easter Monday", $this->get_timestamp("2008-03-24"));
			$this->holidays[] = new Holiday("May Day", $this->get_timestamp("2008-05-05"));
			$this->holidays[] = new Holiday("Spring Bank Holiday", $this->get_timestamp("2008-05-26"));
			$this->holidays[] = new Holiday("Summer Bank Holiday", $this->get_timestamp("2008-08-25"));
			$this->holidays[] = new Holiday("Christmas Day", $this->get_timestamp("2008-12-25"));
			$this->holidays[] = new Holiday("Boxing Day", $this->get_timestamp("2008-12-26"));		
		
			$this->holidays[] = new Holiday("New Years Day", $this->get_timestamp("2009-01-01"));
			$this->holidays[] = new Holiday("Good Friday", $this->get_timestamp("2009-04-10"));
			$this->holidays[] = new Holiday("Easter Monday", $this->get_timestamp("2009-04-13"));
			$this->holidays[] = new Holiday("May Day", $this->get_timestamp("2009-05-04"));
			$this->holidays[] = new Holiday("Spring Bank Holiday", $this->get_timestamp("2009-05-25"));
			$this->holidays[] = new Holiday("Summer Bank Holiday", $this->get_timestamp("2009-08-31"));
			$this->holidays[] = new Holiday("Christmas Day", $this->get_timestamp("2009-12-25"));
			$this->holidays[] = new Holiday("Boxing Day", $this->get_timestamp("2009-12-28"));

		}

		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2005-12-26"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2005-12-27"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2005-12-28"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2005-12-29"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2005-12-30"));

		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2006-12-25"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2006-12-26"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2006-12-27"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2006-12-28"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2006-12-29"));

		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2007-12-24"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2007-12-25"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2007-12-26"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2007-12-27"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2007-12-28"));

		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2008-12-29"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2008-12-30"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2008-12-31"));

		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2009-12-28"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2009-12-29"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2009-12-30"));
		$this->holidays[] = new Holiday("Christmas ShutDown", $this->get_timestamp("2009-12-31"));

*/

		//go fill array to enable fast searches
		foreach ( $this->holidays as $holiday_date ) {
			$this->holiday_dates[] = $holiday_date->date;
		}

	}

	public function days_diff($p_start_date, $p_end_date = NULL, $p_workdays_only = TRUE, $p_skip_holidays = TRUE){
	
	//
	//Function : Main function to calculate the number of days or work days between 2 dates. If no end date passed
	//           in then this can be used to identify if the day is a working day as the function will return 1 or 0
	//Params
	//         : p_start_date     This paramter is the range starting date
	//         : p_end_date       This paramter is the range ending date (can be null)
	//         : p_workdays_only  This paramter is set if you DO NOT want to count weekends
	//         : p_skip_holidays  This paramter is set if you DO NOT want to count standard Bank Holidays & enforced business shutdowns
	// Returns
	//         : number of days between the 2 dates or if no end date passed in then 1/0 if the start day is a work day
	//
	
	    $end_date        = $p_end_date;
	    if ( strlen($p_end_date)==0 ) $end_date = $p_start_date;
	
	    $end_date        = strtotime($end_date);
	    $start_date      = strtotime($p_start_date);
	    if ($start_date > $end_date) {
	    	$start_date_temp	= $end_date;
	    	$end_date			= $start_date;
	    	$start_date			= $start_date_temp;
	    }
	    $nbr_work_days   = 0;

	    for($day_val = $start_date; $day_val <= $end_date; $day_val += $this->seconds_per_day){
	        $pointer_day = date("w", $day_val);
	        if($p_workdays_only == true){
	            if(($pointer_day != $this->sunday_val) AND ($pointer_day != $this->saturday_val)){
	                if($p_skip_holidays == true){
	                    if(!in_array($day_val, $this->holiday_dates)){
	                        $nbr_work_days++;
	                    }
	                }else{
	                    $nbr_work_days++;
	                }
	            }
	        }else{
	            if($p_skip_holidays == true){
	                if(!in_array($day_val, $this->holiday_dates)){
	                    $nbr_work_days++;
	                }
	            }else{
	                $nbr_work_days++;
	            }
	        }
	    }
	    return $nbr_work_days;
	}


	public function get_timestamp($p_date){
	
	//
	//Function : internal function to convert a date from mySQL fmt to unix timestamp
	//Params
	//         : p_date     This paramter takes a date in the format yyyy-mm-dd
	// Returns
	//         : a unix timestamp
	//
	
	    $date_array = explode("-",$p_date); // split the array
	
	    $the_year = $date_array[0];
	    $the_month = $date_array[1];
	    $the_day = $date_array[2];
	
	    $the_timestamp = mktime(0,0,0,$the_month,$the_day,$the_year);
	    return($the_timestamp); // return it to the user
	}

}
?>