<?php
class mdl_users extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	
	
	
	public function update_member($uid)
	{
		$data = (object) $this->input->post();
		if(isset($data->update_account)) unset($data->update_account);
		
		$this->db->where('id',$uid);
		$this->db->update('users',$data);
		
		if(!$this->db->affected_rows())
		{
			$response = '<div class="alert alert-info">';
			$response .= '<p>No changes has been made.</p>';
			$response .= '</div>';
		}
		else
		{
			$response = '<div class="alert alert-success">';
			$response .= '<p>'.$data->first_name.' '.$data->last_name.'\'s account successfully updated.</p>';
			$response .= '</div>';
		}
		
		return $response;
	}	
	
	
	
	
	public function get_user($uid)
	{
		$this->db->where('id', $uid);
		$this->db->select("*, CONCAT(first_name,' ',last_name) AS full_name", FALSE);
		$query = $this->db->get('users');
		
		return ($query->num_rows()) ? $query->row() : false;
	}
	
	
	
	
	
	public function delete_user($uid)
	{
		$this->db->delete('balances', array('uid' => $uid));
		$query = $this->db->delete('users', array('id' => $uid));
		if($this->db->affected_rows())
		{
			$this->session->set_flashdata('response', '<div class="alert alert-success"><strong>SUCCESSFUL:</strong> Member successfully deleted.</div>');
			return true;
		}
		else
		{
			$this->session->set_flashdata('response', '<div class="alert alert-danger"><strong>ERROR:</strong> Unable to delete user please try again.</div>');
			return false;
		}
	}
	
	
	
 
	public function fetch_users($limit, $start)
	{		
		$where = $this->search_filter();
	
		//Count all result
		$this->db->where('users.id !=', $this->session->userdata('user_id'));
		$this->db->join('company', 'company.id = users.cid');
		if($where) $this->db->where($where, NULL, FALSE);
		$this->db->from('users');
		$data['total_rows'] = $this->db->count_all_results();
		
		
		$this->db->where('users.id !=', $this->session->userdata('user_id'));
		if($where) $this->db->where($where, NULL, FALSE);
		$this->db->select("
			users.id,
			users.active,
			users.username AS username,
			users.email,
			company.country_name,
			company.name AS company,
			CONCAT(first_name,' ',last_name) as full_name,
			DATE_FORMAT(date_registered,'%b, %d %Y %h:%i %p') as date_registered,
			DATE_FORMAT(expiration,'%b %d, %Y') as expiration_date,
		", FALSE);
		$this->db->join('company', 'company.id = users.cid');
		$this->db->order_by('users.id', 'DESC');
		$this->db->limit($limit, $start);
		
		$query 			= $this->db->get("users");
		$data['result'] = $query->result();
		
		
		if($this->input->get('download'))
		{
			$this->load->model('mdl_excel');
			$this->mdl_excel->download_reports($data['result']);
			redirect(current_url());
		}
		
		return (object) $data;
	}
	
	
	
	
	public function get_company($cid)
	{
		$this->db->where('id', $cid);
		$query = $this->db->get('company');
		
		if($query->num_rows())
			return $query->row();
		else
			return false;
	}
	
	
	
	public function get_companies()
	{
		$query = $this->db->get('company');
		return $query->result();
	}
	
	
	
	public function fetch_companies($limit, $start)
	{		
		$where 		= $this->search_filter();
	
		//Count all result
		if($where) $this->db->where($where, NULL, FALSE);
		$this->db->from('company');
		$data['total_rows'] = $this->db->count_all_results();
		
		
		if($where) $this->db->where($where, NULL, FALSE);
		$this->db->select("id,country_name, name,phone,address, DATE_FORMAT(expiration,'%b, %d %Y') as expiration", FALSE);
		
		$this->db->order_by('company.id', 'DESC');
		$this->db->limit($limit, $start);
		
		$query 			= $this->db->get("company");
		$data['result'] = $query->result();
		
		
		if($this->input->get('download'))
		{
			$this->load->model('mdl_excel');
			$this->mdl_excel->download_reports($data['result']);
			redirect(current_url());
		}
		
		return (object) $data;
	}
	
	
	
	
	
	
	private function search_filter()
	{
		$keyword 	= $this->input->get('keyword');
		$fields 	= explode(',',urldecode($this->input->get('field_search')));
		$like		= '';
		
		if(!$this->input->get('keyword') && !$this->input->get('filter_column')) return '';
		
		if($keyword)
		{
			$x = 1;
			foreach($fields as $field)
			{
				$like .= str_replace('-','.',$field)." LIKE '%$keyword%'";
				if($x < count($fields)) $like .= ' || ';
				$x++;
			}
		}
		
		
		if($this->input->get('filter_column'))
		{
			$x = 1;
			$inputs = array_filter($this->input->get());
			unset($inputs['filter_column']);
			
			foreach($inputs as $key => $value)
			{
				$like .= str_replace('-','.',$key)." LIKE '%$value%'";
				if($x < count($inputs)) $like .= ' || ';
				$x++;
			}
		}
		
		
		return $like ? '('.$like.')' : '';
	}
	
	
	
	
	public function api_login($username, $password)
	{
		if(empty($username))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Email address is required.', 'result' => '1');
		}
		elseif(empty($password))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Password is required.', 'result' => '2');
		}
		else
		{
			$this->db->select('users.*,users_groups.group_id, company.expiration AS expiration_date, company.name as company_name', FALSE);
			$this->db->where('email', $username);
			$this->db->where('users_groups.group_id !=', 1);
			$this->db->join('company', 'users.cid = company.id');
			$this->db->join('users_groups', 'users.id = users_groups.user_id');
			$query = $this->db->get('users');
			$this->ion_auth->clear_login_attempts($username);
			if($query->num_rows())
			{
				
				$user 		 = $query->row();
				$user->token = $this->token($user->id);
				
				if($this->ion_auth->is_max_login_attempts_exceeded($username)) {
					$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'The maximum number of login attempts has been reached. Please try again later.', 'result' => '');
				}
				elseif(!$this->ion_auth->hash_password_db($user->id, $password))
				{
					$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Password is incorrect.', 'result' => $password);
					$this->ion_auth->increase_login_attempts($username);
				}
				elseif((strtotime($user->expiration_date) - time()) <= 0)
				{
					$result = array( 'status' => 0, 'errortype' => 'warning', 'message' => 'Account is already expired.', 'result' => $user->expiration_date);
				}
				elseif($user->email != $username)
				{
					$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Invalid email and password.', 'result' => '5');
					$this->ion_auth->increase_login_attempts($username);
				}
				else
				{	
					//Add token to user field
					$this->db->where('id',$user->id);
					$this->db->update('users', array(
						'token' 		=> $user->token,
						'data_update' 	=> 0
					));
					
					$this->ion_auth->clear_login_attempts($username);
					
					$this->load->model('Mdl_observation');
					$location 			= $this->Mdl_observation->api_locations($user->cid, $user->id);

					$data['user'] 		= $user;
					$data['location'] 	= $location['result'];
					
					// if($user->group_id == 3){
						$data['members'] = $this->get_members($user->cid);
					// }
					
					$result = array( 'status' => 1, 'errortype' => 'success', 'message' => 'Login successful', 'result' => $data);
				}
			}
			else
			{
				$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Invalid email and password.', 'result' => '');
			}
		}
			
		return $result;
	}

	private function get_members($cid)
	{
		$query = $this->db->select("id, CONCAT(first_name,' ',last_name) AS full_name")
				 		  ->where('cid', $cid)
						  ->order_by('id', 'desc')
						  ->get('users');
				 
		return $query->result();
	}
	
	
	public function api_resetpassword($identity)
	{
		
		$query = $this->db->where('email', $identity)
		                  ->limit(1)
		    			  ->order_by('id', 'desc')
		                  ->get('users');
						  
		if ($query->num_rows() !== 1)
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Invalid email address.', 'result' => '');
		}
		else
		{
			$password		= $this->ion_auth->rand_string(8);
			$user 			= $query->row();
			$new_password  	= $this->ion_auth->hash_password($password, $user->salt);
			
			$data = array(
			    'password'		 	=> $new_password,
			    'remember_code' 	=> NULL,
			);
			
			$this->db->update('users', $data, array('id' => $user->id));
			
			if($this->db->affected_rows())
			{
				//Email message
				$mail['message'] 	= "Hello ".$user->first_name.' '.$user->last_name.",<br><br>";
				$mail['message'] 	.= "Your password has been reset, please check your login details below.<br>";
				$mail['message'] 	.= "<br /><br />Login Credentials:<br>";				
				$mail['message'] 	.= "Email Address: ".$user->email."<br>";
				$mail['message'] 	.= "Password: ".$password."<br><br />";
				$mail['message'] 	.= "Best regards,<br>";
				$mail['message'] 	.= "Support Team";
				
				$mail['subject'] 	= 'Welcome to '.$this->site->info('business_title');
				$mail['to'] 		= $user->email;
				$this->site->sendmail($mail);
				
				
				$this->load->library('email', array(
					'protocol'  => 'smtp',
					'smtp_host' => 'ssl://smtp.googlemail.com',
					'smtp_port' => '465',
					'smtp_user' => 'noreplytripoption@gmail.com',
					'smtp_pass' => 'Powercom888',
					'smtp_timeout' => '4',
					'mailtype'  => 'html',
				));
				
				$result = array( 'status' => 1, 'errortype' => 'success', 'message' => 'Password successfully reset, please check your email for your login details.', 'result' => '');
			}
			else
			{
				$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Unable to reset password, please try again.', 'result' => '');
			}
		}
		
		return $result;
	}
	
	
	
	
	
	public function api_update_user($id = '', $params = array())
	{
		
		$data = (object) $params['userData'];
		if(empty($id))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'User ID is required.', 'result' => '');
		}
		elseif(empty($data))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'No data submitted.', 'result' => '');
		}
		elseif(!isset($data) || empty($data->first_name))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'First name is required.', 'result' => '');
		}
		elseif(!isset($data->last_name) || empty($data->last_name))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Last name is required.', 'result' => '');
		}
		elseif(!isset($data->email) || empty($data->email))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Email address is required.', 'result' => '');
		}
		elseif (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Invalid email address.', 'result' => '');
		}
		elseif(!isset($data->company) || empty($data->company))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Company is required.', 'result' => '');
		}
		else
		{
			$this->db->where('id', $id);
			$query = $this->db->get('users');
			
			$this->db->where('id', $id);
			$this->db->select('email');
			$this->db->where('email !=', $data->email);
			$this->db->from('users');
			$is_email_exists = $this->db->count_all_results();
			
			$u_data = array(
				'first_name' 	=> $data->first_name,
				'last_name' 	=> $data->last_name,
				'company' 		=> $data->company,
				'email' 		=> $data->email,
				'address' 		=> $data->address
			);
			
			if(!$query->num_rows())
			{
				$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Invalid User ID.', 'result' => '');
			}
			elseif($is_email_exists)
			{
				$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Email address already exist.', 'result' => '');
			}
			else
			{
				//Password Change
				if(isset($params['passwordChange']))
				{
					$password = (object) $params['passwordChange'];
					
					if((isset($password->current_password) && !empty($password->current_password)) || (isset($password->new_password) && !empty($password->new_password)) || (isset($password->retype_new_password) && !empty($password->retype_new_password)))
					{
						if(!isset($password->current_password) && empty($password->current_password))
						{
							return array( 'status' => 0, 'errortype' => 'error', 'message' => 'Current password is required.', 'result' => '');
						}
						if(!$this->ion_auth->hash_password_db($id, $password->current_password))
						{
							return array( 'status' => 0, 'errortype' => 'error', 'message' => 'Invalid current password', 'result' => $password);
						}
						
						if(!isset($password->new_password) || empty($password->new_password))
						{
							return array( 'status' => 0, 'errortype' => 'error', 'message' => 'New password is required.', 'result' => '');
						}
						
						if(strlen($password->new_password) < 6)
						{
							return array( 'status' => 0, 'errortype' => 'error', 'message' => 'New password must be more than 6 characters.', 'result' => '');
						}
						
						if(!isset($password->retype_new_password) || empty($password->retype_new_password))
						{
							return array( 'status' => 0, 'errortype' => 'error', 'message' => 'Please retype your new password.', 'result' => '');
						}
						
						if($password->new_password != $password->retype_new_password)
						{
							return array( 'status' => 0, 'errortype' => 'error', 'message' => 'Retype password is not equal to new password.', 'result' => '');
						}
										
						$user 				= $query->row();
						$u_data['password'] = $this->ion_auth->hash_password($password->new_password, $user->salt);
					}
				}
	
	
				$this->db->update('users', $u_data, array('id' => $id));
				
				if($this->db->affected_rows())
				{
					$result = array( 'status' => 1, 'errortype' => 'success', 'message' => 'Account information successfully updated.', 'result' => '');
				}
				else
				{
					$result = array( 'status' => 0, 'errortype' => 'info', 'message' => 'No changes has been made.', 'result' => '' );
				}
			}
		}
		
		return $result;
	}
	
	
	
	
	
	public function api_sendmessage($data)
	{
		$data = (object) $data;
		//Email message
		$mail['message'] 	= "<strong>Name</strong>: ".$data->name."<br>";
		$mail['message'] 	.= "<strong>Contact Number</strong>: ".$data->contact_no."<br>";
		$mail['message'] 	.= "<strong>Email Address</strong>: ".$data->email."<br>";
		$mail['message'] 	.= "<strong>Designation</strong>: ".$data->designation."<br>";
		$mail['message'] 	.= "<strong>Department</strong>: ".$data->department."<br>";
		$mail['message'] 	.= "<strong>Hospital</strong>: ".$data->hospital."<br>";
		$mail['message'] 	.= "<strong>No. of Beds</strong>: ".$data->no_of_beds."<br>";
		$mail['message'] 	.= "<strong>Address</strong>: ".$data->address."<br>";
		$mail['message'] 	.= "<strong>Country</strong>: ".$data->country."<br><br><br />";
		$mail['message'] 	.= "--Message from hand hygiene app--";
		
		$mail['subject'] 	= 'Hand Hygiene Auditing Tool (HHAT) Subscription/Inquiry';
		$mail['to'] 		= 'martin.madrid@bbraun.com';
		$mail['from'] 		= array('email' => 'noreply@bbraun.com','name' => $data->name);
		if($this->site->sendmail($mail))
			$result = array( 'status' => 1, 'errortype' => 'success', 'message' => 'Your message was successfully sent.', 'result' => '');
		else
			$result = array( 'status' => 0, 'errortype' => 'info', 'message' => 'Unable to send your message, please try again.', 'result' => '');
	
		
		return $result;
	}
	
	
	
	public function api_getcompanyusers($uid)
	{
		$user = $this->get_user($uid);
		
		$this->db->select("CONCAT(first_name,' ',last_name) AS name, id, cid", FALSE);
		$this->db->where('cid', $user->cid);
		$query = $this->db->get('users');
				
		return array( 'status' => 1, 'errortype' => 'success', 'message' => '', 'result' => $query->result());
	}
	
	
	
	
	public function is_token_valid($token)
	{
		$this->db->where('token',$token);
		$query = $this->db->get('users');
		
		return ($query->num_rows()) ? $query->row()->id : false;
	}
	
	
	
	
	
	
	
	private function token($uid)
	{
		return sha1(rand(10000,1000000).time().$uid);
	}
}






