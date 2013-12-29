<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Posts_model extends CI_Model {
    
    public function __construct() 
    {
        parent::__construct();
        $this->load->helper('date');
    }
    
    public function get($thread_id, $start, $limit)
    {
        $sql = "SELECT a.*, b.username, b.id as user_id FROM ".'forums_posts'." a, a3m_account b 
                WHERE a.thread_id = ".$thread_id." AND a.author_id = b.id 
                ORDER BY a.date_add ASC LIMIT ".$start.", ".$limit;
        return $this->db->query($sql)->result();
    }
    
    public function reply($thread_id, $author, $post)
    {
        $reply['thread_id'] = $thread_id;
        //get the id of the latest post in the thread
        $reply['reply_to_id'] = $this->get_latest_in_thread($thread_id)->id;
        $reply['author_id'] = $author;
        $reply['post'] = $post;
        $reply['date_add'] = mdate('%Y-%m-%d %H:%i:%s', now());
        $this->db->insert('forums_posts', $reply);
    }
    
    public function get_post_num_in_thread($thread_id)
    {
        return $this->db->get_where('forums_posts', array('thread_id' => $thread_id))->num_rows();
    }
    
    
    public function get_latest_in_thread($thread_id)
    {
        $where = 'date_add = (SELECT MAX(date_add) FROM forums_posts WHERE thread_id = ' . $this->db->escape($thread_id) . ')';
        //$this->db->where('thread_id', $thread_id);
        $this->db->where($where);
        $this->db->limit(1);
        return $this->db->get('forums_posts')->row();
    }
}