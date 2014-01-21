<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_model extends CI_Model {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('date');
    }
    
    public function get($thread_id, $start, $limit)
    {
        $this->db->select($this->db->dbprefix . 'forums_posts.*, '.$this->db->dbprefix . 'a3m_account.username');
        $this->db->join($this->db->dbprefix . 'a3m_account', $this->db->dbprefix . 'forums_posts.author_id = '.$this->db->dbprefix . 'a3m_account.id');
        $this->db->order_by($this->db->dbprefix . $this->db->dbprefix . 'forums_posts.date_add', 'ASC');
        $this->db->limit($limit, $start);
        return $this->db->get_where($this->db->dbprefix . 'forums_posts', array($this->db->dbprefix . 'forums_posts.thread_id' => $thread_id))->result();
    }
    
    public function reply($thread_id, $author, $post)
    {
        $reply['thread_id'] = $thread_id;
        //get the id of the latest post in the thread
        $reply['reply_to_id'] = $this->get_latest_in_thread($thread_id)->id;
        $reply['author_id'] = $author;
        $reply['post'] = $post;
        $reply['date_add'] = mdate('%Y-%m-%d %H:%i:%s', now());
        $this->db->insert($this->db->dbprefix . 'forums_posts', $reply);
    }
    
    public function get_post_num_in_thread($thread_id)
    {
        return $this->db->get_where($this->db->dbprefix . 'forums_posts', array('thread_id' => $thread_id))->num_rows();
    }
    
    
    public function get_latest_in_thread($thread_id)
    {
        $this->db->select_max('date_add');
        $this->db->where('thread_id', $where);
        $this->db->limit(1);
        return $this->db->get($this->db->dbprefix . 'forums_posts')->row();
    }
}