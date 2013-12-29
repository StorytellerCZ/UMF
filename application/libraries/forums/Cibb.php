<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Name:        CodeIgniter Bulletin Board
*
* Original Author:      Aditia Rahman
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
        $this->ci =& get_instance();
        
        $this->ci->load->model(array('forums/category_model', 'forums/thread_model'));
        $this->ci->load->helper('language');
    }
}