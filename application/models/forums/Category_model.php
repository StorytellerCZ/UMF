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
 * Category Model
 * 
 * @package CIBB
 * @subpackage Models
 */
class Category_model extends CI_Model {
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct();
	$this->load->helper('date');
    }
        
    /**
     * Get a category by ID
     * 
     * @access public
     * @param int $category_id
     * @return object
     */
    public function get($category_id)
    {
	return $this->db->get_where($this->db->dbprefix . 'forums_categories', array('id' => $category_id))->row();
    }
    
    /**
     * Get category by slub
     *
     * @access public
     * @param string $slug
     * @return object
     */
    public function get_by_slug($slug)
    {
	return $this->db->get_where($this->db->dbprefix . 'forums_categories', array('slug' => $slug))->row();
    }
    
    /**
     * Create a category
     *
     * @access public
     * @param string $name
     * @param string $slug
     * @param int $parent
     * @return int Insert ID
     */
    public function create($name, $slug, $parent)
    {
        $this->db->insert($this->db->dbprefix . 'forums_categories', array('parent_id' => $parent, 'name' => $name, 'slug' => $slug, 'date_added' => mdate('%Y-%m-%d %H:%i:%s', now()), 'publish' => 1));
	$this->db->insert_id();
    }
    
    /**
     * Edit a category
     * 
     * @access public
     * @param int $id
     * @param string $name
     * @param string $slug
     * @param int $parent
     */
    public function edit($id, $name, $slug, $parent)
    {
	$this->db->where('id', $id);
	$this->db->update($this->db->dbprefix . 'forums_categories', array('parent_id' => $parent, 'name' => $name, 'slug' => $slug, 'date_edit' => mdate('%Y-%m-%d %H:%i:%s', now())));
	return $this->db->affected_rows();
    }
    
    /**
     * List the sub categories for the given category
     * @param Number $cat_id
     * @return object
     */
    public function get_all($cat_id = 0)
    {   
        $data = array();
        $this->db->order_by('name', 'asc');
        $query = $this->db->get_where($this->db->dbprefix . 'forums_categories', array('parent_id' => $cat_id));
        $counter = 0;
        foreach ($query->result() as $row) {
            $data[$counter]['id'] = $row->id;
            $data[$counter]['parent_id'] = $row->parent_id;
            $data[$counter]['name'] = $row->name;
            $data[$counter]['slug'] = $row->slug;
            $data[$counter]['real_name'] = $row->name;
            $children = $this->get_children($row->id, ' - ', $counter);
			$counter = $counter + $children;
			$counter++;
        }        
        return $data;
    }
    
    /**
     * Count the number of sub-categories for a given category
     * @param int $id Description
     * @param string $separator Description
     * @param int $counter Description
     * @return int
     */
    public function get_children($id, $separator, $counter)
	{
        $this->db->order_by('name', 'asc');
		$query = $this->db->get_where($this->db->dbprefix . 'forums_categories', array('parent_id' => $id));
		if ($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			foreach($query->result() as $row)
			{
				$counter++;
				$data[$counter]['id'] = $row->id;
				$data[$counter]['parent_id'] = $row->parent_id;
				$data[$counter]['name'] = $separator.$row->name;
				$data[$counter]['slug'] = $row->slug;
				$data[$counter]['real_name'] = $row->name;
				$children = $this->get_children($row->id, $separator.' - ', $counter);

				if ($children != FALSE)
				{
					$counter = $counter + $children;
				}
			}
			return $counter;
		}
	}
    
    public function get_all_parent($id, $counter = 0)
    {
	$this->data = NULL;
        $row = $this->db->get_where($this->db->dbprefix . 'forums_categories', array('id' => $id))->row_array();
	$this->data[$counter] = $row;
        if ($row['parent_id'] != 0)
	{
            $counter++;
            $this->get_all_parent($row['parent_id'], $counter);
        }
        return array_reverse($this->data);
    }
    
    public function delete($category_id)
    {
	//@todo figure out what to do with threads in this situation
	$this->db->delete($this->db->dbprefix . 'forums_categories', array('id' => $category_id));
    }
}
/* End of file Category_model.php */
/* Location: ./application/models/forums/Category_model.php */