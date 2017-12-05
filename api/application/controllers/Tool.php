<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tool extends CI_Controller {
	
	
	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth'));
		$this->user = $this->ion_auth->user()->row();
	}
	
	
	
	public function index()
	{
		$this->load->library('form_validation');
		$this->load->model('mdl_users');
		
		$config 				= array();
		$per_page				= intval($this->session->userdata('per_page'));
		$per_page				= ($per_page) ? $per_page : 30;
		$page 					= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$users 					= $this->mdl_users->fetch_users($per_page, $page);   
        $config["base_url"] 	= base_url('page');
		$config["suffix"] 		= ($this->input->get()) ? '?'.http_build_query($this->input->get()) : '';
		$config["first_url"]	= $config["base_url"].$config["suffix"];
        $config["total_rows"] 	= $users->total_rows;
        $config["per_page"] 	= $per_page;
        $config["uri_segment"] 	= 3;
	 
        $this->pagination->initialize($config);
		
		$data['data']['response']				= $this->session->flashdata('response');
		$data['data']['datatables']["base_url"] = $config["base_url"];
        $data['data']['datatables']["links"] 	= $this->pagination->create_links();
 		$data['data']['datatables']["results"] 	= $users->result;
		$data['data']['user'] 					= $this->user;
		$data['page'] 							= 'view-locations';		//file name of your view that you can see at folder pages
		$this->load->view('index',$data);							//Main template
	}
	
	
	public function add_location_list()
	{
		$result['status'] 		= 0;
		$result['errortype'] 	= 'error';
		$data					= $this->input->get();
		
		if(!isset($data['name']) || empty($data['name']))
			$result['message'] = 'Item name is required.';
		
		elseif(!isset($data['category']) || empty($data['category']))
			$result['message'] = 'Category name is required.';
		
		else
		{
			$this->db->where(array('name' => $data['name'], 'cid' => $this->user->cid, 'category' => $data['category'], 'deleted' => 0));
			$query = $this->db->get('locations');
			
			if($query->num_rows())
				$result['message'] = 'Item already exist.';
				
			else
			{
				$this->db->where(array('cid' => $this->user->cid, 'category' => $data['category']));
				$query 	= $this->db->get('locations');
				$sortid = $this->get_location_last_sort($data['category']);
				$this->db->insert('locations', array(
					'sort' 		=> $sortid,
					'cid' 		=> $this->user->cid,
					'category' 	=> $data['category'],
					'name' 		=> $data['name']
				));
				
				if($this->db->affected_rows())
				{
					$this->update_devices($this->user->cid);
					$response 	= array('id' => $this->db->insert_id(), 'sort' => $sortid); 
					$result 	= array( 'status' => 1, 'errortype' => 'success', 'message' => 'New '+$data['category']+' has been added.', 'result' => $response);
				}
			}
		}
		
		echo json_encode($result);
	}
	
	
	private function get_location_last_sort($category)
	{
		$this->db->where(array('cid' => $this->user->cid, 'category' => $category));
		$this->db->distinct('category');
		$this->db->order_by('sort', 'DESC');
		$query = $this->db->get('locations');
		
		return ($query->num_rows()) ? $query->row()->sort + 1 : 0;
	}
	
	
	public function sort_location()
	{
		$data = $this->input->post('data');
		$status = 0;
		foreach($data as $key => $item)
		{
			$this->db->where('id', $item['id']);
			$this->db->update('locations', array('sort' => $key+1));
			
			$status = $status + $this->db->affected_rows();
		}
		
		if($status > 0) $this->update_devices($this->user->cid);
	}
	
	
	public function delete_location()
	{
		$result['status'] 		= 0;
		$result['errortype'] 	= 'error';
		$id 					= $this->input->get('id');
		
		if(!$id)
		{
			$result['message']	= 'Invalid ID.';
		}
		else
		{
			$this->db->update('locations', array('deleted' => 1), array('id' => $id));
			if($this->db->affected_rows())
			{
				$this->update_devices($this->user->cid);
				$result = array( 'status' => 1, 'errortype' => 'success', 'message' => 'Item successfully deleted.');
			}
			else
			{
				$result['message']	= 'Unable to delete item.';
			}
		}
		
		echo json_encode($result);
	}
	
	public function update_devices($cid)
	{
		$this->db->where('cid', $cid);
		$this->db->update('users', array('data_update' => 1));
	}
}















