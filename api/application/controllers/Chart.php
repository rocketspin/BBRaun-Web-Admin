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

        $this->load->model('Mdl_charts');

        foreach ($filterOptions['complianceOptions'] as $complianceOption) {
            $response['chart'][$complianceOption] = $this->Mdl_charts->generateChartDataSets($complianceOption, $response['rawData']);
        }

        $chunked = array();
        foreach ($response['chart'] as $complianceOption => $values) {
            $chunkedColumns = array_chunk($values['columns'], 5);
            $chunkedValues = array_chunk($values['values'], 5);
            foreach ($chunkedValues as $key => $value) {
                $chunked[$complianceOption][] = array(
                    'values' => $value,
                    'columns' => $chunkedColumns[$key],
                );
            }
        }
        $response['chunkedChartData'] = $chunked;
        $this->returnJsonResponse($response);
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