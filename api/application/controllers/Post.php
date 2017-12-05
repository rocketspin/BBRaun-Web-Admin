<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Post extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) show_404();
	}

	
	
	public function block_member($uid)
	{
		if(!$this->ion_auth->is_admin()) show_404();
		
		$this->load->model('mdl_users');
		$this->mdl_users->change_user_status($uid, 'Blocked');
		redirect('page/members');
	}
	
	public function unblock_member($uid)
	{
		if(!$this->ion_auth->is_admin()) show_404();
		
		$this->load->model('mdl_users');
		$this->mdl_users->change_user_status($uid, 'Active');
		redirect('page/members');
	}
	
	public function delete_member($uid)
	{
		if(!$this->ion_auth->is_admin()) show_404();
		
		$this->load->model('mdl_users');
		$this->mdl_users->delete_user($uid);
		redirect('page/members');
	}
	
	
	
	
	
	//Package
	public function deactivate_package($pid)
	{
		if(!$this->ion_auth->is_admin()) show_404();
		
		$this->load->model('mdl_packages');
		$this->mdl_packages->change_package_status($pid);
		redirect('page/packages');
	}
	
	public function activate_package($pid)
	{
		if(!$this->ion_auth->is_admin()) show_404();
		
		$this->load->model('mdl_packages');
		$this->mdl_packages->change_package_status($pid, 'Active');
		redirect('page/packages');
	}
	
	public function get_downline($uid)
	{
		$this->load->model('mdl_users');
		echo json_encode($this->mdl_users->get_downline($uid));
	}
	
	
	
	
	
	
	public function transfer_balance($type)
	{
		$this->load->model('mdl_fund');
		$this->mdl_fund->transfer_balance($type);
		
		switch($type)
		{
			case 'builders_bonus':
				$account = 'Builder\'s Bonus';
			break;
			
			case 'direct_referral':
				$account = 'Direct Referral Bonus';
			break;
			
			case 'indirect_referral':
				$account = 'Indirect Referral Bonus';
			break;
			
			case 'unilevel':
				$account = 'Unilevel Bonus';
			break;
		}
		$this->session->set_flashdata('response', '<div class="alert alert-success">'.$account.' has been transfered to main fund.</div>');
		redirect(base_url());
	}
	
	
	public function reset_total_bonus($type)
	{
		switch($type)
		{
			case 'builders_bonus':
				$account = 'Total Builder\'s Bonus';
			break;
			
			case 'direct_referral':
				$account = 'Total Direct Referral Bonus';
			break;
			
			case 'indirect_referral':
				$account = 'Total Indirect Referral Bonus';
			break;
			
			case 'unilevel':
				$account = 'Total Unilevel Bonus';
			break;
		}
		
		$this->auth->update_bal($type, 0, '', true);
		
		$this->session->set_flashdata('response', '<div class="alert alert-success">'.$account.' has been reset.</div>');
		redirect(base_url());
	}
	
	
	
	
	
	
	public function admin_reset_total_bonus($type)
	{
		if(!$this->ion_auth->is_admin()) show_404();
		
		switch($type)
		{
			case 'builders_bonus':
				$account = 'Total Builder\'s Bonus';
			break;
			
			case 'direct_referral':
				$account = 'Total Direct Referral Bonus';
			break;
			
			case 'indirect_referral':
				$account = 'Total Indirect Referral Bonus';
			break;
			
			case 'unilevel':
				$account = 'Total Unilevel Bonus';
			break;
			
		}
		
		if($type == 'balance')
		{
			$oldbal = $this->site->info('balance');
			$this->site->reset_members_bal($type);
			$message = '<div class="alert alert-success">Total income accumulated has been reset.</div>';
			
			if($oldbal)
			{
				$this->db->insert('balance_monitor', array(
					'uid' 				=> $this->session->userdata('user_id'),
					'description' 		=> 'Income Reset',
					'amount' 			=> $oldbal,
					'oldbal' 			=> $oldbal,
					'newbal' 			=> 0,
					'date_registered'	=> date('Y-m-d H:i:s')
				));
			}
		}
		else
		{
			$this->site->reset_members_bal('total_'.$type);
			$message = '<div class="alert alert-success">Members '.$account.' has been reset.</div>';
		}
		
		$this->session->set_flashdata('response', $message);
		redirect(base_url());
	}
	
	
	
	
	
	public function delete_bank($id)
	{
		$this->load->model('mdl_fund');
		$this->mdl_fund->delete_bank($id);
		
		$this->session->set_flashdata('response', '<div class="alert alert-success">Bank account successfully deleted.</div>');
		redirect(base_url('page/account'));
	}
	
	
	
	
	public function get_notifications($last_id = 0)
	{
		if(!$this->ion_auth->is_admin()) show_404();
		
		$this->load->model('mdl_notifications');
		echo json_encode($this->mdl_notifications->get_notifications($last_id));
	}
	
	
	
	
	public function ajax_get_usernames()
	{
		$query = $this->input->get('query');
		$this->load->model('mdl_users');
		
		echo json_encode($this->mdl_users->ajax_get_usernames($query));
	}
	
	
	
	
	
	public function ajax_get_products()
	{
		$query = $this->input->get('query');
		$this->load->model('mdl_packages');
		
		echo json_encode($this->mdl_packages->ajax_get_products($query));
	}
}
