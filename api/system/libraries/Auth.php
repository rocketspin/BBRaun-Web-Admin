<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Auth
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	User Authentication
 * @author		Fred Labanda
 */
class CI_Auth
{
	var $UID = 0;
	public function __construct($params = array())
	{
		// Set the super object to a local variable for use later
		$this->CI 		=& get_instance();
		$this->db 		= $this->CI->db;
		$this->session 	= $this->CI->session;
		$this->UID 		= $this->session->userdata('user_id');
	}
	

	
	public function is_logged_in()
	{
		return ($this->session->userdata('user_id') != '') ? true : false;
	}
		
	//User details
	public function user($Column = '', $UID = '')
	{
		$UID = (!empty($UID)) ? $UID : $this->session->userdata('user_id');
		if($UID)
		{	
			if(!empty($Column)) $this->db->select("$Column");
				
			$this->db->where('id',$UID);
			$Query = $this->db->get('users');
			
			return (!empty($Column)) ? $Query->row()->$Column : $Query->row(); 
		}
		return false;
	}
	
	
	
	//Balance details
	public function get_balance($column = '', $uid = '')
	{
		
		$uid = (!empty($uid)) ? $uid : $this->session->userdata('user_id');
		if($uid)
		{	
			if(!empty($column)) $this->db->select("$column");
			$this->db->where('uid', $uid);
			$query = $this->db->get('balances');
			
			return (!empty($column)) ? $query->row()->$column : $query->row();
		}
		return false;
	}
	
	
	
	
	public function get_total_balance($uid = '')
	{
		$uid = (!empty($uid)) ? $uid : $this->session->userdata('user_id');
		$this->db->where('uid', $uid);
		$query 		= $this->db->get('balances');
		
		if(!$query->num_rows()) return 0;
		
		$balance 	= $query->row();
		return ($balance->builders_bonus + $balance->direct_referral + $balance->indirect_referral + $balance->unilevel + $balance->main_fund);
	}
	
	
	
	
	//Update user balance
	public function update_bal($account = 'main_fund', $amount = 0, $uid = '', $reset = false)
	{
		$uid = (!empty($uid)) ? $uid : $this->session->userdata('user_id');
		
		$data['amount'] 		= $amount;
		$data['oldbal'] 		= $this->get_balance($account, $uid);
		$data['total_oldbal'] 	= $this->get_total_balance($uid);
		$data['newbal'] 		= ($reset == true) ? $amount : $data['oldbal'] + $amount;
		
		$this->db->where( 'uid', $uid);
		$this->db->update( 'balances', array($account => $data['newbal']));
		
		$data['status']			= $this->db->affected_rows();
		$data['total_newbal'] 	= $this->get_total_balance($uid);
		
		if($amount > 0)
		{
			$this->update_total_bal($uid, $account, $amount);
		}
		
		return (object) $data;
	}
	
	
	
	
	public function update_total_bal($uid, $account, $amount)
	{
		if($account != 'builders_bonus_pending' && $account != 'main_fund')
		{
			$oldbal = $this->get_balance('total_'.$account, $uid);
			
			$this->db->where( 'uid', $uid);
			$this->db->update( 'balances', array('total_'.$account => $oldbal + $amount));
			
			
			
			
			//Admin balance update
			$this->CI->load->library('site');
			$admin_oldbal = $this->CI->site->info('total_'.$account);
			
			$this->db->where('option_name','total_'.$account);
			$this->db->update( 'options', array('option_value' => $admin_oldbal + $amount));
		}
	}
	
	
	
	
	public function notify($data = array())
	{
		if(!isset($data['uid'])) 				$data['uid'] 				= $this->session->userdata('user_id');
		if(!isset($data['status'])) 			$data['status']				= 'Active';
		if(!isset($data['date_registered'])) 	$data['date_registered']	= date('Y-m-d H:i:s');
		
		if( !is_array($data) ) return false;
		$this->db->insert('notifications', $data);
		return $this->db->affected_rows();
	}
	
	
	
	
	public function update_last_transaction($uid, $date = '')
	{
		$date = (empty($date)) ? date('Y-m-d H:i:s') : $date;
		
		$this->db->where('id', $uid);
		$this->db->update('users',array(
			'last_transaction' => $date
		));
		
		return $this->db->affected_rows();
	}
}


