<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category_model extends CI_Model {
    public $error       = array();
    public $error_count = 0;
    public $data        = array();
    
    public function __construct() 
    {
        parent::__construct();
	$this->load->helper('date');
    }
        
    /*
     * @access public
     * @param int $category_id
     * @return Object
     */
    public function get($category_id)
    {
	return $this->db->get_where('forums_categories', array('id' => $category_id))->row();
    }
    
    public function get_by_slug($slug)
    {
	return $this->db->get_where('forums_categories', array('slug' => $slug))->row();
    }
    
    /*
     * @access public
     * @param string $name
     * @param string $slug
     * @param int $parent
     */
    public function create($name, $slug, $parent)
    {
        $this->db->insert('forums_categories', array('parent_id' => $parent, 'name' => $name, 'slug' => $slug, 'date_added' => mdate('%Y-%m-%d %H:%i:%s', now()), 'publish' => 1));
    }
    
    /*
     * @access public
     * @param int $id
     * @param string $name
     * @param string $slug
     * @param int $parent
     */
    public function edit($id, $name, $slug, $parent)
    {
	$this->db->where('id', $id);
	$this->db->update('forums_categories', array('parent_id' => $parent, 'name' => $name, 'slug' => $slug, 'date_edit' => mdate('%Y-%m-%d %H:%i:%s', now())));
    }
    
    public function get_all($cat_id = 0)
    {   
        $this->data = array();
        $this->db->order_by('name', 'asc');
        $query = $this->db->get_where('forums_categories', array('parent_id' => $cat_id));
        $counter = 0;
        foreach ($query->result() as $row) {
            $this->data[$counter]['id'] = $row->id;
            $this->data[$counter]['parent_id'] = $row->parent_id;
            $this->data[$counter]['name'] = $row->name;
            $this->data[$counter]['slug'] = $row->slug;
            $this->data[$counter]['real_name'] = $row->name;
            $children = $this->get_children($row->id, ' - ', $counter);
			$counter = $counter + $children;
			$counter++;
        }        
        return $this->data;
    }
    
    public function get_children($id, $separator, $counter)
	{
        $this->db->order_by('name', 'asc');
		$query = $this->db->get_where('forums_categories', array('parent_id' => $id));
		if ($query->num_rows() == 0)
		{
			return FALSE;
		}
		else
		{
			foreach($query->result() as $row)
			{
				$counter++;
				$this->data[$counter]['id'] = $row->id;
				$this->data[$counter]['parent_id'] = $row->parent_id;
				$this->data[$counter]['name'] = $separator.$row->name;
				$this->data[$counter]['slug'] = $row->slug;
				$this->data[$counter]['real_name'] = $row->name;
				$children = $this->get_children($row->id, $separator.' - ', $counter);

				if ($children != FALSE)
				{
					$counter = $counter + $children;
				}
			}
			return $counter;
		}
	}
    
    public function get_all_parent($id, $counter)
    {
        $row = $this->db->get_where('forums_categories', array('id' => $id))->row_array();
        $this->data[$counter] = $row;
        if ($row['parent_id'] != 0) {
            $counter++;
            $this->get_all_parent($row['parent_id'], $counter);
        }
        return array_reverse($this->data);
    }
}