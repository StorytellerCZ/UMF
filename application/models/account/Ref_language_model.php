<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Ref_language
 *
 * Model for the Ref_language table.
 * Refferencing language information.
 *
 * @package A3M
 * @subpackage Models
 */
class Ref_language_model extends CI_Model
{

	/**
	 * Get ref language
	 *
	 * @access public
	 * @param string $country
	 * @return object
	 */
	function get($country)
	{
		$this->db->where('one', $country);
		$this->db->or_where('two', $country);
		$this->db->or_where('language', $country);
		$query = $this->db->get($this->db->dbprefix . 'ref_language');
		if ($query->num_rows()) return $query->row();
	}

	// --------------------------------------------------------------------

	/**
	 * Get all ref language
	 *
	 * @access public
	 * @return object
	 */
	function get_all()
	{
		$this->db->order_by('language', 'asc');
		return $this->db->get($this->db->dbprefix . 'ref_language')->result();
	}
}
/* End of file Ref_language_model.php */
/* Location: ./application/models/account/Ref_language_model.php */