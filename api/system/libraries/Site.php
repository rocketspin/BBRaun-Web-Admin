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
class CI_Site
{
	var $UID = 0;
	public function __construct($params = array())
	{
		// Set the super object to a local variable for use later
		$this->CI 		=& get_instance();
		$this->db 		= $this->CI->db;
	}
	
	
	public function info($name = '')
	{
		if(!empty($name)) $this->db->where('option_name',$name);
		$this->db->select('option_value, option_name');
		$query = $this->db->get('options');
		
		if( $query->num_rows() == 0 ) return false;
		
		if(empty($name))
		{
			foreach($query->result() as $row)
			{
				$data[$row->option_name] = $row->option_value;
			}
			
			return (object) $data;
		}
		
		else return $query->row()->option_value;
	}
	
	
	public function update_info($data)
	{
		$data 		= (object) $data;
		$is_updated = false;
		
		unset($data->update_settings);
		
		foreach($data as $key => $item)
		{
			$this->db->where('option_name', $key);
			$this->db->update('options', array('option_value' => $item));
			
			if(!$is_updated) $is_updated = $this->db->affected_rows();
		}
		
		return $is_updated;
	}
	
	
	
	
	
	public function upload_dir($dir, $realpath = 'assets/uploads/')
	{
		
		$year 	= FCPATH.$realpath.date('Y');
		$month 	= $year.'/'.date('m');
		$dir 	= $month.'/'.$dir.'/';
		
		if(!file_exists($year)) 	mkdir($year, 0777);
		if(!file_exists($month)) 	mkdir($month, 0777);
		if(!file_exists($dir)) 		mkdir($dir, 0777);
		
		$data['path'] 	= $dir;
		$data['url']	= base_url().str_replace(FCPATH,'', $dir);
		
		return (object) $data;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function sendmail($array)
	{	
		//Sample email array
		$data['message'] = $array['message'];
		$this->CI->load->library('email', array(
			'protocol'  => 'smtp',
			'smtp_host' => 'ssl://smtp.googlemail.com',
			'smtp_port' => '465',
			'smtp_user' => 'noreplytripoption@gmail.com',
			'smtp_pass' => 'Powercom888',
			'smtp_timeout' => '4',
			'mailtype'  => 'html',
		));
		
		$email_body = $this->CI->load->view('email-template', $data, TRUE);
		if(!isset($array['from']))
		{
			$array['from']['email'] = $this->info('support_email');
			$array['from']['name'] 	= $this->info('business_title');
		}
		
		//$this->CI->email->clear(); 
		
		if(isset($array['cc']))		$this->CI->email->cc($array['cc']);
		if(isset($array['bcc']))	$this->CI->email->bcc($array['bcc']);
			
		
		$this->CI->email->from($array['from']['email'], $array['from']['name']);
		$this->CI->email->to($array['to']);
		$this->CI->email->subject($array['subject']);
		$this->CI->email->message($email_body);
		
		return $this->CI->email->send();
	}
	
	
	
}




