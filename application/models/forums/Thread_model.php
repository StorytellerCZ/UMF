<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Thread_model extends CI_Model {
    public $error       = array();
    public $error_count = 0;
    public $data        = array();
    public $fields      = array();
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('date');
    }
    
    public function get($thread_id)
    {
        return $this->db->get_where('forums_threads', array('id' => $thread_id))->row();
    }
    
    public function get_by_slug($slug)
    {
	return $this->db->get_where('forums_threads', array('slug' => $slug))->row();
    }
    
    public function get_all($start, $limit)
    {
        $sql = "SELECT a.*, b.name as category_name, b.slug as category_slug, c.date_add 
                FROM forums_threads a, forums_categories b,forums_posts c 
                WHERE a.category_id = b.id AND a.id = c.thread_id 
                AND c.date_add = (SELECT MAX(date_add) FROM ".'forums_posts'." WHERE thread_id = a.id LIMIT 1) 
                ORDER BY c.date_add DESC LIMIT ".$start.", ".$limit;
        return $this->db->query($sql)->result();
    }
    
    public function get_by_category($start, $limit, $cat_id)
    {
        
        $sql = "SELECT a.*, b.name as category_name, b.slug as category_slug, c.date_add 
                FROM forums_threads a, forums_categories b,forums_posts c 
                WHERE a.category_id = b.id AND a.id = c.thread_id AND  a.category_id = ".$cat_id." 
                AND c.date_add = (SELECT MAX(date_add) FROM forums_posts WHERE thread_id = a.id LIMIT 1) 
                ORDER BY c.date_add DESC LIMIT ".$start.", ".$limit;
        return $this->db->query($sql)->result();
        /* @todo fix bellow so it works, so that we don't need the sql above
        $this->db->select('forums_threads.*, forums_categories.name AS category_name, forums_categories.slug AS category_slug, forums_posts.date_add');
        $this->db->from('forums_threads');
        $this->db->from('forums_categories');
        $this->db->from('forums_posts');
        $this->db->where('forums_threads.category_id', 'forums_categories.id');
        $this->db->where('forums_threads.id', 'forums_posts.thread_id');
        if(is_array($cat_id))
        {
            foreach($cat_id as $key => $value)
            {
                if($key == 0)
                {
                    $this->db->where('forums_threads.category_id', $value);
                }
                else
                {
                    $this->db->or_where('forums_threads.category_id', $value);
                }
            }
        }
        else
        {
            $this->db->where('forums_threads.category_id', $cat_id);
        }
        
        
        //@todo
        $where = '`forums_posts`.`date_add` = (SELECT MAX(`date_add`) FROM `forums_posts` WHERE `thread_id` = `forums_threads`.`id` LIMIT 1)';
        $this->db->where($where);
        
        $this->db->order_by('forums_posts.date_add', 'DESC');
        $this->db->limit($limit, $start);
        return $this->db->get()->result();*/
    }
    
    public function get_latest_post_in_thread($thread_id)
    {
        $this->db->select_max('date_add')->from('forums_posts')->where('thread_id', $thread_id)->limit(1);
        return $this->db->get()->row();
    }
    
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
            $query = $this->db->get('forums_threads');
        }
        else
        {
            $query = $this->db->get_where('forums_threads', array('category_id' => $cat_id));
        }
        return $query->num_rows();
    }
    
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
    
    public function edit($thread_id, $title, $slug, $category_id)
    {
        // update thread
        $this->db->where('id', $thread_id);
        $this->db->update('forums_threads', array('title' => $title, 'slug' => $slug, 'category_id' => $category_id));
    }
    
    public function delete($id)
    {
        // delete thread
        $this->db->delete('forums_threads', array('id' => $id));
        
        // delete all posts on this thread
        $this->db->delete('forums_posts', array('thread_id' => $id));
    }
    
    public function count_all_threads()
    {
        return $this->db->count_all_results('forums_threads');
    }
}
