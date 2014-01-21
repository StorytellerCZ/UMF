<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ref_iptocountry_model extends CI_Model {

	/**
	 * Get country by ip address
	 *
	 * @access public
	 * @param string $ip
	 * @return string
	 */
	function get_by_ip($ip)
	{
		// Unsigned long representation of ip address
		$ulip = sprintf("%u", ip2long($ip));

		$query = $this->db->get_where($this->db->dbprefix . 'ref_iptocountry', array('ip_from <' => $ulip, 'ip_to >' => $ulip));

		if ($row = $query->row())
		{
			return $row->country_code;
		}
		return FALSE;
	}

}


/* End of file Ref_iptocountry_model.php */
/* Location: ./application/models/account/Ref_iptocountry_model.php */