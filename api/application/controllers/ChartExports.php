<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class ChartExports extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->ion_auth->logged_in()) redirect(base_url('auth'));
    }

    /**
     * Generates PDF version of charts
     */
    public function exportPdf()
    {
        $this->load->library('svggraphhelper');
        $this->load->library('pdf');
        $this->load->model('Mdl_Charts');
        $this->load->model('Mdl_observation');

        $logoLocation = FCPATH . "assets/img/logo.png";
        $compNames   = array(
            "loc1"    => 'Location 1',
            "loc2"    => 'Location 2',
            "loc3"    => 'Location 3',
            "loc4"    => 'Location 4',
            "hcw"     => 'Healthcare Compliance',
            "cpm"     => 'Count per Moment',
            "cbm"     => 'Compliance by Moment',
            "loc1hcw" => 'Location 1 per Healthcare Worker',
            "loc1m"   => 'Location 1 per Moment',
            "loc2hcw" => 'Location 2 per Healthcare Worker',
            "loc2m"   => 'Location 2 per Moment',
            "loc3hcw" => 'Location 3 per Healthcare Worker ',
            "loc3m"   => 'Location 3 per Moment',
            "loc4hcw" => 'Location 4 per Healthcare Worker',
            "loc4m"   => 'Location 4 per Moment',
        );

        $filterOptions = $this->input->get();
        $rawData = $this->Mdl_observation->fetchWebChartData($filterOptions);
        $y = 30;
        foreach ($filterOptions['complianceOptions'] as $complianceOption) {
            $chartName = isset($compNames[$complianceOption]) ? $compNames[$complianceOption] : 'Compliance Rate';
            $percentagesOutput = $this->Mdl_Charts->generateChartDataSets($complianceOption, $rawData);
            $dataSet =  array_combine($percentagesOutput['columns'], $percentagesOutput['values']);

            $this->pdf->AddPage();
            $this->pdf->Image($logoLocation, 15, 10, 65, 15, 'PNG', '', '', false, 150, '', false, false, 1, false, false, false);

            $output = $this->svggraphhelper->generateBarGraph($dataSet, $chartName);
            $this->pdf->ImageSVG('@' . $output, $x=30, $y, $w='200', $h='200', '', $align='C', $palign='C', $border=1, $fitonpage=true);

        }

        $this->pdf->Output('name.pdf', 'D');
    }
}