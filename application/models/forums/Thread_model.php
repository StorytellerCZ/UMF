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
 * Thread Model
 * 
 * @package CIBB
 * @subpackage Models
 */
class Thread_model extends CI_Model {
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('date');
    }
    
    /**
     * Get a thread by id
     * @param int $thread_id
     * @return object
     */
    public function get($thread_id)
    {
        return $this->db->get_where($this->db->dbprefix . 'forums_threads', array('id' => $thread_id))->row();
    }
    
    /**
     * Get a thread by slug
     * @param string $slug
     * @return object
     */
    public function get_by_slug($slug)
    {
	return $this->db->get_where($this->db->dbprefix . 'forums_threads', array('slug' => $slug))->row();
    }
    
    /**
     * Get all threads
     * @param int $start At which entry to start
     * @param int $limit How many entries to get
     * @return object
     */
    public function get_all($start, $limit)
    {
        $this->db->select($this->db->dbprefix . 'forums_threads.*, '. $this->db->dbprefix . 'forums_categories.name AS category_name, '. $this->db->dbprefix . 'forums_categories.slug AS category_slug, '. $this->db->dbprefix . 'forums_posts.date_add');
        $this->db->select_max($this->db->dbprefix . 'forums_posts.date_add');
        $this->db->join($this->db->dbprefix . 'forums_categories', $this->db->dbprefix . 'forums_threads.category_id = ' . $this->db->dbprefix . 'forums_categories.id');
        $this->db->join($this->db->dbprefix . 'forums_posts', $this->db->dbprefix . 'forums_threads.id = '. $this->db->dbprefix . 'forums_posts.thread_id');
        $this->db->order_by($this->db->dbprefix . 'forums_posts.date_add', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get($this->db->dbprefix . 'forums_threads')->result();
    }
    
    /**
     * Get Threads by category
     * @param int $start At which entry to start
     * @param int $limit How many entries to get
     * @param int $cat_id Category ID
     * @return object
     */
    public function get_by_category($start, $limit, $cat_id)
    {
        $this->db->select('forums_threads.*, forums_categories.name AS category_name, forums_categories.slug AS category_slug, forums_posts.date_add');
        $this->db->select_max($this->db->dbprefix . 'forums_posts.date_add');
        $this->db->join($this->db->dbprefix . 'forums_categories', $this->db->dbprefix . 'forums_threads.category_id = '.$this->db->dbprefix . 'forums_categories.id');
        $this->db->join($this->db->dbprefix . 'forums_posts', $this->db->dbprefix . 'forums_threads.id = '.$this->db->dbprefix . 'forums_posts.thread_id');
        if(is_array($cat_id))
        {
            foreach($cat_id as $key => $value)
            {
                if($key == 0)
                {
                    $this->db->where($this->db->dbprefix . 'forums_threads.category_id', $value);
                }
                else
                {
                    $this->db->or_where($this->db->dbprefix . 'forums_threads.category_id', $value);
                }
            }
        }
        else
        {
            $this->db->where($this->db->dbprefix . 'forums_threads.category_id', $cat_id);
        }
        
        $this->db->order_by($this->db->dbprefix . 'forums_posts.date_add', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get($this->db->dbprefix . 'forums_threads')->result();
    }
    
    /**
     * Get latest post in thread
     * @param int $thread_id
     * @return object
     */
    public function get_latest_post_in_thread($thread_id)
    {
        $this->db->select_max('date_add')->from($this->db->dbprefix . 'forums_posts')->where('thread_id', $thread_id)->limit(1);
        return $this->db->get()->row();
    }
    
    /**
     * Get total number of threads by category
     * @param int $cat_id Category ID
     * @return int Number of rows
     */
    public function get_total_by_category($cat_id)
    {
        if(is_array($cat_id))
        {
            foreach($cat_id as $key => $value)
            {
                if($key == 0)
                {
                    $this->db->where('category_id', $value);
                }
                else
                {
                    $this->db->or_where('category_id', $value);
                }
            }
            $query = $this->db->get($this->db->dbprefix . 'forums_threads');
        }
        else
        {
            $query = $this->db->get_where($this->db->dbprefix . 'forums_threads', array('category_id' => $cat_id));
        }
        return $query->num_rows();
    }
    
    /**
     * Create a thread
     * @param string $title
     * @param string $slug
     * @param int $category_id
     * @param string $post_text
     * @param int $author
     * @return int Insert id
     */
    public function create($title, $slug, $category_id, $post_text, $author)
    {
        // insert into thread
        $thread['category_id'] = $category_id;
        $thread['title'] = $title;
        $thread['slug'] = $slug;
        $thread['date_add']       = mdate('%Y-%m-%d %H:%i:%s', now());
        $thread['date_last_post'] = mdate('%Y-%m-%d %H:%i:%s', now());
        $this->db->insert('forums_threads', $thread);
        
        // insert into post
        $post['author_id'] = $author;
        $post['post'] = $post_text;
        $post['thread_id'] = $this->db->insert_id();
        $post['date_add']  = mdate('%Y-%m-%d %H:%i:%s', now());
        $this->db->insert('forums_posts', $post);
    }
    
    /**
     * Edit a thread
     * @param int $thread_id
     * @param string $title
     * @param slug $slug
     * @param int $category_id
     * @return boolean
     */
    public function edit($thread_id, $title, $slug, $category_id)
    {
        // update thread
        $this->db->where('id', $thread_id);
        $this->db->update($this->db->dbprefix . 'forums_threads', array('title' => $title, 'slug' => $slug, 'category_id' => $category_id));
	return $this->db->affected_rows();
    }
    
    /**
     * Delete a thread
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        // delete thread
        $this->db->delete($this->db->dbprefix . 'forums_threads', array('id' => $id));
        
        // delete all posts on this thread
        $this->db->delete($this->db->dbprefix . 'forums_posts', array('thread_id' => $id));
	
	return $this->db->affected_rows();
    }
    
    /**
     * Count all threads
     * @return int
     */
    public function count_all_threads()
    {
        return $this->db->count_all_results('forums_threads');
    }
}
/* End of file Thread_model.php */
/* Location: ./application/models/forums/Thread_model.php */