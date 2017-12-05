<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends CI_Controller {
	
	
	function __construct()
	{
		parent::__construct();
		if (!$this->ion_auth->logged_in()) redirect(base_url('auth'));
		$this->user = $this->ion_auth->user()->row();
		
		
		if(intval($this->input->get('per_page')))
		{
			$this->session->set_userdata('per_page', intval($this->input->get('per_page')));
			redirect(current_url());
		}
	}
	
	
	
	
	
	public function index()
	{
		if(!$this->ion_auth->is_admin()) redirect(base_url('tool'));
		$this->load->library('form_validation');
		$this->load->model('mdl_users');
		
		$config 				= array();
		$per_page				= intval($this->session->userdata('per_page'));
		$per_page				= ($per_page) ? $per_page : 30;
		$page 					= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$users 					= $this->mdl_users->fetch_users($per_page, $page);    
                $config["base_url"] 	= base_url('page/index');
		$config["suffix"] 	= ($this->input->get()) ? '?'.http_build_query($this->input->get()) : '';
		$config["first_url"]	= $config["base_url"].$config["suffix"];
        	$config["total_rows"] 	= $users->total_rows;
        	$config["per_page"] 	= $per_page;
        	$config["uri_segment"] 	= 3;

$config['full_tag_open'] = '<ul class="tsc_pagination tsc_paginationA tsc_paginationA01">';
$config['full_tag_close'] = '</ul>';
$config['prev_link'] = '&lt;';
$config['prev_tag_open'] = '<li>';
$config['prev_tag_close'] = '</li>';
$config['next_link'] = '&gt;';
$config['next_tag_open'] = '<li>';
$config['next_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="current"><a href="#">';
$config['cur_tag_close'] = '</a></li>';
$config['num_tag_open'] = '<li>';
$config['num_tag_close'] = '</li>';
 
$config['first_tag_open'] = '<li>';
$config['first_tag_close'] = '</li>';
$config['last_tag_open'] = '<li>';
$config['last_tag_close'] = '</li>';
 
$config['first_link'] = '&lt;&lt;';
$config['last_link'] = '&gt;&gt;';


        	$this->pagination->initialize($config);
		
		$data['data']['response']				= $this->session->flashdata('response');
		$data['data']['datatables']["base_url"] = $config["base_url"];
        	$data['data']['datatables']["links"] 	= $this->pagination->create_links();
 		$data['data']['datatables']["results"] 	= $users->result;
		$data['data']['user'] 					= $this->user;
		$data['page'] 							= 'view-users';		//file name of your view that you can see at folder pages
		$this->load->view('index',$data);							//Main template
	}
	
	
	
	public function reports()
	{
		$this->load->library('form_validation');
		$this->load->model('mdl_users');
		
		$config 				= array(); 
        $config["base_url"] 	= base_url('page');
		$config["suffix"] 		= ($this->input->get()) ? '?'.http_build_query($this->input->get()) : '';
		$config["first_url"]	= $config["base_url"].$config["suffix"];
        $config["uri_segment"] 	= 3;
		
		$data['data']['response']				= $this->session->flashdata('response');
		$data['data']['datatables']["base_url"] = $config["base_url"];
		$data['data']['user'] 					= $this->user;
		$data['page'] 							= 'view-report';		//file name of your view that you can see at folder pages
		$this->load->view('index',$data);								//Main template
	}
	
	public function companies()
	{
		if(!$this->ion_auth->is_admin()) show_404();
		
		$this->load->library('form_validation');
		$this->load->model('mdl_users');
		
		$config 				= array();
		$per_page				= intval($this->session->userdata('per_page'));
		$per_page				= ($per_page) ? $per_page : 30;
		$page 					= ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
		$users 					= $this->mdl_users->fetch_companies($per_page, $page);   
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
		$data['page'] 							= 'view-companies';		//file name of your view that you can see at folder pages
		$this->load->view('index',$data);								//Main template
	}
	
	
	
	
	public function add_company()
	{
		if(!$this->ion_auth->is_admin()) show_404();
		$this->load->library('form_validation');
		$this->load->model('mdl_users');
		$data 		= array();
		$response 	= $this->session->userdata('response');
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 			'Company Name', 	'required|is_unique[company.name]');
		$this->form_validation->set_rules('phone', 			'Phone No.', 		'trim');
		$this->form_validation->set_rules('address', 		'Address', 			'trim');
		$this->form_validation->set_rules('country', 		'Country', 			'required');
		$this->form_validation->set_rules('expiration', 	'Expiration Date', 	'required');
		
		if ($this->form_validation->run() !== FALSE)
		{
			$logo = '';
			if(isset($_FILES))
			{
				$config['upload_path'] 		= FCPATH.'assets/uploads/';
				$config['allowed_types'] 	= 'jpeg|jpg|png';
				$config['max_size']			= '5120';
				$this->load->library('upload', $config);
				
				if($this->upload->do_upload('logo'))
				{				
					$upload 	= $this->upload->data();
					$filename 	= rand(1000000,9999999).time().$upload['file_ext'];
					$logo		= base_url('assets/uploads/'.$filename);
					rename($upload['full_path'], $upload['file_path'].$filename);
				}
			}
			
			$inputs = (object) $this->input->post();
			$this->db->insert('company', array(
				'name' 			=> $inputs->name,
				'logo'			=> $logo,
				'phone' 		=> $inputs->phone,
				'address' 		=> $inputs->address,
				'country_name'	=> $inputs->country,
				'expiration'	=> date('Y-m-d',strtotime($inputs->expiration))
			));
			$lastid = $this->db->insert_id();
			
			$location[] = array('cid' => $lastid, 'sort' => 1, 'category' => 'healthcare',	'name' => 'Doctor');
			$location[] = array('cid' => $lastid, 'sort' => 2, 'category' => 'healthcare',	'name' => 'Nurse');
			
			$location[] = array('cid' => $lastid, 'sort' => 1, 'category' => 'location1',	'name' => 'ICU');
			$location[] = array('cid' => $lastid, 'sort' => 2, 'category' => 'location1',	'name' => 'General Wards');
			$location[] = array('cid' => $lastid, 'sort' => 3, 'category' => 'location1',	'name' => 'Emergency(A & E)');
			$location[] = array('cid' => $lastid, 'sort' => 4, 'category' => 'location1',	'name' => 'Clinics');
			$location[] = array('cid' => $lastid, 'sort' => 5, 'category' => 'location1',	'name' => 'Diagnostics Centre');
			
			$location[] = array('cid' => $lastid, 'sort' => 1, 'category' => 'location2',	'name' => 'Medical');
			$location[] = array('cid' => $lastid, 'sort' => 2, 'category' => 'location2',	'name' => 'Surgical');
			$location[] = array('cid' => $lastid, 'sort' => 3, 'category' => 'location2',	'name' => 'Paedatrics');
			
			$location[] = array('cid' => $lastid, 'sort' => 1, 'category' => 'location3',	'name' => 'Ward 1');
			$location[] = array('cid' => $lastid, 'sort' => 2, 'category' => 'location3',	'name' => 'Ward 2');
			$location[] = array('cid' => $lastid, 'sort' => 3, 'category' => 'location3',	'name' => 'Ward 3');
			$location[] = array('cid' => $lastid, 'sort' => 4, 'category' => 'location3',	'name' => 'Ward 4');
			$location[] = array('cid' => $lastid, 'sort' => 5, 'category' => 'location3',	'name' => 'Ward 5');
			
			$location[] = array('cid' => $lastid, 'sort' => 1, 'category' => 'location4',	'name' => 'In Patient');
			$location[] = array('cid' => $lastid, 'sort' => 2, 'category' => 'location4',	'name' => 'Out Patient');
			
			
			foreach($location as $loc)
			{
				$this->db->insert('locations', $loc);
			}
			
			$this->session->set_flashdata('response', '<div class="alert alert-success">New company successfully added.</div>');
			redirect(base_url('page/companies'));
		}
	
		$data['data']['response']	= $response;
		$data['data']['user'] 		= $this->user;
		$data['data']['countries'] 	= json_decode(file_get_contents(FCPATH.'assets/js/countries.json'));
		$data['page'] = 'view-add-company';				//file name of your view that you can see at folder pages
		$this->load->view('index',$data);				//Main template
	}
	
	
	
	
	
	public function edit_company($cid = '')
	{
		if(!$this->ion_auth->is_admin()) show_404();
		$this->load->library('form_validation');
		$this->load->model('Mdl_users');
		
		$company 	= $this->Mdl_users->get_company($cid);
		$data 		= array();
		$response 	= $this->session->userdata('response');
		
		if(!$company) show_404();
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules('name', 			'Institute Name', 	'required|callback_check_company['.$cid.']');
		$this->form_validation->set_rules('phone', 			'Phone No.', 		'trim');
		$this->form_validation->set_rules('address', 		'Address', 			'trim');
		$this->form_validation->set_rules('country', 		'Country', 			'required');
		$this->form_validation->set_rules('expiration', 	'Expiration Date', 	'required');
		
		
		if ($this->form_validation->run() !== FALSE)
		{
			$logo = $company->logo;
			if(isset($_FILES))
			{
				$config['upload_path'] 		= FCPATH.'assets/uploads/';
				$config['allowed_types'] 	= 'jpeg|jpg|png';
				$config['max_size']			= '5120';
				$this->load->library('upload', $config);
				
				if($this->upload->do_upload('logo'))
				{				
					$upload 		= $this->upload->data();
					$filename 		= rand(1000000,9999999).time().$upload['file_ext'];
					$logo			= base_url('assets/uploads/'.$filename);
					$company->logo 	= $logo;
					
					rename($upload['full_path'], $upload['file_path'].$filename);
				}
			}
			
			$inputs = (object) $this->input->post();
			$this->db->where('id', $cid);
			$this->db->update('company', array(
				'name' 			=> $inputs->name,
				'logo'			=> $logo,
				'phone' 		=> $inputs->phone,
				'address' 		=> $inputs->address,
				'country_name'	=> $inputs->country,
				'expiration'	=> date('Y-m-d',strtotime($inputs->expiration))
			));
			$response = '<div class="alert alert-success">Company details successfully updated.</div>';
		}
		
		$data['data']['response']	= $response;
		$data['data']['user'] 		= $this->user;
		$data['data']['company'] 	= $company;
		$data['data']['countries'] 	= json_decode(file_get_contents(FCPATH.'assets/js/countries.json'));
	
		$data['page'] = 'view-edit-company';			//file name of your view that you can see at folder pages
		$this->load->view('index',$data);				//Main template
	}
	
	
	
	
	
	
	public function add_user()
	{
		if(!$this->ion_auth->is_admin()) show_404();
		$this->load->library('form_validation');
		$this->load->model('Mdl_users');
		$data 		= array();
		$response 	= $this->session->userdata('response');
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 		'First Name', 		'required');
		$this->form_validation->set_rules('last_name', 			'Last Name', 		'required');
		$this->form_validation->set_rules('email_address', 		'Email Address', 	'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('address', 			'Address', 			'trim');
		$this->form_validation->set_rules('role', 				'User Role', 		'required');
		$this->form_validation->set_rules('company', 			'Company', 			'required');
		$this->form_validation->set_rules('contact_no', 		'Contact No.', 		'trim');
		
		if ($this->form_validation->run() !== FALSE)
		{		
			$inputs 	= (object) $this->input->post();
			$password	= $this->ion_auth->rand_str(8);
			
			$result = $this->ion_auth->register(
				$inputs->email_address,
				$password,
				$inputs->email_address,
				array(
					'cid'				=> $inputs->company,
					'first_name' 		=> $inputs->first_name,
					'last_name' 		=> $inputs->last_name,
					'address' 			=> $inputs->address,
					'company' 			=> $inputs->company,
					'phone' 			=> $inputs->contact_no,
					'date_registered' 	=> date('Y-m-d H:i:s')
				),
				array($inputs->role)
			);
			
			if(!$result)
				$data['response'] = '<div class="alert alert-danger">'.$this->ion_auth->errors().'</div>';
			else
			{
				//Email message
				$mail['message'] 	= "Hello ".$inputs->first_name.' '.$inputs->last_name.",<br><br>";
				$mail['message'] 	.= "Congratulations!  You are now a registered user of ".$this->site->info('business_title')." Auditing Tool (HHAT) App.<br>";
				$mail['message'] 	.= "<br /><br />To begin, download the app and login using the credentials details below.<br /><br />Login Credentials:<br>";				
				$mail['message'] 	.= "Email Address: ".$inputs->email_address."<br>";
				$mail['message'] 	.= "Password: ".$password."<br><br />";
				$mail['message'] 	.= "Best regards,<br>";
				$mail['message'] 	.= "HHAT Support Team";
				
				$mail['subject'] 	= 'Welcome to '.$this->site->info('business_title');
				$mail['to'] 		= $inputs->email_address;
				$this->site->sendmail($mail);
				
				$this->session->set_flashdata('response', '<div class="alert alert-success">Account successfully created, login details has been sent to <a href="mailto:'.$inputs->email_address.'">'.$inputs->email_address.'.</a></div>');
				redirect(base_url('page'));
			}
		}
		
		$data['data']['companies']	= $this->Mdl_users->get_companies();
		$data['data']['response']	= $response;
		$data['data']['user'] 		= $this->user;
		$data['page'] = 'view-add-user';				//file name of your view that you can see at folder pages
		$this->load->view('index',$data);				//Main template
	}
	
	
	
	
	
	
	
	public function edit_user($uid = '')
	{
		if(!$this->ion_auth->is_admin() || empty($uid)) show_404();
		
		$this->load->library('form_validation');
		$this->load->model('Mdl_users');
		$data 		= array();
		$response 	= $this->session->userdata('response');
		$userdata	= $this->ion_auth->user($uid)->row();
		
		if(!$userdata) show_404();
			
		$this->load->library('form_validation');
		$this->form_validation->set_rules('first_name', 		'First Name', 			'required');
		$this->form_validation->set_rules('last_name', 			'Last Name', 			'required');
		$this->form_validation->set_rules('address', 			'Address', 				'trim');
		$this->form_validation->set_rules('role', 				'User Role', 			'required');
		$this->form_validation->set_rules('company', 			'Company', 				'required');
		$this->form_validation->set_rules('contact_no', 		'Contact No.', 			'trim');
		$this->form_validation->set_rules('email_address', 		'Email Address', 	'required|valid_email|callback_check_email['.$uid.']');
		
		
		if($this->form_validation->run() !== FALSE)
		{	
			$inputs 	= (object) $this->input->post();		
			$result 	= $this->ion_auth->update($uid, array(
					'cid'				=> $inputs->company,
					'first_name' 		=> $inputs->first_name,
					'last_name' 		=> $inputs->last_name,
					'address' 			=> $inputs->address,
					'company' 			=> $inputs->company,
					'email'				=> $inputs->email_address,
					'phone' 			=> $inputs->contact_no,
					'date_registered' 	=> date('Y-m-d H:i:s')
			));

			
			if(!$result)
				$response = '<div class="alert alert-danger">No changes has been made.</div>';
			else {
				//RONALD
				echo("<script>console.log('PHP: START UPDATE ROLE');</script>");
                                echo("<script>console.log('PHP: ".$uid."');</script>");
                                echo("<script>console.log('PHP: ".$inputs->role."');</script>");
				$data = array(
		                    'group_id' => $inputs->role
		                );
				$this->db->where('user_id', $uid);
				$this->db->update('users_groups', $data);
				echo("<script>console.log('PHP: END UPDATE ROLE');</script>");
					
				//END RONALD

				$response = '<div class="alert alert-success">'.$inputs->first_name.' '.$inputs->last_name.'\'s account successfully updated...</div>';
			}
                        //else
			//	$response = '<div class="alert alert-success">'.$inputs->first_name.' '.$inputs->last_name.'\'s account successfully updated...</div>';
		}
		
		
		$data['data']['companies']	= $this->Mdl_users->get_companies();
		$data['data']['response']	= $response;
		$data['data']['user'] 		= $this->user;
		$data['data']['userdata'] 	= $userdata;
		$data['data']['usergroup'] 	= $this->ion_auth->get_users_groups($userdata->id)->row();
	
		$data['page'] = 'view-edit-user';				//file name of your view that you can see at folder pages
		$this->load->view('index',$data);				//Main template
	}
	
	
	
	
	
	
	
	function check_email($email, $uid)
	{
		$this->db->where('id !=', $uid);
		$this->db->where('email', $email);
		$query = $this->db->get('users');
		
		if($query->num_rows())
		{
			$this->form_validation->set_message('check_email', 'The %s field already exist.');
			return FALSE;
		}
		else
			return TRUE;
	}
	
	
	
	function check_company($name, $cid)
	{
		$this->db->where('id !=', $cid);
		$this->db->where('name', $name);
		$query = $this->db->get('company');
		
		if($query->num_rows())
		{
			$this->form_validation->set_message('check_email', 'The %s field already exist.');
			return FALSE;
		}
		else
			return TRUE;
	}
}















