<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Api extends REST_Controller
{
	public function __construct()
    {
        parent::__construct();
		$this->load->model('Mdl_users');
		$this->token	= $this->input->get('token');
		$this->uid 		= $this->Mdl_users->is_token_valid($this->token);
    }
	
	
	public function index()
	{
		echo 'B. Braun hand hygiene API.';
	}
	
	
	public function login_post()
    {
		$result = $this->Mdl_users->api_login($this->post('email'), $this->post('password'));
		$this->response($result, 200);
    }
	
	
	
	
	public function resetpassword_get()
    {
		$result = $this->Mdl_users->api_resetpassword($this->get('email'));
		$this->response($result, 200);
    }
	
	
	

	public function updateruser_post()
    {
		$this->is_logged_in();
		$result = $this->Mdl_users->api_update_user($this->input->get('id'), $this->post());
		$this->response($result, 200);
    }
	
	
	
	
	
	public function sendmessage_post()
    {
		$result = $this->Mdl_users->api_sendmessage($this->post());
		$this->response($result, 200);
    }
	
	
	
	public function saveobservation_post()
	{
		$this->is_logged_in();
		$this->load->model('Mdl_observation');
		$result = $this->Mdl_observation->api_saveobservation($this->post(), $this->uid);
		$this->response($result, 200);
	}
	
	public function multiobservesend_post()
	{
		$this->is_logged_in();
		$this->load->model('Mdl_observation');
		$result = $this->Mdl_observation->api_multiobservesend($this->post(), $this->uid);
		$this->response($result, 200);
	}
	
	
	public function getobservation_get()
	{
		$this->is_logged_in();
		$this->load->model('Mdl_observation');
		$type 	= ($this->get('uid') == 0) ? 'users' : 'user';
		$result = $this->Mdl_observation->api_getobservation($this->uid, $type);
		$this->response($result, 200);
	}
	
	public function report_get()
	{
		$this->load->model('Mdl_observation');
		$result = $this->Mdl_observation->apiexcelreports();
	}
	
	
	public function mailreports_post()
	{
		$this->is_logged_in();
		$this->load->model('Mdl_observation');
		$result = $this->Mdl_observation->api_sendmailreports($this->post('data'), $this->uid);
		$this->response($result, 200);
	}
	
	
	public function getstatistics_get()
	{
		$this->is_logged_in();
		$this->load->model('Mdl_observation');
		$result = $this->Mdl_observation->api_statistics($this->uid);
		$this->response($result, 200);
	}
	
	public function getlocations_get()
	{
		//$this->is_logged_in();
		$this->load->model('Mdl_observation');
		$result = $this->Mdl_observation->api_locations($this->get('cid'), $this->uid);
		$this->response($result, 200);
	}
	
	
	public function counter_get()
	{
		$this->load->model('Mdl_observation');
		$result = $this->Mdl_observation->api_countobservation(1, 1);
		$this->response($result, 200);
	}
	
	
	public function getcompanyusers_get()
    {
		$this->is_logged_in();
		$result = $this->Mdl_users->api_getcompanyusers($this->uid);
		$this->response($result, 200);
    }
	
	
	public function checklistupdate_get()
	{
		$this->is_logged_in();
		$result = $this->Mdl_users->get_user($this->uid)->data_update;
		$this->response(array( 'status' => 1, 'errno' => '',  'message' => 'List update status', 'result' => $result), 200);
	}
	
	
	function is_logged_in()
    {
		if(!$this->token || !$this->uid)
		{
			$this->response(array( 'status' => 0, 'errno' => 1080,  'message' => 'Invalid token.', 'result' => ''), 200);
		}
    }
}

