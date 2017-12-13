<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Chart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) redirect(base_url('auth'));
    }

    /**
     *
     */
    public function institutions()
    {
        $institutions = array();
        if ($this->ion_auth->is_admin()) {
            $this->load->model('Mdl_company');
            $institutions = $this->Mdl_company->getInstitutions();
        }

        $this->returnJsonResponse($institutions);
    }

    /**
     *
     */
    public function users()
    {
        $companyId = $this->getCompanyIdOfCurrentUser();
        $this->load->model('Mdl_users');
        $users = $this->Mdl_users->getUsersLookup($companyId);
        $this->returnJsonResponse($users);
    }

    /**
     *
     */
    public function hcw()
    {
        $companyId = $this->getCompanyIdOfCurrentUser();
        $this->load->model('Mdl_locations');
        $hcw =  $this->Mdl_locations->getHealthCareWorkers($companyId);
        $this->returnJsonResponse($hcw);
    }

    /**
     *
     */
    public function locations()
    {
        $companyId = $this->getCompanyIdOfCurrentUser();
        $this->load->model('Mdl_locations');
        $locations = $this->Mdl_locations->getLocations($companyId);
        $this->returnJsonResponse($locations);
    }

    /**
     *
     */
    public function getData()
    {
        $filterOptions = $this->input->get();
        $companyId = $this->getCompanyIdOfCurrentUser();
        if ($companyId) {
            // override company value to limit to current user's company value
            $filterOptions['company'] = $companyId;
        }

        $this->load->model('Mdl_observation');
        $response['rawData'] = $this->Mdl_observation->fetchWebChartData($filterOptions);

        $this->load->model('Mdl_Charts');

        foreach ($filterOptions['complianceOptions'] as $complianceOption) {
            $response['chart'][$complianceOption] = $this->Mdl_Charts->generateChartDataSets($complianceOption, $response['rawData']);
        }

        $this->returnJsonResponse($response);
    }

    /**
     *
     * @return void
     */
    public function generateExcelRawDataReport()
    {
        $filterOptions = $this->input->get();
        $companyId = $this->getCompanyIdOfCurrentUser();
        if ($companyId) {
            // override company value to limit to current user's company value
            $filterOptions['company'] = $companyId;
        }

        $this->load->model('Mdl_observation');
        $observations = $this->Mdl_observation->fetchWebChartData($filterOptions);
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

        $contents['D2'] = '1';
        $contents['E2'] = '2';
        $contents['F2'] = '3';
        $contents['G2'] = '4';

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

        $this->load->library('excel');

        // We'll be outputting an excel file
        header('Content-type: application/vnd.ms-excel');

        // It will be called file.xls
        header('Content-Disposition: attachment; filename="file.xls"');
        $writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $writer->save('php://output');
    }

    /**
     * @param $data
     */
    private function returnJsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    /**
     * @return null
     */
    private function getCompanyIdOfCurrentUser()
    {
        $companyId = null;
        if (!$this->ion_auth->is_admin()) {
            $companyId = $this->ion_auth->user()->row()->cid;
        }

        return $companyId;
    }
}