<?php
class Mdl_observation extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	public function __api_saveobservation($data, $uid)
	{
		$data 		= json_decode(json_encode($data));
		$location 	= $data->location;
		$healthcare = $data->healthWorker;
		$moment 	= $data->moment;
		
		$moment->stepOne 	= $data->stepOne;
		$moment->stepTwo 	= $data->stepTwo;
		$moment->maskType 	= $data->maskType;
		$moment->type 		= $data->compliance;
		$moment->dateTime 	= $data->dateTime;
		$moment->glove 		= isset($data->gloveCompliance) ? $data->gloveCompliance : '';
		$moment->gown 		= isset($data->gownCompliance) ? $data->gownCompliance : '';
		$moment->mask 		= isset($data->maskCompliance) ? $data->maskCompliance : '';
		
		
		if(empty($uid))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'User ID is required.', 'result' => '');
		}
		elseif(empty($data))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'No data submitted.', 'result' => '');
		}
		elseif(!isset($healthcare))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Healthcare worker is required.', 'result' => '');
		}
		elseif(!isset($healthcare->type) || empty($healthcare->type))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Healthcare worker is required.', 'result' => '');
		}
		elseif(!isset($location))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Locations is required.', 'result' => '');
		}
		elseif(!isset($location->levelOne) || empty($location->levelOne))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Location level 1 is required.', 'result' => '');
		}
		elseif(!isset($location->levelTwo) || empty($location->levelTwo))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Location level 2 is required.', 'result' => '');
		}
		elseif(!isset($location->levelThree) || empty($location->levelThree))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Location level 3 is required.', 'result' => '');
		}
		elseif(!isset($location->levelFour) || empty($location->levelFour))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Location level 4 is required.', 'result' => '');
		}
		elseif(!isset($moment->type) || empty($moment->type))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Moment is required.', 'result' => '');
		}
		elseif(!isset($moment->stepOne) || empty($moment->stepOne))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Hand hygiene compliance is required.', 'result' => '');
		}
		elseif(isset($data->resend_id) && $this->validate_resend($data->resend_id) > 0)
		{
			$result = array( 'status' => 1, 'errortype' => 'warning', 'message' => 'This observation already saved to server.', 'result' => 'resend');
		}
		else
		{	
			if(!isset($moment->stepTwo) || $moment->stepTwo == 'NA')
			{
				$glove 				= 'NA';
				$gown 				= 'NA';
				$mask 				= 'NA';
				$moment->maskType 	= 'NA';
			}
			else
			{
				$prefix = ($moment->type == 'Before Contact' || $moment->type == 'Before Procedure') ? 'Don on' : 'Remove';
				
				$glove 	= (isset($moment->glove) && $moment->glove) ? 	$prefix.' glove / Yes' 	: $prefix.' glove / No';
				$gown 	= (isset($moment->gown) && $moment->gown) ? 	$prefix.' gown / Yes' 	: $prefix.' gown / No';
				$mask 	= (isset($moment->mask) && $moment->mask) ? 	$prefix.' mask / Yes' 	: $prefix.' mask / No';
			}
			
			if($moment->type == 'After Environment')
			{
				$glove 				= 'NA';
				$gown 				= 'NA';
				$mask 				= 'NA';
				$moment->maskType 	= 'NA';
				$moment->stepTwo	= 'NA';
			}
			
			$healthcare->type 		= ($healthcare->type == 'Others' && isset($healthcare->otherType) &&  $healthcare->otherType != '') ? 				$healthcare->otherType : 		$healthcare->type; 
			$location->levelTwo 	= ($location->levelTwo == 'Others' && isset($location->levelTwoOther) &&  $location->levelTwoOther != '') ? 		$location->levelTwoOther : 		$location->levelTwo; 
			$location->levelThree 	= ($location->levelThree == 'Others' && isset($location->levelThreeOther) &&  $location->levelThreeOther != '') ? 	$location->levelThreeOther : 	$location->levelThree; 
			$location->levelFour 	= ($location->levelFour == 'Others' && isset($location->levelFourOther) &&  $location->levelFourOther != '') ? 		$location->levelFourOther : 	$location->levelFour; 
			
			
			$user = $this->Mdl_users->get_user($uid);
			
			$this->db->insert('observations', array(
				'uid' 					=> $uid,
				'cid'					=> $user->cid,
				'resend_id'				=> isset($data->resend_id) ? $data->resend_id : '',
				'hcw_title' 			=> $healthcare->type->id,
				'hcw_name' 				=> isset($healthcare->name) ? $healthcare->name : '',
				'organization' 			=> isset($data->organizationName) ? $data->organizationName : '',
				'moment'				=> $moment->type,
				'note'					=> isset($moment->note) ? $moment->note : '',
				'location_level1'		=> $location->levelOne->id,
				'location_level2'		=> $location->levelTwo->id,
				'location_level3'		=> $location->levelThree->id,
				'location_level4'		=> $location->levelFour->id,
				'hh_compliance'			=> isset($moment->stepOne) ? $moment->stepOne : 'No',
				'hh_compliance_type'	=> isset($moment->stepTwo) ? $moment->stepTwo : 'NA',
				'glove_compliance'		=> $glove,
				'gown_compliance'		=> $gown,
				'mask_compliance'		=> $mask,
				'mask_type'				=> isset($moment->maskType) ? $moment->maskType : 'NA',
				'date_registered'		=> date('Y-m-d H:i:s', strtotime($moment->dateTime))
			));
			
			if($this->db->affected_rows())
			{
				$this->load->model('Mdl_users');
				
				$user = $this->Mdl_users->get_user($uid);
				$this->db->where('cid', $user->cid);
				$this->db->from('observations');
				$stats['companyRecords'] = $this->db->count_all_results();
				
				$this->db->where('uid', $uid);
				$this->db->from('observations');
				$stats['accountRecords'] = $this->db->count_all_results();
				
				$s_uid = $this->ion_auth->get_users_groups($uid)->row()->id != 1 ? $uid : '';
				
				$stats['momentRecords'] = $this->api_countobservation($user->cid, $s_uid);
				
				$result = array( 'status' => 1, 'errortype' => 'success', 'message' => 'New observation successfully save to server.', 'result' => $stats);
			}
			else
			{
				$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Server Error.', 'result' => '');
			}
		}
		
		return $result;
	}
	
	public function api_saveobservation($data, $uid)
	{
		$data = json_decode(json_encode($data));
		$user = $this->ion_auth->user($uid)->row();
		
		if(empty($data))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'No data submitted.', 'result' => '');
		}
		elseif(!isset($data->uid) || empty($data->uid))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'User ID is required.', 'result' => '');
		}
		elseif(!isset($data->hcw_title))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Healthcare worker is required.', 'result' => '');
		}
		elseif(!isset($data->location_level1) || empty($data->location_level1))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Location level 1 is required.', 'result' => '');
		}
		elseif(!isset($data->location_level2) || empty($data->location_level2))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Location level 2 is required.', 'result' => '');
		}
		elseif(!isset($data->location_level3) || empty($data->location_level3))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Location level 3 is required.', 'result' => '');
		}
		elseif(!isset($data->location_level4) || empty($data->location_level4))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Location level 4 is required.', 'result' => '');
		}
		elseif(!isset($data->moment) || empty($data->moment))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Moment is required.', 'result' => '');
		}
		elseif(!isset($data->hh_compliance) || empty($data->hh_compliance))
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Hand hygiene compliance is required.', 'result' => '');
		}
		elseif(isset($data->resend_id) && $this->validate_resend($data->resend_id) > 0)
		{
			$result = array( 'status' => 1, 'errortype' => 'warning', 'message' => 'This observation already saved to server.', 'result' => 'resend');
		}
		else
		{	
			if(!isset($data->hh_compliance_type) || $data->hh_compliance_type == 'NA')
			{
				$data->glove_compliance = $data->gown_compliance = $data->mask_compliance = $data->mask_type = 'NA';
				$data->mask_type		= 'NA';
			}
			else
			{
				$prefix = ($data->moment ==  1 || $data->moment == 2) ? 'Don on' : 'Remove';
				$data->glove_compliance = (isset($data->glove_compliance) && $data->glove_compliance) ? $prefix.' glove / Yes' 	: $prefix.' glove / No';
				$data->gown_compliance 	= (isset($data->gown_compliance) && $data->gown_compliance) ? 	$prefix.' gown / Yes' 	: $prefix.' gown / No';
				$data->mask_compliance 	= (isset($data->mask_compliance) && $data->mask_compliance) ? 	$prefix.' mask / Yes' 	: $prefix.' mask / No';
				$data->mask_type		= ($data->mask_compliance == 'Don on mask / Yes' || $data->mask_compliance == 'Remove mask / Yes') ? $data->mask_type : 'NA';
			}
			
			$this->db->insert('observations', array(
				'uid' 					=> $data->uid,
				'cid'					=> $data->cid,
				'resend_id'				=> !isset($data->resend_id) ? '' : $data->resend_id,
				'hcw_title' 			=> $data->hcw_title,
				'hcw_name' 				=> !isset($data->hcw_name) 		? '' : $data->hcw_name,
				'organization' 			=> !isset($data->organization) 	? '' : $data->organization,
				'moment'				=> $data->moment,
				'note'					=> !isset($data->note) 			? '' : $data->note,
				'location_level1'		=> $data->location_level1,
				'location_level2'		=> $data->location_level2,
				'location_level3'		=> $data->location_level3,
				'location_level4'		=> $data->location_level4,
				'hh_compliance'			=> $data->hh_compliance,
				'hh_compliance_type'	=> !isset($data->hh_compliance_type) ? '' : $data->hh_compliance_type,
				'glove_compliance'		=> $data->glove_compliance,
				'gown_compliance'		=> $data->gown_compliance,
				'mask_compliance'		=> $data->mask_compliance,
				'mask_type'				=> $data->mask_type,
				'date_registered'		=> date('Y-m-d H:i:s', strtotime($data->date_registered))
			));
			
			if($this->db->affected_rows())
			{
				$this->db->where('cid', $user->cid);
				$this->db->from('observations');
				$stats['companyRecords'] = $this->db->count_all_results();
				
				$this->db->where('uid', $uid);
				$this->db->from('observations');
				$stats['accountRecords'] = $this->db->count_all_results();
				$stats['momentRecords'] = $this->api_countobservation($user->cid, $uid);
				
				$result = array( 'status' => 1, 'errortype' => 'success', 'message' => 'New observation successfully save to server.', 'result' => $stats);
			}
			else
			{
				$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'Server Error.', 'result' => '');
			}
		}
		
		return $result;
	}
	
	
	public function api_multiobservesend($data, $uid)
	{
		$data 	= json_decode(json_encode($data));
		$user 	= $this->ion_auth->user($uid)->row();
		$stats	= array();
		$this->db->insert('observations', $data);
		
		if($this->db->affected_rows())
		{
			$this->db->where('cid', $user->cid);
			$this->db->from('observations');
			$stats['companyRecords'] = $this->db->count_all_results();
			
			$this->db->where('uid', $uid);
			$this->db->from('observations');
			$stats['accountRecords'] 	= $this->db->count_all_results();
			$stats['momentRecords'] 	= $this->api_countobservation($user->cid, $uid);
			
			return array( 'status' => 1, 'errortype' => 'success', 'message' => 'New observation successfully save to server.', 'result' => $stats);
		}
		else
			return array( 'status' => 0, 'errortype' => 'error', 'message' => 'Server Error.', 'result' => '');
	}
	
	public function api_countobservation($cid, $uid = '')
	{
		$user 		= $this->ion_auth->user($uid)->row();
		$date_from 	= strtotime($this->input->get('dateFrom'));
		$date_to 	= strtotime($this->input->get('dateTo'));
		$group_id	= $this->ion_auth->get_users_groups($uid)->row()->id;
		
		if($this->input->get('department') > 0) 	$this->db->where('location_level1', $this->input->get('department'));
		if($this->input->get('subDepartment') > 0) 	$this->db->where('location_level2', $this->input->get('subDepartment'));
		if($this->input->get('ward') > 0) 			$this->db->where('location_level3', $this->input->get('ward'));
		if($this->input->get('patient') > 0) 		$this->db->where('location_level4', $this->input->get('patient'));
		if($this->input->get('healthcare') > 0) 	$this->db->where('hcw_title', $this->input->get('healthcare'));
		
		if($this->input->get('dateFrom') != '')
			$this->db->where('observations.date_registered >=',date('Y-m-d 00:00:00',$date_from));
			
		if($this->input->get('dateTo') != '')
			$this->db->where('observations.date_registered <=',date('Y-m-d 23:59:59',$date_to));
				
		if($group_id == 2)
			$this->db->where('uid', $uid);
		
		if($this->input->get('auditor') != '')	
			$this->db->where('uid', $this->input->get('auditor'));
		
		$this->db->where('cid', $cid);
		$this->db->select('moment1, moment2, moment3, moment4, moment5, hh_compliance');
		$query = $this->db->get('observations');
		
		$passedFailed 	= array('passed' => 0, 'failed' => 0);
		$momentRecords 	= array( 1 => $passedFailed, 2 => $passedFailed, 3 => $passedFailed, 4 => $passedFailed, 5 => $passedFailed);
		
		foreach($query->result() as $data)
		{
			for($x = 1; $x <=5; $x++){
				$moment = 'moment'.$x; 
				if($data->$moment == $x)
				{
					if($data->hh_compliance == 'missed')
						$momentRecords[$x]['failed']++;
					else
						$momentRecords[$x]['passed']++;
				}
			}
		}
		return $momentRecords;
	}
	
	public function validate_resend($id)
	{
		$this->db->where('resend_id',$id);
		$this->db->select('id');
		$this->db->from('observations');
		return $this->db->count_all_results();
	}
	
	public function api_getobservation($uid, $type)
	{
		$data 		= array();
		$user 		= $this->ion_auth->user($uid)->row();
		$date_from 	= strtotime($this->input->get('dateFrom'));
		$date_to 	= strtotime($this->input->get('dateTo'));
		$locations	= $this->get_all_locations($user->cid);
		
		if($this->input->get('department') > 0) 	$this->db->where('location_level1', $this->input->get('department'));
		if($this->input->get('subDepartment') > 0) 	$this->db->where('location_level2', $this->input->get('subDepartment'));
		if($this->input->get('ward') > 0) 			$this->db->where('location_level3', $this->input->get('ward'));
		if($this->input->get('patient') > 0) 		$this->db->where('location_level4', $this->input->get('patient'));
		if($this->input->get('healthcare') > 0) 	$this->db->where('hcw_title', $this->input->get('healthcare'));
		
		if($this->input->get('dateFrom') != '')
			$this->db->where('observations.date_registered >=',date('Y-m-d 00:00:00',$date_from));
			
		if($this->input->get('dateTo') != '')
			$this->db->where('observations.date_registered <=',date('Y-m-d 23:59:59',$date_to));
		
		if($this->input->get('auditor') != '')	
			$this->db->where('uid', $this->input->get('auditor'));
		else {
				$this->db->where('observations.cid', $user->cid);

		}

		// if($type == 'user')			
		// 	$this->db->where('uid', $uid);

		$this->db->join('users as u1', 'observations.uid = u1.id');
		
		$this->db->select("
			CONCAT(u1.first_name,' ',u1.last_name) AS full_name,
			observations.*, 
			DATE_FORMAT(observations.date_registered, '%b %d, %Y') AS date_registered,
			DATE_FORMAT(observations.date_registered, '%b %d, %Y %h:%i %p') AS datetime,
		",FALSE);
		$this->db->order_by('observations.date_registered', 'ASC');
		$query = $this->db->get('observations');

		if($query->num_rows())
		{
			$x = 0;
			$date = '';
			foreach($query->result() as $row)
			{		
				$return_data[$x] 					= $row;
				$return_data[$x]->hcw_title 		= $locations[$row->hcw_title]['name'];
				$return_data[$x]->location_level1 	= $locations[$row->location_level1]['name'];
				$return_data[$x]->location_level2 	= $locations[$row->location_level2]['name'];
				$return_data[$x]->location_level3 	= $locations[$row->location_level3]['name'];
				$return_data[$x]->location_level4 	= $locations[$row->location_level4]['name'];
				
				if($date != $row->date_registered)
				{
					$return_data[$x]->header_date 	= date('M j, Y',strtotime($row->date_registered));
					$date							= $row->date_registered;
				}else
					$return_data[$x]->header_date = '';
					
				$x++;
			}
			
			$data['data'] 			= $return_data;
			$data['date']['from'] 	= date('M j, Y', $date_from);
			$data['date']['to'] 	= date('M j, Y', $date_to);
			
			if($this->input->get('download'))
			{
				return $this->api_sendmailreports($return_data, $uid, $this->input->get('download'));				
			}
		}
		else
		{
			$data['data'] 	= array();
			$data['date']['from'] 	= date('M j, Y', $date_from);
			$data['date']['to'] 	= date('M j, Y', $date_to);
		}
		
		return array( 'status' => 1, 'errortype' => 'success', 'message' => '', 'result' => $data);
	}
	
	
	public function get_all_locations($cid)
	{
		$this->db->where(array('cid' => $cid));
		$query = $this->db->get('locations');
		
		foreach($query->result() as $loc)
		{
			$result[$loc->id] = array('name' => $loc->name, 'id' => $loc->id);
		}
		
		return $result;
	}
	
	public function api_sendmailreports($data, $uid, $type)
	{
		if(count($data) == 0)
		{
			$result = array( 'status' => 0, 'errortype' => 'error', 'message' => 'No data available.', 'result' => '');
		}
		else
		{
			$this->load->library('email', array(
				'protocol'  => 'smtp',
				'smtp_host' => 'ssl://smtp.googlemail.com',
				'smtp_port' => '465',
				'smtp_user' => 'noreplytripoption@gmail.com',
				'smtp_pass' => 'Powercom888',
				'smtp_timeout' => '4',
				'mailtype'  => 'html',
			));
			
			if($type == 1 || $type == 3) $pdf 	= $this->api_pdfreports($data);
			if($type == 2 || $type == 3) $excel = $this->api_excelreports($data);
				
			$user = $this->ion_auth->user($uid)->row();
			
			$this->email->set_newline("\r\n");
			$this->email->from($this->site->info('support_email'), $this->site->info('business_title'));
			$this->email->to($user->email);
			$this->email->subject('Hand hygiene observations reports '.date('m/d/Y'));
			$this->email->message('Hand hygiene report');
	
			if(isset($pdf)) 	$this->email->attach($pdf);
			if(isset($excel)) 	$this->email->attach($excel);

			$this->email->send();
	
			if(isset($pdf) && file_exists($pdf)) 		unlink($pdf);
			if(isset($excel) && file_exists($excel)) 	unlink($excel);
			
			$result = array( 'status' => 1, 'errortype' => 'success', 'message' => 'Report successfully sent, please check your email address.', 'result' => '');
		}
		
		return $result;
	}
	
	public function api_pdfreports($data)
	{
		return $this->api_excelreports($data);
		// if(count($data) == 0) return false;
		
		// $data_array 	= json_decode(json_encode($data));
		// $param['data'] 	= $data_array;
		// $date_from 	= date('m-d-Y', strtotime($this->input->get('dateFrom')));
		// $date_to 	= date('m-d-Y', strtotime($this->input->get('dateTo')));
		
		// $html = $this->load->view('report-table', $param, TRUE);
			
		// $this->load->library('Pdf');
		// $pdf = new Pdf('L', 'cm', 'REPORT', true, 'UTF-8', true);
		// $pdf->SetCreator(PDF_CREATOR);
		// $pdf->SetAuthor('B. Braun');
		// $pdf->SetTitle('Compliance Data');
		// $pdf->setPrintHeader(false);
		// $pdf->setPrintFooter(false);
		// $pdf->SetFontSize(10);
		// $pdf->AddPage('L', 'REPORT');
	
		// $pdf->writeHTML($html, true, false, true, false, '');
		// $file = FCPATH.'assets/uploads/HHAT_COMPLIANCE_DATA_'.rand(1000,9999).'_'.$date_from.' to '.$date_to.'.pdf';
		// $pdf->Output($file, 'F');
		
		// return $file;
	}
	
	public function api_excelreports($data)
	{
		if(count($data) == 0) return false;
		
		$arr_HcwType = array();
		$arr_Ward = array();
		$arr_Department = array();
		$arr_Facility = array();
		$arr_Service = array();
		$arr_Indication = array();

		//Header	
		$contents['A1'] = '';
		$contents['B1'] = '';
		$contents['D1'] = 'LOCATION LEVEL';
		
		$contents['H1'] = 'HEALTHCARE WORKER';
		
		$contents['I1'] = '';
		$contents['J1'] = 'HAND HYGIENE COMPLIANCE';
		$contents['K1'] = '';
		$contents['L1'] = '';
		$contents['M1'] = '';
		$contents['N1'] = '';
		
		$contents['O1'] = '';
		$contents['P1'] = '';
		$contents['Q1'] = 'Occupational';
		

		$contents['A2'] = 'Date & Time';
		$contents['B2'] = 'Auditor';
		//$contents['C2'] = 'Branch';
		
		$contents['D2'] = '1';
		$contents['E2'] = '2';
		$contents['F2'] = '3';
		$contents['G2'] = '4';
		// $contents['D2'] = 'Facility';
		// $contents['E2'] = 'Department';
		// $contents['F2'] = 'Ward';
		// $contents['G2'] = 'Service';
		
		$contents['H2'] = 'Title';
		$contents['I2'] = 'Name';
		
		$contents['J2'] = 'Moment';
		
		$contents['K2'] = '';		
		$contents['L2'] = '';
		$contents['M2'] = '';
		$contents['N2'] = '';
		
		$contents['O2'] = 'Action';
		$contents['P2'] = 'Result';
		$contents['Q2'] = 'Exposure Risk';
		
		$contents['R2'] = 'GLOVES';
		$contents['S2'] = 'GOWN';
		$contents['T2'] = 'MASK';
		$contents['U2'] = 'Mask Type';
		$contents['V2'] = 'Notes';
		
		//Header
		$this->load->library('excel');
		$letters 	= range('A', 'Z');
		$i 			= 3;
		foreach($data as $items)
		{
			$contents['A'.$i] = $items->datetime;
			$contents['B'.$i] = $items->full_name;
			$contents['C'.$i] = $items->organization;
			$contents['D'.$i] = $items->location_level1;
			$contents['E'.$i] = $items->location_level2;
			$contents['F'.$i] = $items->location_level3;
			$contents['G'.$i] = $items->location_level4;
			$contents['H'.$i] = $items->hcw_title;
			$contents['I'.$i] = $items->hcw_name;
			$contents['J'.$i] = $items->moment1;
			$contents['K'.$i] = $items->moment2;
			$contents['L'.$i] = $items->moment3;
			$contents['M'.$i] = $items->moment4;
			$contents['N'.$i] = $items->moment5;
			$contents['O'.$i] = $items->hh_compliance;
			$contents['P'.$i] = $items->hh_compliance == 'missed' ? 'Failed' : 'Passed';
			$contents['Q'.$i] = $items->hh_compliance_type;
			$contents['R'.$i] = $items->glove_compliance;
			$contents['S'.$i] = $items->gown_compliance;
			$contents['T'.$i] = $items->mask_compliance;
			$contents['U'.$i] = $items->mask_type;
			$contents['V'.$i] = $items->note;
			
			if (!in_array($items->hcw_title, $arr_HcwType)) {
				array_push($arr_HcwType, $items->hcw_title);
			}
			if (!in_array($items->location_level1, $arr_Facility)) {
				array_push($arr_Facility, $items->location_level1);
			}
			if (!in_array($items->location_level2, $arr_Department)) {
				array_push($arr_Department, $items->location_level2);
			}
			if (!in_array($items->location_level3, $arr_Ward)) {
				array_push($arr_Ward, $items->location_level3);
			}
			if (!in_array($items->location_level4, $arr_Service)) {
				array_push($arr_Service, $items->location_level4);
			}


			if($items->moment1 != '') {
				if (!in_array($items->moment1, $arr_Indication)) {
					array_push($arr_Indication, $items->moment1);
				}
			}
			if($items->moment2 != '') {
				if (!in_array($items->moment2, $arr_Indication)) {
					array_push($arr_Indication, $items->moment2);
				}
			}
			if($items->moment3 != '') {
				if (!in_array($items->moment3, $arr_Indication)) {
					array_push($arr_Indication, $items->moment3);
				}
			}
			if($items->moment4 != '') {
				if (!in_array($items->moment4, $arr_Indication)) {
					array_push($arr_Indication, $items->moment4);
				}
			}
			if($items->moment5 != '') {
				if (!in_array($items->moment5, $arr_Indication)) {
					array_push($arr_Indication, $items->moment5);
				}
			}

			$i++;
		}
		
		sort($arr_Indication);

		$this->excel->getProperties()->setCreator('Hand Hygiene Auditing Tool');
		$this->excel->getProperties()->setLastModifiedBy('RocketSpin.ph');
		$this->excel->getProperties()->setTitle('HHAT Compliance Data');
		$this->excel->getProperties()->setSubject('HHAT Reports');
		$this->excel->getProperties()->setDescription('System Generated Reports');

		$this->excel->getActiveSheet()->setTitle('Details');
		$this->excel->getActiveSheet()->getDefaultStyle()->getFont()->setSize(11)->setName('Arial');
		$this->excel->getActiveSheet()->getStyle("A1:Z2")->getFont()->setBold(true);
		
		$this->excel->setActiveSheetIndex(0)->mergeCells('D1:G1');
		$this->excel->setActiveSheetIndex(0)->mergeCells('B2:C2');
		$this->excel->setActiveSheetIndex(0)->mergeCells('H1:I1');
		$this->excel->setActiveSheetIndex(0)->mergeCells('J1:P1');
		$this->excel->setActiveSheetIndex(0)->mergeCells('J2:N2');
		
		$this->excel->getActiveSheet()->getStyle('A1:Z2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$this->excel->getActiveSheet()->getStyle('A1:V2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$this->excel->getActiveSheet()->getStyle('A1:V2')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));
		
		$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(19.33);
		$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(15.17);
		$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(9.17);
		$this->excel->getActiveSheet()->getColumnDimension('D')->setWidth(14.33);
		$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(10.17);
		$this->excel->getActiveSheet()->getColumnDimension('F')->setWidth(10.17);
		$this->excel->getActiveSheet()->getColumnDimension('G')->setWidth(10.17);
		$this->excel->getActiveSheet()->getColumnDimension('H')->setWidth(10.17);
		$this->excel->getActiveSheet()->getColumnDimension('I')->setWidth(18.00);
		$this->excel->getActiveSheet()->getColumnDimension('J')->setWidth(4.17);
		$this->excel->getActiveSheet()->getColumnDimension('K')->setWidth(4.17);
		$this->excel->getActiveSheet()->getColumnDimension('L')->setWidth(4.17);
		$this->excel->getActiveSheet()->getColumnDimension('M')->setWidth(4.17);
		$this->excel->getActiveSheet()->getColumnDimension('N')->setWidth(4.17);
		$this->excel->getActiveSheet()->getColumnDimension('O')->setWidth(8.0);
		$this->excel->getActiveSheet()->getColumnDimension('P')->setWidth(7.17);
		$this->excel->getActiveSheet()->getColumnDimension('Q')->setWidth(13.67);
		$this->excel->getActiveSheet()->getColumnDimension('R')->setWidth(7.67);
		$this->excel->getActiveSheet()->getColumnDimension('S')->setWidth(7.67);
		$this->excel->getActiveSheet()->getColumnDimension('T')->setWidth(7.67);
		$this->excel->getActiveSheet()->getColumnDimension('U')->setWidth(12.83);
		$this->excel->getActiveSheet()->getColumnDimension('V')->setWidth(29.17);
	
		$mergectr = 3;
		foreach($contents as $key => $item)
		{
			$this->excel->getActiveSheet()->setCellValue($key, $item);	
			$this->excel->getActiveSheet()->mergeCells('B'.$mergectr.':C'.$mergectr.'');
			$mergectr++;
		}
		
		$date_from 	= date('m-d-Y', strtotime($this->input->get('dateFrom')));
		$date_to 	= date('m-d-Y', strtotime($this->input->get('dateTo')));
		


		// START Ronald code here

		$ews3 = $this->excel->createSheet(2);
		$ews3->setTitle('Moment-HCW');
		$ews3->mergeCells('B1:D1');
		$ews3->setCellValue('B1', 'Moment 1');
		$ews3->mergeCells('E1:G1');
		$ews3->setCellValue('E1', 'Moment 2');
		$ews3->mergeCells('H1:J1');
		$ews3->setCellValue('H1', 'Moment 3');
		$ews3->mergeCells('K1:M1');
		$ews3->setCellValue('K1', 'Moment 4');
		$ews3->mergeCells('N1:P1');
		$ews3->setCellValue('N1', 'Moment 5');

		$ews3->setCellValue('B2', 'Passed');
		$ews3->setCellValue('C2', 'Total');
		$ews3->setCellValue('D2', '%');
		$ews3->setCellValue('E2', 'Passed');
		$ews3->setCellValue('F2', 'Total');
		$ews3->setCellValue('G2', '%');
		$ews3->setCellValue('H2', 'Passed');
		$ews3->setCellValue('I2', 'Total');
		$ews3->setCellValue('J2', '%');
		$ews3->setCellValue('K2', 'Passed');
		$ews3->setCellValue('L2', 'Total');
		$ews3->setCellValue('M2', '%');
		$ews3->setCellValue('N2', 'Passed');
		$ews3->setCellValue('O2', 'Total');
		$ews3->setCellValue('P2', '%');

		$ews3->getStyle('A1:P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews3->getStyle('A1:P2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews3->getStyle('A1:P2')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

		$counterHcw = 3;
		foreach ($arr_HcwType as $hcwUnique) {
		    $ews3->setCellValue('A'.$counterHcw, $hcwUnique);
		    $ews3->setCellValue('B'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$P$3:$P$50000, "Passed", Details!$J$3:$J$50000, 1)');
		    $ews3->setCellValue('C'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$J$3:$J$50000, 1)');
			$ews3->setCellValue('d'.$counterHcw, '=IF(OR(b'.$counterHcw.'=0,c'.$counterHcw.'=0),"",b'.$counterHcw.'/c'.$counterHcw.')');
			$ews3->getStyle('D'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		    $ews3->setCellValue('E'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$P$3:$P$50000, "Passed", Details!$K$3:$K$50000, 2)');
		    $ews3->setCellValue('F'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$K$3:$K$50000, 2)');
			$ews3->setCellValue('g'.$counterHcw, '=IF(OR(e'.$counterHcw.'=0,f'.$counterHcw.'=0),"",e'.$counterHcw.'/f'.$counterHcw.')');
			$ews3->getStyle('G'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		    $ews3->setCellValue('H'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$P$3:$P$50000, "Passed", Details!$L$3:$L$50000, 3)');
		    $ews3->setCellValue('I'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$L$3:$L$50000, 3)');
			$ews3->setCellValue('j'.$counterHcw, '=IF(OR(h'.$counterHcw.'=0,i'.$counterHcw.'=0),"",h'.$counterHcw.'/i'.$counterHcw.')');
			$ews3->getStyle('J'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		    $ews3->setCellValue('K'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$P$3:$P$50000, "Passed", Details!$M$3:$M$50000, 4)');
		    $ews3->setCellValue('L'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$M$3:$M$50000, 4)');
			$ews3->setCellValue('m'.$counterHcw, '=IF(OR(k'.$counterHcw.'=0,l'.$counterHcw.'=0),"",k'.$counterHcw.'/l'.$counterHcw.')');
			$ews3->getStyle('M'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		    $ews3->setCellValue('N'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$P$3:$P$50000, "Passed", Details!$N$3:$N$50000, 5)');
		    $ews3->setCellValue('O'.$counterHcw, '=COUNTIFS(Details!$H$3:$H$50000, "'.$hcwUnique.'", Details!$N$3:$N$50000, 5)');
			$ews3->setCellValue('p'.$counterHcw, '=IF(OR(n'.$counterHcw.'=0,o'.$counterHcw.'=0),"",n'.$counterHcw.'/o'.$counterHcw.')');
			$ews3->getStyle('P'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

			$counterHcw++;
		}
		$ews3->setCellValue('b'.$counterHcw, '=SUM(b3:b'.($counterHcw-1).')');
		$ews3->setCellValue('c'.$counterHcw, '=SUM(c3:c'.($counterHcw-1).')');
		$ews3->setCellValue('d'.$counterHcw, '=IF(OR(b'.$counterHcw.'=0,c'.$counterHcw.'=0),"",b'.$counterHcw.'/c'.$counterHcw.')');
		$ews3->getStyle('D'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		$ews3->setCellValue('E'.$counterHcw, '=SUM(E3:E'.($counterHcw-1).')');
		$ews3->setCellValue('F'.$counterHcw, '=SUM(F3:F'.($counterHcw-1).')');
		$ews3->setCellValue('g'.$counterHcw, '=IF(OR(e'.$counterHcw.'=0,f'.$counterHcw.'=0),"",e'.$counterHcw.'/f'.$counterHcw.')');
		$ews3->getStyle('G'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		$ews3->setCellValue('H'.$counterHcw, '=SUM(H3:H'.($counterHcw-1).')');
		$ews3->setCellValue('I'.$counterHcw, '=SUM(I3:I'.($counterHcw-1).')');
		$ews3->setCellValue('j'.$counterHcw, '=IF(OR(h'.$counterHcw.'=0,i'.$counterHcw.'=0),"",h'.$counterHcw.'/i'.$counterHcw.')');
		$ews3->getStyle('J'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		$ews3->setCellValue('K'.$counterHcw, '=SUM(K3:K'.($counterHcw-1).')');
		$ews3->setCellValue('L'.$counterHcw, '=SUM(L3:L'.($counterHcw-1).')');
		$ews3->setCellValue('m'.$counterHcw, '=IF(OR(k'.$counterHcw.'=0,l'.$counterHcw.'=0),"",k'.$counterHcw.'/l'.$counterHcw.')');
		$ews3->getStyle('M'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		$ews3->setCellValue('N'.$counterHcw, '=SUM(N3:N'.($counterHcw-1).')');
		$ews3->setCellValue('O'.$counterHcw, '=SUM(O3:O'.($counterHcw-1).')');
		$ews3->setCellValue('p'.$counterHcw, '=IF(OR(n'.$counterHcw.'=0,o'.$counterHcw.'=0),"",n'.$counterHcw.'/o'.$counterHcw.')');
		$ews3->getStyle('P'.$counterHcw)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

		$centerStyle = array(
	        'alignment' => array(
	            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
	        )
	    );

	    $ews3->getStyle('B1:P1')->applyFromArray($centerStyle);
	    $ews3->getStyle('B'.$counterHcw.':P'.$counterHcw)->applyFromArray(array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        ));
        $ews3->getStyle('B3:P3')->applyFromArray(array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        ));





        $ews4 = $this->excel->createSheet(3);
		$ews4->setTitle('LocLevel1');

		$ews4->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews4->getStyle('A1:A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews4->getStyle('A1:A2')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

		//set wards first
		sort($arr_Facility);
		$facilityCtr = 3;
		$facilityLength = 0;
		foreach($arr_Facility as $facilityUniqueLoc) {
		    $ews4->setCellValue('A'.$facilityCtr, $facilityUniqueLoc);
		    $facilityCtr++;
		    $facilityLength++;
		}


		$counterLoc1facility = 1;

		$columnIdxfacility = 1;
		$columnLetterfacility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility); 
		$columnLetter1facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+1); 
		$columnLetter2facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+2); 
		foreach ($arr_HcwType as $hcwUniqueLocfacility) {
			//B1
		    $ews4->setCellValue($columnLetterfacility.$counterLoc1facility, $hcwUniqueLocfacility);
		    $ews4->setCellValue($columnLetterfacility.($counterLoc1facility+1), 'Passed');

		    $ews4->setCellValue($columnLetter1facility.($counterLoc1facility+1), 'Total');

		    $ews4->setCellValue($columnLetter2facility.($counterLoc1facility+1), '%');

			//D1
			$ews4->mergeCells($columnLetterfacility.$counterLoc1facility.':'.$columnLetter2facility.$counterLoc1facility);
			$ews4->getStyle($columnLetterfacility.$counterLoc1facility.':'.$columnLetter2facility.$counterLoc1facility)->applyFromArray($centerStyle);

			//COLORS
			$ews4->getStyle($columnLetterfacility.$counterLoc1facility)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews4->getStyle($columnLetterfacility.$counterLoc1facility)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews4->getStyle($columnLetterfacility.$counterLoc1facility)->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews4->getStyle($columnLetterfacility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews4->getStyle($columnLetterfacility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews4->getStyle($columnLetterfacility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews4->getStyle($columnLetter1facility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews4->getStyle($columnLetter1facility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews4->getStyle($columnLetter1facility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews4->getStyle($columnLetter2facility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews4->getStyle($columnLetter2facility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews4->getStyle($columnLetter2facility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			//populate rows below
			$ctrLocfacility = 3;
			for ($r = 1; $r <= $facilityLength; $r++) {
			    //passed
			    $ews4->setCellValue($columnLetterfacility.$ctrLocfacility, '=COUNTIFS(Details!$H$3:$H$50000, LocLevel1!'.$columnLetterfacility.'1, Details!$P$3:$P$50000, "Passed", Details!$D$3:$D$50000, LocLevel1!A'.$ctrLocfacility.')');

			    //total
			    $ews4->setCellValue($columnLetter1facility.$ctrLocfacility, '=COUNTIFS(Details!$H$3:$H$50000, LocLevel1!'.$columnLetterfacility.'1, Details!$D$3:$D$50000, LocLevel1!A'.$ctrLocfacility.')');

			    //compliance
				$ews4->setCellValue($columnLetter2facility.$ctrLocfacility, '=IF(OR('.$columnLetterfacility.$ctrLocfacility.'=0,'.$columnLetter1facility.$ctrLocfacility.'=0),"",'.$columnLetterfacility.$ctrLocfacility.'/'.$columnLetter1facility.$ctrLocfacility.')');
				$ews4->getStyle($columnLetter2facility.$ctrLocfacility)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

			    $ctrLocfacility++;
			} 



			$columnIdxfacility = $columnIdxfacility + 3;
			$columnLetterfacility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility); 
			$columnLetter1facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+1); 
			$columnLetter2facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+2); 

		}




        $ews5 = $this->excel->createSheet(4);
		$ews5->setTitle('LocLevel2');

		$ews5->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews5->getStyle('A1:A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews5->getStyle('A1:A2')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));


		//set wards first
		sort($arr_Department);
		$departmentCtr = 3;
		$departmentLength = 0;
		foreach($arr_Department as $departmentUniqueLoc) {
		    $ews5->setCellValue('A'.$departmentCtr, $departmentUniqueLoc);
		    $departmentCtr++;
		    $departmentLength++;
		}


		$counterLoc1department = 1;

		$columnIdxdepartment = 1;
		$columnLetterdepartment  = PHPExcel_Cell::stringFromColumnIndex($columnIdxdepartment); 
		$columnLetter1department  = PHPExcel_Cell::stringFromColumnIndex($columnIdxdepartment+1); 
		$columnLetter2department  = PHPExcel_Cell::stringFromColumnIndex($columnIdxdepartment+2); 
		foreach ($arr_HcwType as $hcwUniqueLocdepartment) {
			//B1
		    $ews5->setCellValue($columnLetterdepartment.$counterLoc1department, $hcwUniqueLocdepartment);
		    $ews5->setCellValue($columnLetterdepartment.($counterLoc1department+1), 'Passed');

		    $ews5->setCellValue($columnLetter1department.($counterLoc1department+1), 'Total');

		    $ews5->setCellValue($columnLetter2department.($counterLoc1department+1), '%');

			//D1
			$ews5->mergeCells($columnLetterdepartment.$counterLoc1department.':'.$columnLetter2department.$counterLoc1department);
			$ews5->getStyle($columnLetterdepartment.$counterLoc1department.':'.$columnLetter2department.$counterLoc1department)->applyFromArray($centerStyle);

			//COLORS
			$ews5->getStyle($columnLetterdepartment.$counterLoc1department)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews5->getStyle($columnLetterdepartment.$counterLoc1department)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews5->getStyle($columnLetterdepartment.$counterLoc1department)->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews5->getStyle($columnLetterdepartment.($counterLoc1department+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews5->getStyle($columnLetterdepartment.($counterLoc1department+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews5->getStyle($columnLetterdepartment.($counterLoc1department+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews5->getStyle($columnLetter1department.($counterLoc1department+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews5->getStyle($columnLetter1department.($counterLoc1department+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews5->getStyle($columnLetter1department.($counterLoc1department+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews5->getStyle($columnLetter2department.($counterLoc1department+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews5->getStyle($columnLetter2department.($counterLoc1department+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews5->getStyle($columnLetter2department.($counterLoc1department+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));


			//populate rows below
			$ctrLocdepartment = 3;
			for ($r = 1; $r <= $departmentLength; $r++) {
			    //passed
			    $ews5->setCellValue($columnLetterdepartment.$ctrLocdepartment, '=COUNTIFS(Details!$H$3:$H$50000, LocLevel2!'.$columnLetterdepartment.'1, Details!$P$3:$P$50000, "Passed", Details!$E$3:$E$50000, LocLevel2!A'.$ctrLocdepartment.')');

			    //total
			    $ews5->setCellValue($columnLetter1department.$ctrLocdepartment, '=COUNTIFS(Details!$H$3:$H$50000, LocLevel2!'.$columnLetterdepartment.'1, Details!$E$3:$E$50000, LocLevel2!A'.$ctrLocdepartment.')');

			    //compliance
				$ews5->setCellValue($columnLetter2department.$ctrLocdepartment, '=IF(OR('.$columnLetterdepartment.$ctrLocdepartment.'=0,'.$columnLetter1department.$ctrLocdepartment.'=0),"",'.$columnLetterdepartment.$ctrLocdepartment.'/'.$columnLetter1department.$ctrLocdepartment.')');
				$ews5->getStyle($columnLetter2department.$ctrLocdepartment)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

			    $ctrLocdepartment++;
			} 



			$columnIdxdepartment = $columnIdxdepartment + 3;
			$columnLetterdepartment  = PHPExcel_Cell::stringFromColumnIndex($columnIdxdepartment); 
			$columnLetter1department  = PHPExcel_Cell::stringFromColumnIndex($columnIdxdepartment+1); 
			$columnLetter2department  = PHPExcel_Cell::stringFromColumnIndex($columnIdxdepartment+2); 

		}



        $ews6 = $this->excel->createSheet(5);
		$ews6->setTitle('LocLevel3');
		
		$ews6->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews6->getStyle('A1:A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews6->getStyle('A1:A2')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));


		//set wards first
		sort($arr_Ward);
		$wardCtr = 3;
		$wardLength = 0;
		foreach($arr_Ward as $wardUniqueLoc) {
		    $ews6->setCellValue('A'.$wardCtr, $wardUniqueLoc);
		    $wardCtr++;
		    $wardLength++;
		}


		$counterLoc1 = 1;

		$columnIdx = 1;
		$columnLetter  = PHPExcel_Cell::stringFromColumnIndex($columnIdx); 
		$columnLetter1  = PHPExcel_Cell::stringFromColumnIndex($columnIdx+1); 
		$columnLetter2  = PHPExcel_Cell::stringFromColumnIndex($columnIdx+2); 
		foreach ($arr_HcwType as $hcwUniqueLoc) {
			//B1
		    $ews6->setCellValue($columnLetter.$counterLoc1, $hcwUniqueLoc);
		    $ews6->setCellValue($columnLetter.($counterLoc1+1), 'Passed');

		    $ews6->setCellValue($columnLetter1.($counterLoc1+1), 'Total');

		    $ews6->setCellValue($columnLetter2.($counterLoc1+1), '%');

			//D1
			$ews6->mergeCells($columnLetter.$counterLoc1.':'.$columnLetter2.$counterLoc1);
			$ews6->getStyle($columnLetter.$counterLoc1.':'.$columnLetter2.$counterLoc1)->applyFromArray($centerStyle);

			//COLORS
			$ews6->getStyle($columnLetter.$counterLoc1)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews6->getStyle($columnLetter.$counterLoc1)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews6->getStyle($columnLetter.$counterLoc1)->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews6->getStyle($columnLetter.($counterLoc1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews6->getStyle($columnLetter.($counterLoc1+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews6->getStyle($columnLetter.($counterLoc1+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews6->getStyle($columnLetter1.($counterLoc1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews6->getStyle($columnLetter1.($counterLoc1+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews6->getStyle($columnLetter1.($counterLoc1+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews6->getStyle($columnLetter2.($counterLoc1+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews6->getStyle($columnLetter2.($counterLoc1+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews6->getStyle($columnLetter2.($counterLoc1+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));
			

			//populate rows below
			$ctrLoc = 3;
			for ($r = 1; $r <= $wardLength; $r++) {
			    //passed
			    $ews6->setCellValue($columnLetter.$ctrLoc, '=COUNTIFS(Details!$H$3:$H$50000, LocLevel3!'.$columnLetter.'1, Details!$P$3:$P$50000, "Passed", Details!$F$3:$F$50000, LocLevel3!A'.$ctrLoc.')');

			    //total
			    $ews6->setCellValue($columnLetter1.$ctrLoc, '=COUNTIFS(Details!$H$3:$H$50000, LocLevel3!'.$columnLetter.'1, Details!$F$3:$F$50000, LocLevel3!A'.$ctrLoc.')');

			    //compliance
				$ews6->setCellValue($columnLetter2.$ctrLoc, '=IF(OR('.$columnLetter.$ctrLoc.'=0,'.$columnLetter1.$ctrLoc.'=0),"",'.$columnLetter.$ctrLoc.'/'.$columnLetter1.$ctrLoc.')');
				$ews6->getStyle($columnLetter2.$ctrLoc)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

			    $ctrLoc++;
			} 



			$columnIdx = $columnIdx + 3;
			$columnLetter  = PHPExcel_Cell::stringFromColumnIndex($columnIdx); 
			$columnLetter1  = PHPExcel_Cell::stringFromColumnIndex($columnIdx+1); 
			$columnLetter2  = PHPExcel_Cell::stringFromColumnIndex($columnIdx+2); 

		}


		$ews7 = $this->excel->createSheet(6);
		$ews7->setTitle('LocLevel4');
		
		$ews7->getStyle('A1:A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews7->getStyle('A1:A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews7->getStyle('A1:A2')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));


		//set service first
		sort($arr_Service);
		$serviceCtr = 3;
		$serviceLength = 0;
		foreach($arr_Service as $serviceUniqueLoc) {
		    $ews7->setCellValue('A'.$serviceCtr, $serviceUniqueLoc);
		    $serviceCtr++;
		    $serviceLength++;
		}


		$counterLoc1service = 1;

		$columnIdxservice = 1;
		$columnLetterservice  = PHPExcel_Cell::stringFromColumnIndex($columnIdxservice); 
		$columnLetter1service  = PHPExcel_Cell::stringFromColumnIndex($columnIdxservice+1); 
		$columnLetter2service  = PHPExcel_Cell::stringFromColumnIndex($columnIdxservice+2); 
		foreach ($arr_HcwType as $hcwUniqueLocservice) {
			//B1
		    $ews7->setCellValue($columnLetterservice.$counterLoc1service, $hcwUniqueLocservice);
		    $ews7->setCellValue($columnLetterservice.($counterLoc1service+1), 'Passed');

		    $ews7->setCellValue($columnLetter1service.($counterLoc1service+1), 'Total');

		    $ews7->setCellValue($columnLetter2service.($counterLoc1service+1), '%');

			//D1
			$ews7->mergeCells($columnLetterservice.$counterLoc1service.':'.$columnLetter2service.$counterLoc1service);
			$ews7->getStyle($columnLetterservice.$counterLoc1service.':'.$columnLetter2service.$counterLoc1service)->applyFromArray($centerStyle);

			//COLORS
			$ews7->getStyle($columnLetterservice.$counterLoc1service)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews7->getStyle($columnLetterservice.$counterLoc1service)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews7->getStyle($columnLetterservice.$counterLoc1service)->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews7->getStyle($columnLetterservice.($counterLoc1service+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews7->getStyle($columnLetterservice.($counterLoc1service+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews7->getStyle($columnLetterservice.($counterLoc1service+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews7->getStyle($columnLetter1service.($counterLoc1service+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews7->getStyle($columnLetter1service.($counterLoc1service+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews7->getStyle($columnLetter1service.($counterLoc1service+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			$ews7->getStyle($columnLetter2service.($counterLoc1service+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$ews7->getStyle($columnLetter2service.($counterLoc1service+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
			$ews7->getStyle($columnLetter2service.($counterLoc1service+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

			//populate rows below
			$ctrLocservice = 3;
			for ($r = 1; $r <= $serviceLength; $r++) {
			    //passed
			    $ews7->setCellValue($columnLetterservice.$ctrLocservice, '=COUNTIFS(Details!$H$3:$H$50000, LocLevel4!'.$columnLetterservice.'1, Details!$P$3:$P$50000, "Passed", Details!$G$3:$G$50000, LocLevel4!A'.$ctrLocservice.')');

			    //total
			    $ews7->setCellValue($columnLetter1service.$ctrLocservice, '=COUNTIFS(Details!$H$3:$H$50000, LocLevel4!'.$columnLetterservice.'1, Details!$G$3:$G$50000, LocLevel4!A'.$ctrLocservice.')');

			    //compliance
				$ews7->setCellValue($columnLetter2service.$ctrLocservice, '=IF(OR('.$columnLetterservice.$ctrLocservice.'=0,'.$columnLetter1service.$ctrLocservice.'=0),"",'.$columnLetterservice.$ctrLocservice.'/'.$columnLetter1service.$ctrLocservice.')');
				$ews7->getStyle($columnLetter2service.$ctrLocservice)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);

			    $ctrLocservice++;
			} 



			$columnIdxservice = $columnIdxservice + 3;
			$columnLetterservice  = PHPExcel_Cell::stringFromColumnIndex($columnIdxservice); 
			$columnLetter1service  = PHPExcel_Cell::stringFromColumnIndex($columnIdxservice+1); 
			$columnLetter2service  = PHPExcel_Cell::stringFromColumnIndex($columnIdxservice+2); 

		}

		
















		$ews2 = $this->excel->createSheet(0);
		$ews2->setTitle('Summary');
		// $ews2->getRowDimension(1)->setRowHeight(35);
		// $ews2->getStyle('A1:B1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		// $ews2->getStyle("A1:B1")->getFont()->setBold(true);
		// $ews2->setCellValue('A1', 'Period Covered:');
		// $ews2->setCellValue('B1', $date_from.' to '.$date_to);
		$ews2->getStyle("A2")->getFont()->setBold(true);
		$ews2->setCellValue('A2', 'Filters Used:');
		$ews2->setCellValue('A3', 'Date From');
		$ews2->setCellValue('A4', 'Date To');
		// $ews2->setCellValue('A5', 'Auditor');
		// $ews2->setCellValue('A6', 'HCW');
		// $ews2->setCellValue('A7', 'Facility');
		// $ews2->setCellValue('A8', 'Department');
		// $ews2->setCellValue('A9', 'Ward');
		// $ews2->setCellValue('A10', 'Service');


		$ews2->setCellValue('B3', date('m-d-Y', strtotime($this->input->get('dateFrom'))));
		$ews2->setCellValue('B4', date('m-d-Y', strtotime($this->input->get('dateTo'))));
		// $ews2->setCellValue('B5', $this->input->get('auditor'));
		// $ews2->setCellValue('B6', $this->input->get('healthcare'));
		// $ews2->setCellValue('B7', $this->input->get('department'));
		// $ews2->setCellValue('B8', $this->input->get('subDepartment'));
		// $ews2->setCellValue('B9', $this->input->get('ward'));
		// $ews2->setCellValue('B10', $this->input->get('patient'));


		$ews2->getColumnDimension('A')->setWidth(11);
		$ews2->getColumnDimension('B')->setWidth(11);
		$ews2->getColumnDimension('C')->setWidth(11);
		$ews2->getColumnDimension('D')->setWidth(11);
		$ews2->getColumnDimension('E')->setWidth(11);
		$ews2->getColumnDimension('F')->setWidth(11);
		$ews2->getColumnDimension('G')->setWidth(11);
		$ews2->getColumnDimension('H')->setWidth(11);
		$ews2->getColumnDimension('I')->setWidth(11);
		$ews2->getColumnDimension('J')->setWidth(11);
		$ews2->getColumnDimension('K')->setWidth(11);
		$ews2->getColumnDimension('L')->setWidth(11);
		$ews2->getColumnDimension('M')->setWidth(11);
		$ews2->getColumnDimension('N')->setWidth(11);
		$ews2->getColumnDimension('O')->setWidth(11);
		$ews2->getColumnDimension('P')->setWidth(11);
		$ews2->getColumnDimension('Q')->setWidth(11);
		$ews2->getColumnDimension('R')->setWidth(11);
		$ews2->getColumnDimension('S')->setWidth(11);
		$ews2->getColumnDimension('T')->setWidth(11);
		$ews2->getColumnDimension('U')->setWidth(11);
		$ews2->getColumnDimension('V')->setWidth(11);
		$ews2->getColumnDimension('W')->setWidth(11);
		$ews2->getColumnDimension('X')->setWidth(11);
		$ews2->getColumnDimension('Y')->setWidth(11);
		$ews2->getColumnDimension('Z')->setWidth(11);
		$ews2->getColumnDimension('AA')->setWidth(11);
		$ews2->getColumnDimension('AB')->setWidth(11);
		$ews2->getColumnDimension('AC')->setWidth(11);
		$ews2->getColumnDimension('AD')->setWidth(11);
		$ews2->getColumnDimension('AE')->setWidth(11);
		$ews2->getColumnDimension('AF')->setWidth(11);
		$ews2->getColumnDimension('AG')->setWidth(11);
		$ews2->getColumnDimension('AH')->setWidth(11);
		$ews2->getColumnDimension('AI')->setWidth(11);
		$ews2->getColumnDimension('AJ')->setWidth(11);
		$ews2->getColumnDimension('AK')->setWidth(11);
		$ews2->getColumnDimension('AL')->setWidth(11);
		$ews2->getColumnDimension('AM')->setWidth(11);
		$ews2->getColumnDimension('AN')->setWidth(11);
		$ews2->getColumnDimension('AO')->setWidth(11);
		$ews2->getColumnDimension('AP')->setWidth(11);
		$ews2->getColumnDimension('AQ')->setWidth(11);
		$ews2->getColumnDimension('AR')->setWidth(11);

		//LOCATION ONE HERE
		$ews2->setCellValue('D22', 'Loc Level 1');
		$ews2->setCellValue('E22', 'Passed');
		$ews2->setCellValue('F22', 'Total');
		$ews2->setCellValue('G22', '%');
		$ews2->getStyle('D22:G22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews2->getStyle('D22:G22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews2->getStyle('D22:G22')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));
		
		$ews2->setCellValue('I22', 'Loc Level 2');
		$ews2->setCellValue('J22', 'Passed');
		$ews2->setCellValue('K22', 'Total');
		$ews2->setCellValue('L22', '%');
		$ews2->getStyle('I22:L22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews2->getStyle('I22:L22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews2->getStyle('I22:L22')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

		
		$ews2->setCellValue('N22', 'Loc Level 3');
		$ews2->setCellValue('O22', 'Passed');
		$ews2->setCellValue('P22', 'Total');
		$ews2->setCellValue('Q22', '%');
		$ews2->getStyle('N22:Q22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews2->getStyle('N22:Q22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews2->getStyle('N22:Q22')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));		
		
		//LOCATION FOUR HERE
		$ews2->setCellValue('S22', 'Loc Level 4');
		$ews2->setCellValue('T22', 'Passed');
		$ews2->setCellValue('U22', 'Total');
		$ews2->setCellValue('V22', '%');
		$ews2->getStyle('S22:V22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews2->getStyle('S22:V22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews2->getStyle('S22:V22')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

		//N-Q to X-AA
		$ews2->setCellValue('X22', 'HCW Type');
		$ews2->setCellValue('Y22', 'Passed');
		$ews2->setCellValue('Z22', 'Total');
		$ews2->setCellValue('AA22', '%');
		$ews2->getStyle('X22:AA22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews2->getStyle('X22:AA22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews2->getStyle('X22:AA22')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

		//S-V to AC-AF
		$ews2->setCellValue('AC22', 'Moment');
		$ews2->setCellValue('AC23', '1');
		$ews2->setCellValue('AC24', '2');
		$ews2->setCellValue('AC25', '3');
		$ews2->setCellValue('AC26', '4');
		$ews2->setCellValue('AC27', '5');
	    $ews2->setCellValue('AD22', 'Count');
	    $ews2->setCellValue('AD23', '=IF(COUNTIF(Details!$J$3:$J$5000,1)=0, "N/A", COUNTIF(Details!$J$3:$J$5000,1))');
		$ews2->setCellValue('AD24', '=IF(COUNTIF(Details!$K$3:$K$5000,2)=0, "N/A", COUNTIF(Details!$K$3:$K$5000,2))');
		$ews2->setCellValue('AD25', '=IF(COUNTIF(Details!$L$3:$L$5000,3)=0, "N/A", COUNTIF(Details!$L$3:$L$5000,3))');
		$ews2->setCellValue('AD26', '=IF(COUNTIF(Details!$M$3:$M$5000,4)=0, "N/A", COUNTIF(Details!$M$3:$M$5000,4))');
		$ews2->setCellValue('AD27', '=IF(COUNTIF(Details!$N$3:$N$5000,5)=0, "N/A", COUNTIF(Details!$N$3:$N$5000,5))');
		$ews2->getStyle('AC22:AD22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews2->getStyle('AC22:AD22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews2->getStyle('AC22:AD22')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

		//X-AB to AH-AL
		$ews2->setCellValue('AH22', 'Moment');
		$ews2->setCellValue('AI22', 'Total');
		$ews2->setCellValue('AJ22', 'Passed');
		$ews2->setCellValue('AK22', 'Failed');		
		$ews2->setCellValue('AL22', '%');	
		$ews2->getStyle('AH22:AL22')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$ews2->getStyle('AH22:AL22')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('2E8B57');
		$ews2->getStyle('AH22:AL22')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));	

		//FOR HCW
		//N-Q to X-AA
		$ctr = 23;
		foreach ($arr_HcwType as $value) {
		    $ews2->setCellValue('x'.$ctr, $value);
			$ews2->setCellValue('y'.$ctr, '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$H$3:$H$5000,"'.$value.'")');
			$ews2->setCellValue('z'.$ctr, '=COUNTIF(Details!$H$3:$H$5000, "'.$value.'")');
			$ews2->setCellValue('aa'.$ctr, '=y'.$ctr.'/z'.$ctr);
			$ews2->getStyle('AA'.$ctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
			$ctr++;
		}

		$dsl = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$AA$22', null, 1),
		);
		$xal = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$X$23:$X$'.($ctr-1), null, ($ctr-23)),	//	Q1 to Q4
		);
		$dsv = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Summary!$AA$23:$AA$'.($ctr-1), null, ($ctr-23)),
		);

		$ds = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                    range(0, count($dsv)-1),
                    $dsl,
                    $xal,
                    $dsv
                    );
		$ds->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

		$pa = new PHPExcel_Chart_PlotArea(NULL, array($ds));
		$legend = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
		$title = new PHPExcel_Chart_Title('Healthcare Worker Compliance');
		$chart = new PHPExcel_Chart(
                    'Healthcare Worker Compliance',
                    $title,
                    $legend,
                    $pa,
                    true,
                    0,
                    NULL, 
                    NULL
                    );

		$chart->setTopLeftPosition('X2');
		$chart->setBottomRightPosition('AB20');

		$ews2->addChart($chart);




		//S-V to AC-AF
		$dslmoment = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$AD22', null, 1),
		);
		$xalmoment = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$AC23:$AC27', null, 1),
		);
		$dsvmoment = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Summary!$AD23:$AD27', null, 1),
		);

		$dsmoment = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                    range(0, count($dsvmoment)-1),
                    $dslmoment,
                    $xalmoment,
                    $dsvmoment
                    );
		$dsmoment->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

		$pamoment = new PHPExcel_Chart_PlotArea(NULL, array($dsmoment));
		$legendmoment = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
		$titlemoment = new PHPExcel_Chart_Title('Count per Moment');
		$chartmoment = new PHPExcel_Chart(
                    'Count per Moment',
                    $titlemoment,
                    $legendmoment,
                    $pamoment,
                    true,
                    0,
                    NULL, 
                    NULL
                    );

		$chartmoment->setTopLeftPosition('AC2');
		$chartmoment->setBottomRightPosition('AG20');

		$ews2->addChart($chartmoment);






		//FOR FACILITY
		//D-G
		$fctr = 23;
		foreach ($arr_Facility as $fvalue) {
		    $ews2->setCellValue('D'.$fctr, $fvalue);
			$ews2->setCellValue('E'.$fctr, '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$D$3:$D$5000,"'.$fvalue.'")');
			$ews2->setCellValue('F'.$fctr, '=COUNTIF(Details!$D$3:$D$5000, "'.$fvalue.'")');
			$ews2->setCellValue('g'.$fctr, '=e'.$fctr.'/f'.$fctr);
			$ews2->getStyle('G'.$fctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
			$fctr++;
		}

		$dslfacility = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$G$22', null, 1),
		);
		$xalfacility = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$D$23:$D$'.($fctr-1), null, ($fctr-23)),
		);
		$dsvfacility = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Summary!$G$23:$G$'.($fctr-1), null, ($fctr-23)),
		);

		$dsfacility = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                    range(0, count($dsvfacility)-1),
                    $dslfacility,
                    $xalfacility,
                    $dsvfacility
                    );
		$dsfacility->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

		$pafacility = new PHPExcel_Chart_PlotArea(NULL, array($dsfacility));
		$legendfacility = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
		$titlefacility = new PHPExcel_Chart_Title('Location Level 1');
		$chartfacility = new PHPExcel_Chart(
                    'Location Level 1',
                    $titlefacility,
                    $legendfacility,
                    $pafacility,
                    true,
                    0,
                    NULL, 
                    NULL
                    );

		$chartfacility->setTopLeftPosition('D2');
		$chartfacility->setBottomRightPosition('H20');

		$ews2->addChart($chartfacility);



		//FOR DEPARTMENT
		//I-L to N-Q
		$dctr = 23;
		foreach ($arr_Department as $dvalue) {
		    $ews2->setCellValue('I'.$dctr, $dvalue);
			$ews2->setCellValue('J'.$dctr, '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$E$3:$E$5000,"'.$dvalue.'")');
			$ews2->setCellValue('K'.$dctr, '=COUNTIF(Details!$E$3:$E$5000, "'.$dvalue.'")');
			$ews2->setCellValue('l'.$dctr, '=j'.$dctr.'/k'.$dctr);
			$ews2->getStyle('L'.$dctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
			$dctr++;
		}

		$dsldepartment = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$L$22', null, 1),
		);
		$xaldepartment = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$I$23:$I$'.($dctr-1), null, ($dctr-23)),
		);
		$dsvdepartment = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Summary!$L$23:$L$'.($dctr-1), null, ($dctr-23)),
		);

		$dsdepartment = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                    range(0, count($dsvdepartment)-1),
                    $dsldepartment,
                    $xaldepartment,
                    $dsvdepartment
                    );
		$dsdepartment->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

		$padepartment = new PHPExcel_Chart_PlotArea(NULL, array($dsdepartment));
		$legenddepartment = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
		$titledepartment = new PHPExcel_Chart_Title('Location Level 2');
		$chartdepartment = new PHPExcel_Chart(
                    'Location Level 2',
                    $titledepartment,
                    $legenddepartment,
                    $padepartment,
                    true,
                    0,
                    NULL, 
                    NULL
                    );

		$chartdepartment->setTopLeftPosition('I2');
		$chartdepartment->setBottomRightPosition('M20');

		$ews2->addChart($chartdepartment);




		//FOR WARD
		//N-Q to S-V
		$wctr = 23;
		foreach ($arr_Ward as $wvalue) {
		    $ews2->setCellValue('N'.$wctr, $wvalue);
			$ews2->setCellValue('O'.$wctr, '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$F$3:$F$5000,"'.$wvalue.'")');
			$ews2->setCellValue('P'.$wctr, '=COUNTIF(Details!$F$3:$F$5000, "'.$wvalue.'")');
			$ews2->setCellValue('q'.$wctr, '=o'.$wctr.'/p'.$wctr);
			$ews2->getStyle('Q'.$wctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
			$wctr++;
		}

		$dslward = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$Q$22', null, 1),
		);
		$xalward = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$N$23:$N$'.($wctr-1), null, ($wctr-23)),	//	Q1 to Q4
		);
		$dsvward = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Summary!$Q$23:$Q$'.($wctr-1), null, ($wctr-23)),
		);

		$dsward = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                    range(0, count($dsvward)-1),
                    $dslward,
                    $xalward,
                    $dsvward
                    );
		$dsward->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

		$paward = new PHPExcel_Chart_PlotArea(NULL, array($dsward));
		$legendward = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
		$titleward = new PHPExcel_Chart_Title('Location Level 3');
		$chartward = new PHPExcel_Chart(
                    'Location Level 3',
                    $titleward,
                    $legendward,
                    $paward,
                    true,
                    0,
                    NULL, 
                    NULL
                    );

		$chartward->setTopLeftPosition('N2');
		$chartward->setBottomRightPosition('R20');

		$ews2->addChart($chartward);



		//FOR SERVICE
		//D-G
		$sctr = 23;
		foreach ($arr_Service as $svalue) {
		    $ews2->setCellValue('S'.$sctr, $svalue);
			$ews2->setCellValue('T'.$sctr, '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$G$3:$G$5000,"'.$svalue.'")');
			$ews2->setCellValue('U'.$sctr, '=COUNTIF(Details!$G$3:$G$5000, "'.$svalue.'")');
			$ews2->setCellValue('v'.$sctr, '=t'.$sctr.'/u'.$sctr);
			$ews2->getStyle('V'.$sctr)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
			$sctr++;
		}

		$dslservice = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$V$22', null, 1),
		);
		$xalservice = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$S$23:$S$'.($sctr-1), null, ($sctr-23)),
		);
		$dsvservice = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Summary!$V$23:$V$'.($sctr-1), null, ($sctr-23)),
		);

		$dsservice = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                    range(0, count($dsvservice)-1),
                    $dslservice,
                    $xalservice,
                    $dsvservice
                    );
		$dsservice->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

		$paservice = new PHPExcel_Chart_PlotArea(NULL, array($dsservice));
		$legendservice = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
		$titleservice = new PHPExcel_Chart_Title('Location Level 4');
		$chartservice = new PHPExcel_Chart(
                    'Location Level 4',
                    $titleservice,
                    $legendservice,
                    $paservice,
                    true,
                    0,
                    NULL, 
                    NULL
                    );

		$chartservice->setTopLeftPosition('S2');
		$chartservice->setBottomRightPosition('W20');

		$ews2->addChart($chartservice);




		//COMPLIANCE BY INDICATION
		//X-AB to AH-AL
		$ictr = 23;
		foreach ($arr_Indication as $ivalue) {
		    $ews2->setCellValue('AH23', 1);
		    $ews2->setCellValue('AH24', 2);
		    $ews2->setCellValue('AH25', 3);
		    $ews2->setCellValue('AH26', 4);
		    $ews2->setCellValue('AH27', 5);
		    if($ivalue == 1) {
				$ews2->setCellValue('AJ23', '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$J$3:$J$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AK23', '=COUNTIFS(Details!$P$3:$P$5000,"Failed",Details!$J$3:$J$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AI23', '=COUNTIF(Details!$J$3:$J$5000, "'.$ivalue.'")');
				$ews2->setCellValue('al23', '=aj23/ai23');
				$ews2->getStyle('AL23')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
		    }
		    if($ivalue == 2) {
				$ews2->setCellValue('AJ24', '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$K$3:$K$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AK24', '=COUNTIFS(Details!$P$3:$P$5000,"Failed",Details!$K$3:$K$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AI24', '=COUNTIF(Details!$K$3:$K$5000, "'.$ivalue.'")');
			$ews2->setCellValue('al24', '=aj24/ai24');
			$ews2->getStyle('AL24')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
		    }
		    if($ivalue == 3) {
				$ews2->setCellValue('AJ25', '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$L$3:$L$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AK25', '=COUNTIFS(Details!$P$3:$P$5000,"Failed",Details!$L$3:$L$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AI25', '=COUNTIF(Details!$L$3:$L$5000, "'.$ivalue.'")');
			$ews2->setCellValue('al25', '=aj25/ai25');
			$ews2->getStyle('AL25')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
		    }
		    if($ivalue == 4) {
				$ews2->setCellValue('AJ26', '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$M$3:$M$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AK26', '=COUNTIFS(Details!$P$3:$P$5000,"Failed",Details!$M$3:$M$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AI26', '=COUNTIF(Details!$M$3:$M$5000, "'.$ivalue.'")');
			$ews2->setCellValue('al26', '=aj26/ai26');
			$ews2->getStyle('AL26')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
		    }
		    if($ivalue == 5) {
				$ews2->setCellValue('AJ27', '=COUNTIFS(Details!$P$3:$P$5000,"Passed",Details!$N$3:$N$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AK27', '=COUNTIFS(Details!$P$3:$P$5000,"Failed",Details!$N$3:$N$5000,"'.$ivalue.'")');
				$ews2->setCellValue('AI27', '=COUNTIF(Details!$N$3:$N$5000, "'.$ivalue.'")');
			$ews2->setCellValue('al27', '=aj27/ai27');
			$ews2->getStyle('AL27')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
		    }
		}
		$ictr == 28;
		$ews2->setCellValue('AH28', 'Total');
		$ews2->setCellValue('AL28', '=AVERAGE(AL23:AL'.($ictr-1).')');
		$ews2->getStyle('AL28')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
		$ictr++;

		$dslindication = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$AL$22', null, 1),
		);
		$xalindication = array(
			new PHPExcel_Chart_DataSeriesValues('String', 'Summary!$AH$23:$AH$27', null, 5),
		);
		$dsvindication = array(
			new PHPExcel_Chart_DataSeriesValues('Number', 'Summary!$AL$23:$AL$27', null, 5),
		);

		$dsindication = new PHPExcel_Chart_DataSeries(
                    PHPExcel_Chart_DataSeries::TYPE_BARCHART,
                    PHPExcel_Chart_DataSeries::GROUPING_STANDARD,
                    range(0, count($dsvindication)-1),
                    $dslindication,
                    $xalindication,
                    $dsvindication
                    );
		$dsindication->setPlotDirection(PHPExcel_Chart_DataSeries::DIRECTION_COL);

		$paindication = new PHPExcel_Chart_PlotArea(NULL, array($dsindication));
		$legendindication = new PHPExcel_Chart_Legend(PHPExcel_Chart_Legend::POSITION_RIGHT, NULL, false);
		$titleindication = new PHPExcel_Chart_Title('Compliance by Moment');
		$chartindication = new PHPExcel_Chart(
                    'Compliance by Moment',
                    $titleindication,
                    $legendindication,
                    $paindication,
                    true,
                    0,
                    NULL, 
                    NULL
                    );

		$chartindication->setTopLeftPosition('AH2');
		$chartindication->setBottomRightPosition('AM20');

		$ews2->addChart($chartindication);


		// END Ronald code here

		// Do your stuff here
		$writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		//$writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2013');
		$writer->setIncludeCharts(true); //Ronald code
                $file 	= FCPATH.'assets/uploads/HHAT_COMPLIANCE_DATA_'.rand(1000,9999).'_'.$date_from.' to '.$date_to.'.xls';
		//$file 	= FCPATH.'assets/uploads/HHAT_COMPLIANCE_DATA_'.rand(1000,9999).'_'.$date_from.' to '.$date_to.'.xlsx';
		$writer->save($file);
		return $file;
	}
		
	
	public function api_locations($cid, $uid)
	{
		$this->db->where(array('cid' => $cid, 'deleted' => 0));
		$query 	= $this->db->get('locations');
		$result = array();
		
		foreach($query->result() as $loc)
		{
			$sid = ($loc->sort == 0) ? $loc->id : $loc->sort;
			$result[$loc->category][$sid] = array('name' => $loc->name, 'id' => $loc->id);
		}
		
		if($this->input->get('type') == 'mobile')
		{
			$this->db->where('id', $uid);
			$this->db->update('users', array('data_update' => '0'));
		}
		return array( 'status' => 1, 'errortype' => 'success', 'message' => '', 'result' => $result);
	}
	
	
	
	public function api_statistics($uid)
	{
		$this->load->model('Mdl_users');
		
		$user = $this->ion_auth->user($uid)->row();	
		$date_from 	= strtotime($this->input->get('dateFrom'));
		$date_to 	= strtotime($this->input->get('dateTo'));
		
		if($this->input->get('department') > 0) 	$this->db->where('location_level1', $this->input->get('department'));
		if($this->input->get('subDepartment') > 0) 	$this->db->where('location_level2', $this->input->get('subDepartment'));
		if($this->input->get('ward') > 0) 			$this->db->where('location_level3', $this->input->get('ward'));
		if($this->input->get('patient') > 0) 		$this->db->where('location_level4', $this->input->get('patient'));
		if($this->input->get('healthcare') > 0) 	$this->db->where('hcw_title', $this->input->get('healthcare'));
		
		if($this->input->get('dateFrom') != '')
			$this->db->where('observations.date_registered >=',date('Y-m-d 00:00:00',$date_from));
			
		if($this->input->get('dateTo') != '')
			$this->db->where('observations.date_registered <=',date('Y-m-d 23:59:59',$date_to));
		

		if($this->input->get('auditor') != '') {
			$this->db->where('uid', $this->input->get('auditor'));
		}
		else {
			$this->db->where('observations.cid', $user->cid);
		}

		$this->db->from('observations');
		$stats['accountRecords'] 	= $this->db->count_all_results();

		$stats['momentRecords'] 	= $this->api_countSummaryObservation($uid);
		// if($this->input->get('auditor') != '')	{
		// 	$stats['momentRecords'] 	= $this->api_countobservation($user->cid, $this->input->get('auditor'));
		// }
		// else {
		// 	$stats['momentRecords'] 	= $this->api_countobservation($user->cid, $uid);
		// }
		
		$stats['date']['from'] 		= date('M j, Y', $date_from);
		$stats['date']['to'] 		= date('M j, Y', $date_to);
		
		return array( 'status' => 1, 'errortype' => 'success', 'message' => $uid, 'result' => $stats);
	}

	public function api_countSummaryObservation($uid)
	{
		$user 		= $this->ion_auth->user($uid)->row();
		$date_from 	= strtotime($this->input->get('dateFrom'));
		$date_to 	= strtotime($this->input->get('dateTo'));
		
		if($this->input->get('department') > 0) 	$this->db->where('location_level1', $this->input->get('department'));
		if($this->input->get('subDepartment') > 0) 	$this->db->where('location_level2', $this->input->get('subDepartment'));
		if($this->input->get('ward') > 0) 			$this->db->where('location_level3', $this->input->get('ward'));
		if($this->input->get('patient') > 0) 		$this->db->where('location_level4', $this->input->get('patient'));
		if($this->input->get('healthcare') > 0) 	$this->db->where('hcw_title', $this->input->get('healthcare'));
		
		if($this->input->get('dateFrom') != '')
			$this->db->where('observations.date_registered >=',date('Y-m-d 00:00:00',$date_from));
			
		if($this->input->get('dateTo') != '')
			$this->db->where('observations.date_registered <=',date('Y-m-d 23:59:59',$date_to));
		
		if($this->input->get('auditor') != '') {
			$this->db->where('uid', $this->input->get('auditor'));
		}
		else {
			$this->db->where('observations.cid', $user->cid);
		}
		$this->db->select('moment1, moment2, moment3, moment4, moment5, hh_compliance');
		$query = $this->db->get('observations');
		
		$passedFailed 	= array('passed' => 0, 'failed' => 0);
		$momentRecords 	= array( 1 => $passedFailed, 2 => $passedFailed, 3 => $passedFailed, 4 => $passedFailed, 5 => $passedFailed);
		
		foreach($query->result() as $data)
		{
			for($x = 1; $x <=5; $x++){
				$moment = 'moment'.$x; 
				if($data->$moment == $x)
				{
					if($data->hh_compliance == 'missed')
						$momentRecords[$x]['failed']++;
					else
						$momentRecords[$x]['passed']++;
				}
			}
		}
		return $momentRecords;
	}
	
	public function search_locations($category, $keyword, $cid)
	{
		$this->db->where('id', $category);
		$this->db->where('cid', $cid);
		$this->db->like('name', $keyword);
		$this->db->select('id');
		$query 		= $this->db->get('locations');
		$response 	= false;
		
		if($query->num_rows())
		{
			$response = array();
			foreach($query->result() as $row)
			{
				$response[] = $row->id;
			}
		}
		
		return $response;
	}
}








