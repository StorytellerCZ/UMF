<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$CI =& get_instance();

function set_pagination()
{
    $CI->page_config['first_link']         = '&lsaquo; First';
    $CI->page_config['first_tag_open']     = '<li>';
    $CI->page_config['first_tag_close']    = '</li>';
    $CI->page_config['last_link']          = 'Last &raquo;';
    $CI->page_config['last_tag_open']      = '<li>';
    $CI->page_config['last_tag_close']     = '</li>';
    $CI->page_config['next_link']          = 'Next &rsaquo;';
    $CI->page_config['next_tag_open']      = '<li>';
    $CI->page_config['next_tag_close']     = '</li>';
    $CI->page_config['prev_link']          = '&lsaquo; Prev';
    $CI->page_config['prev_tag_open']      = '<li>';
    $CI->page_config['prev_tag_close']     = '</li>';
    $CI->page_config['cur_tag_open']       = '<li class="active"><a href="#">';
    $CI->page_config['cur_tag_close']      = '</a></li>';
    $CI->page_config['num_tag_open']       = '<li>';
    $CI->page_config['num_tag_close']      = '</li>';
}