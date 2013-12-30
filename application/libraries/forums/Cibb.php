<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:        CodeIgniter Bulletin Board
*
* Original Author of CIBB:      Aditia Rahman
*              http://superdit.com/2012/08/15/cibb-an-experimental-basic-forum-built-with-codeigniter-and-twitter-bootstrap/
*
* Rewrite Author:       Jan Dvorak
*               https://github.com/AdwinTrave
*
*/

class Cibb
{
    
    public function __construct()
    {
        $this->CI =& get_instance();
        
        $this->CI->load->model(array('forums/category_model', 'forums/thread_model'));
    }
    
    /*
     * BUG: language doesn't load
     */
    public function time_ago($date)
    {
        //load the appropriate lang file
        $this->CI->lang->load('forums/forums');
        $this->CI->load->helper('language');
        
        if(empty($date)) {
            return lang('forums_time_no_date');
        }
        
        $periods = array(lang('forums_time_second'), lang('forums_time_minute'), lang('forums_time_hour'), lang('forums_time_day'), lang('forums_time_week'), lang('forums_time_month'), lang('forums_time_year'), lang('forums_time_decade'));
        $lengths = array("60","60","24","7","4.35","12","10");
        $now = time();
        $unix_date = strtotime($date);
        
        // check validity of date
        if(empty($unix_date)) {
            return lang('forums_time_bad_date');
        }
        
        // is it future date or past date
        if($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = lang('forums_time_ago');
        } else {
            $difference = $unix_date - $now;
            $tense = lang('forums_time_from_now');
        }
        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }
        $difference = round($difference);
        if($difference != 1) {
            $periods[$j].= lang('forums_time_period');
        }

        return "$difference $periods[$j] {$tense}";
    }
}