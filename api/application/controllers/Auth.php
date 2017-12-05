<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
	}







	// redirect if needed, otherwise display the user list
	function index()
	{
		if ($this->ion_auth->logged_in())
		{
			redirect(base_url('page'));
		}
		
		$this->form_validation->set_rules('email', 'Email address', 'required');
		$this->form_validation->set_rules('password', 	'Password', 'required');
		$data['response'] = $this->session->flashdata('response');
		
		if(isset($_POST['login']))
		{
			if ($this->form_validation->run() !== FALSE)
			{
				$identity = $this->input->post('email');
				$password = $this->input->post('password');
				$remember = (bool) $this->input->post('rememberme');
				
				$result 			= $this->ion_auth->login($identity, $password, $remember);
				$data['response'] 	= '<div class="alert alert-danger">'.$this->ion_auth->errors().'</div>';
				
				if ($this->ion_auth->in_group(2))
				{
					$this->ion_auth->logout();
					$data['response'] 	= '<div class="alert alert-danger">Invalid login.</div>';
				}
				else
					if($result) redirect(base_url('page'));
			}
			else
			{
				$data['response'] 	= '<div class="alert alert-danger">'.validation_errors().'</div>';
			}
		}
		
		$this->load->view('auth/login', $data);
	}
	
	
	
	
	

	// log the user out
	public function logout()
	{
		// log the user out
		$logout = $this->ion_auth->logout();
		// redirect them to the login page
		$this->session->set_flashdata('response', '<div class="alert alert-info">'.$this->ion_auth->messages().'</div>');
		redirect(base_url('auth'));
	}
	
	
		
	
	// forgot password
	public function forgot_password()
	{
		$this->form_validation->set_rules('username', 'Username', 'required');
		if ($this->form_validation->run() == false)
		{
			//set any errors and display the form
			$data['response'] = validation_errors('<div class="alert alert-danger">', '</div>');
			$this->load->view('auth/forgot-password', $data);
		}
		else
		{
			//run the forgotten password method to email an activation code to the user
			$forgotten = $this->ion_auth->forgotten_password($this->input->post('username'));
	
			if($forgotten)
			{
				$this->session->set_flashdata('response', '<div class="alert alert-success">'.$this->ion_auth->messages().'</div>');
				redirect("auth", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$data['response'] = '<div class="alert alert-danger">'.$this->ion_auth->errors().'</div>';
				$this->load->view('auth/forgot-password', $data);
			}
		}
	}
	
	
	
	
	
	
	
	
	// reset password - final step for forgotten password
	public function reset_password($code = NULL)
	{
		if (!$code) show_404();
		
		$user = $this->ion_auth->forgotten_password_check($code);

		if ($user)
		{
			// if the code is valid then display the password reset form

			$this->form_validation->set_rules('new', 'New Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
			$this->form_validation->set_rules('new_confirm', 'Confirm New Password', 'required');

			if ($this->form_validation->run() == false)
			{
				// display the form
				// set the flash data error message if there is one
				$data['response'] 				= (validation_errors()) ? '<div class="alert alert-danger">'.validation_errors().'</div>' : $this->session->flashdata('response');
				$data['min_password_length'] 	= $this->config->item('min_password_length', 'ion_auth');
				
				$data['new_password'] = array(
					'name' 		=> 'new',
					'id'   		=> 'new',
					'type' 		=> 'password',
					'pattern' 	=> '^.{'.$data['min_password_length'].'}.*$',
					'class'		=> 'form-control'
				);
				
				$data['new_password_confirm'] = array(
					'name' 		=> 'new_confirm',
					'id'   		=> 'new_confirm',
					'type' 		=> 'password',
					'pattern' 	=> '^.{'.$data['min_password_length'].'}.*$',
					'class'		=> 'form-control'
				);
				
				$data['user_id'] = array(
					'name'  	=> 'user_id',
					'id'    	=> 'user_id',
					'type'  	=> 'hidden',
					'value' 	=> $user->id,
				);
				
				$data['csrf'] 	= $this->_get_csrf_nonce();
				$data['code'] 	= $code;

				$this->load->view('auth/reset-password', $data);
			}
			else
			{
				// do we have a valid request?
				if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id'))
				{
					// something fishy might be up
					$this->ion_auth->clear_forgotten_password_code($code);
					show_error($this->lang->line('error_csrf'));
				}
				else
				{
					// finally change the password
					$identity 	= $user->{$this->config->item('identity', 'ion_auth')};
					$change 	= $this->ion_auth->reset_password($identity, $this->input->post('new'));

					if ($change)
					{
						// if the password was successfully changed
						$this->session->set_flashdata('response', '<div class="alert alert-success">'.$this->ion_auth->messages().'</div>');
						redirect("auth", 'refresh');
					}
					else
					{
						$this->session->set_flashdata('response', '<div class="alert alert-danger">'.$this->ion_auth->errors().'</div>');
						redirect('auth/reset_password/'.$code, 'refresh');
					}
				}
			}
		}
		else
		{
			// if the code is invalid then send them back to the forgot password page
			$this->session->set_flashdata('response', '<div class="alert alert-danger">'.$this->ion_auth->errors().'</div>');
			redirect("auth/forgot_password", 'refresh');
		}
	}
	
	
	
	
	
	public function register()
	{
		if(isset($_GET['mid']))
		{
			$this->load->model('mdl_users');
			if(!$this->mdl_users->is_username_valid($_GET['mid'])) show_404();
		}
		
		$data = array();
		$this->form_validation->set_rules('username', 	'Username', 	'required');
		$this->form_validation->set_rules('first_name', 'First Name', 	'required');
		$this->form_validation->set_rules('last_name', 	'Last Name', 	'required');
		$this->form_validation->set_rules('email_address', 'Email Address', 'required|valid_email|is_unique[users.email]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[retype_password]');
		$this->form_validation->set_rules('retype_password', 'Retype Password', 'required');
		if ($this->form_validation->run() !== FALSE)
		{
			$username 	= $this->input->post('username');
			$password 	= $this->input->post('password');
			$email 		= $this->input->post('email_address');
			
			$data = array(
				'first_name' 		=> $this->input->post('first_name'),
				'last_name' 		=> $this->input->post('last_name'),
				'status'			=> 'Active',
				'last_transaction'	=> date('Y-m-d H:i:s'),
				'date_registered'	=> date('Y-m-d H:i:s')
			);

			$result = $this->ion_auth->register($username, $password, $email, $data,array(2));
			
			if(!$result)
				$data['response'] = '<div class="alert alert-danger">'.$this->ion_auth->errors().'</div>';
			else
			{
				
				//Email message
				$fullname 			= $data['first_name'].' '.$data['last_name'];
				$mail['message'] 	= "Hello $fullname,<br><br>";
				$mail['message'] 	.= "Congratulations, you are now a member of ".$this->site->info('business_title').".<br>";
				$mail['message'] 	.= "Should you encounter any problems during your registration, don't hesitate to contact us at: ";
				$mail['message'] 	.= '<a href="mailto:'.$this->site->info('support_email').'">'.$this->site->info('support_email').'</a>.<br>';
				$mail['message'] 	.= "<br />Login Credentials:<br>";
				$mail['message'] 	.= "Link: ".base_url('auth')."<br>";
				$mail['message'] 	.= "Username: ".$username."<br>";
				$mail['message'] 	.= "Password: ".$password."<br>";
				$mail['message'] 	.= "<br />Best regards,<br>";
				$mail['message'] 	.= "Support Team";
				
				$mail['subject'] 	= 'Welcome to '.$this->site->info('business_title');
				$mail['to'] 		= $email;
				
				if($this->site->sendmail($mail))
				{
					$this->session->set_flashdata('response', '<div class="alert alert-success">You have successfully registered as member, login your account.</div>');
					
					if(isset($_GET['mid']) && $this->mdl_users->is_username_valid($_GET['mid'])) redirect(base_url('page/members'));
						
					redirect(base_url('auth'));
				}
			}
		}
		$this->load->view('auth/register', $data);
	}
	
	
	
	
	
	
	public function update_status($uid)
	{
		$user = $this->ion_auth->user($uid)->row();
		if(!$user) show_404();
		
		$status 	= ($user->active == 1) ? 0 : 1;
		$str_status = ($status) ? 'activated' : 'blocked';
		$this->db->where('id',$uid);
		$this->db->update('users', array('active' => $status));
		
		$this->session->set_flashdata('response', '<div class="alert alert-success">'.$user->first_name.' '.$user->last_name.'\'s account has been '.$str_status.'.</div>');
		redirect(base_url('page'));
	}
	
	
	
	
	
	
	
	function _get_csrf_nonce()
	{
		$this->load->helper('string');
		$key   = random_string('alnum', 8);
		$value = random_string('alnum', 20);
		$this->session->set_flashdata('csrfkey', $key);
		$this->session->set_flashdata('csrfvalue', $value);

		return array($key => $value);
	}

	function _valid_csrf_nonce()
	{
		if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
			$this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}
