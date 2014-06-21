<?php
/**
* CodeIgniter Bulletin Board
*
* Original Author of CIBB:
* @author Aditia Rahman
* @link http://superdit.com/2012/08/15/cibb-an-experimental-basic-forum-built-with-codeigniter-and-twitter-bootstrap/
*
* Rewrite Author:
* @author Jan Dvorak IV.
* @link https://github.com/AdwinTrave
*
*/
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CIBB
 * 
 * @package CIBB
 * @subpackage Libraries
 */
class Cibb
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->CI =& get_instance();
        //$this->CI->load->model(array('forums/category_model', 'forums/thread_model'));
        $this->CI->load->language('forums/forums');
        $this->CI->load->helper('language');
    }
    
    /**
     * Transforms date to a string on how long ago it was
     * 
     * @param date $date
     * @return string
     */
    public function time_ago($date)
    {
        $this->CI =& get_instance();
        
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
/* End of file Cibb.php */
/* Location: ./application/libraries/forums/Cibb.php */