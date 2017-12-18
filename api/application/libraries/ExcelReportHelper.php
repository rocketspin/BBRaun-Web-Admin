<?php

if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH."/third_party/PHPExcel.php";

class ExcelReportHelper extends PHPExcel
{
    protected $sheetIndex = 0;
    protected $moments = array(
        'moment1' => array("B", "C", "D"),
        'moment2' => array("E", "F", "G"),
        'moment3' => array("H", "I", "J"),
        'moment4' => array("K", "L", "M"),
        'moment5' => array("N", "O", "P"),
    );
    protected $locationLookup = array(
        'location_level1' => array(
            'loc_index' => 2,
            'loc_moment_index' => 3,
            'loc' => 'LocLevel1',
            'loc_moment' => 'LocLevel1-Moment'
        ),
        'location_level2' => array(
            'loc_index' => 4,
            'loc_moment_index' => 5,
            'loc' => 'LocLevel2',
            'loc_moment' => 'LocLevel2-Moment'
        ),
        'location_level3' => array(
            'loc_index' => 6,
            'loc_moment_index' => 7,
            'loc' => 'LocLevel3',
            'loc_moment' => 'LocLevel3-Moment'
        ),
        'location_level4' => array(
            'loc_index' => 8,
            'loc_moment_index' => 9,
            'loc' => 'LocLevel4',
            'loc_moment' => 'LocLevel4-Moment'
        ),
        'location_level5' => array(
            'loc_index' => 10,
            'loc_moment_index' => 11,
            'loc' => 'LocLevel5',
            'loc_moment' => 'LocLevel5-Moment'
        ),
    );

	public function __construct() {
		parent::__construct();
    }

    /**
     * @param $data
     * @throws PHPExcel_Exception
     */
    public function createExcelSheet($data)
    {
        $this->createDetailsSheet($data);
        $this->createMomentHcwSheet($data);

        $locations = array(
            'location_level1' => 'location_1_name',
            'location_level2' => 'location_2_name',
            'location_level3' => 'location_3_name',
            'location_level4' => 'location_4_name'
        );

        foreach ($locations as $locKey => $locFieldName) {
            $this->createLocSheet($locKey, $locFieldName, $data);
            $this->createLocMomentSheet($locKey, $locFieldName, $data);
        }

        $this->getProperties()->setCreator('Hand Hygiene Auditing Tool');
        $this->getProperties()->setLastModifiedBy('RocketSpin.ph');
        $this->getProperties()->setTitle('HHAT Compliance Data');
        $this->getProperties()->setSubject('HHAT Reports');
        $this->getProperties()->setDescription('System Generated Reports');
    }

    /**
     * @param $data
     * @throws PHPExcel_Exception
     */
    private function createDetailsSheet($data)
    {
        $formattedData = $this->formatRawData($data);
        $sheet = $this->createAndFormatDetailsSheet()
            ->fromArray($formattedData, '', 'A3');
    }

    /**
     * @param $data
     * @throws PHPExcel_Exception
     */
    public function createMomentHcwSheet($data)
    {
        $sheet = $this->createAndFormatMomentHcwSheet();
        $formattedData = $this->formatDataforMomentHcw($data);
        $sheet->fromArray($formattedData, "", "A3");

        $startRow = 3 + count($formattedData);
        $sheet->getStyle('B'.$startRow.':P'.$startRow)->applyFromArray(array(
            'borders' => array(
                'top' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        ));

        $sheet->setCellValue('b'.$startRow, '=SUM(b3:b'.($startRow-1).')');
        $sheet->setCellValue('c'.$startRow, '=SUM(c3:c'.($startRow-1).')');
        $sheet->setCellValue('d'.$startRow, '=IF(OR(b'.$startRow.'=0,c'.$startRow.'=0),"",b'.$startRow.'/c'.$startRow.')');
        $sheet->getStyle('D'.$startRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
        $sheet->setCellValue('E'.$startRow, '=SUM(E3:E'.($startRow-1).')');
        $sheet->setCellValue('F'.$startRow, '=SUM(F3:F'.($startRow-1).')');
        $sheet->setCellValue('g'.$startRow, '=IF(OR(e'.$startRow.'=0,f'.$startRow.'=0),"",e'.$startRow.'/f'.$startRow.')');
        $sheet->getStyle('G'.$startRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
        $sheet->setCellValue('H'.$startRow, '=SUM(H3:H'.($startRow-1).')');
        $sheet->setCellValue('I'.$startRow, '=SUM(I3:I'.($startRow-1).')');
        $sheet->setCellValue('j'.$startRow, '=IF(OR(h'.$startRow.'=0,i'.$startRow.'=0),"",h'.$startRow.'/i'.$startRow.')');
        $sheet->getStyle('J'.$startRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
        $sheet->setCellValue('K'.$startRow, '=SUM(K3:K'.($startRow-1).')');
        $sheet->setCellValue('L'.$startRow, '=SUM(L3:L'.($startRow-1).')');
        $sheet->setCellValue('m'.$startRow, '=IF(OR(k'.$startRow.'=0,l'.$startRow.'=0),"",k'.$startRow.'/l'.$startRow.')');
        $sheet->getStyle('M'.$startRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
        $sheet->setCellValue('N'.$startRow, '=SUM(N3:N'.($startRow-1).')');
        $sheet->setCellValue('O'.$startRow, '=SUM(O3:O'.($startRow-1).')');
        $sheet->setCellValue('p'.$startRow, '=IF(OR(n'.$startRow.'=0,o'.$startRow.'=0),"",n'.$startRow.'/o'.$startRow.')');
        $sheet->getStyle('P'.$startRow)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
    }

    /**
     * @param $location
     * @param $locationNameKey
     * @param $data
     */
    public function createLocSheet($location, $locationNameKey, $data)
    {
        $formattedData = $this->formatDataByLocation($location, $locationNameKey, $data);
        $sheet = $this->createAndFormatLocationSheet($location, $formattedData['unique_hcw']);

        $locations = array_keys($formattedData['dataset']);
        $startRow = 3;
        foreach ($locations as $loc) {
            $sheet->setCellValue("A{$startRow}", $loc);
            $startRow++;
        }

        $sheet->getColumnDimension("A")
            ->setAutoSize(true);

        $sheet->fromArray($formattedData['dataset'], "", "B3");
    }

    /**
     * @param $location
     * @param $locationNameKey
     * @param $data
     * @return array
     */
    public function formatDataByLocation($location, $locationNameKey, $data)
    {
        $dataSet = $uniqueHcw = $finalDataSet = array();
        foreach ($data as $datum) {
            if (empty($datum[$location]) || empty($datum[$locationNameKey])) {
                continue;
            }

            $key = $datum[$locationNameKey];
            $hcwKey = "{$datum['hcw_titlename']} (ID {$datum['hcw_title']})";

            if (!in_array($hcwKey, $uniqueHcw)) {
                $uniqueHcw[] = $hcwKey;
            }

            if (!isset($dataSet[$key][$hcwKey]['total'])) {
                $dataSet[$key][$hcwKey]['total'] = 0;
            }

            $dataSet[$key][$hcwKey]['total']++;

            if (!isset($dataSet[$key][$hcwKey]['passed'])) {
                $dataSet[$key][$hcwKey]['passed'] = 0;
            }

            if (!empty($datum['result']) && $datum['result'] == 'PASSED') {
                $dataSet[$key][$hcwKey]['passed']++;
            }

            if (!isset($dataSet[$key][$hcwKey]['percentage'])) {
                $dataSet[$key][$hcwKey]['percentage'] = 0;
            }

            $dataSet[$key][$hcwKey]['percentage'] = round((($dataSet[$key][$hcwKey]['passed'] / $dataSet[$key][$hcwKey]['total']) * 100), 2);
        }

        foreach ($dataSet as $locationName => $loc) {
            foreach ($uniqueHcw as $hcw) {
                if (isset($loc[$hcw])) {
                    $finalDataSet[$locationName][] = $loc[$hcw]['passed'];
                    $finalDataSet[$locationName][] = $loc[$hcw]['total'];
                    $finalDataSet[$locationName][] = "{$loc[$hcw]['percentage']}%";
                } else {
                    $finalDataSet[$locationName][] = "0";
                    $finalDataSet[$locationName][] = "0";
                    $finalDataSet[$locationName][] = "0%";
                }
            }
        }

        return array('unique_hcw' => $uniqueHcw, 'dataset' => $finalDataSet);
    }

    /**
     * @param $location
     * @param $hcw
     * @return PHPExcel_Worksheet
     * @throws PHPExcel_Exception
     */
    public function createAndFormatLocationSheet($location, $hcw)
    {

        $sheet = $this->createSheet($this->locationLookup[$location]['loc_index']);
        $sheet->setTitle($this->locationLookup[$location]['loc']);

        if (!$hcw) {
            $sheet->getStyle("A1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->setCellValue("A1", "No Data to Display");
            return $sheet;
        }

        $centerStyle = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $sheet->getStyle("A1:B2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:B2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');

        $counterLoc1facility = 1;
        $columnIdxfacility = 1;
        $columnLetterfacility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility);
        $columnLetter1facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+1);
        $columnLetter2facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+2);
        foreach ($hcw  as $hcwUniqueLocfacility) {
            //B1
            $sheet->setCellValue($columnLetterfacility.$counterLoc1facility, $hcwUniqueLocfacility);
            $sheet->setCellValue($columnLetterfacility.($counterLoc1facility+1), 'Passed');
            $sheet->setCellValue($columnLetter1facility.($counterLoc1facility+1), 'Total');
            $sheet->setCellValue($columnLetter2facility.($counterLoc1facility+1), '%');

            //D1
            $sheet->mergeCells($columnLetterfacility.$counterLoc1facility.':'.$columnLetter2facility.$counterLoc1facility);
            $sheet->getStyle($columnLetterfacility.$counterLoc1facility.':'.$columnLetter2facility.$counterLoc1facility)->applyFromArray($centerStyle);

            //COLORS
            $sheet->getStyle($columnLetterfacility.$counterLoc1facility)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetterfacility.$counterLoc1facility)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->getStyle($columnLetterfacility.$counterLoc1facility)->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

            $sheet->getStyle($columnLetterfacility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetterfacility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->getStyle($columnLetterfacility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

            $sheet->getStyle($columnLetter1facility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetter1facility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->getStyle($columnLetter1facility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

            $sheet->getStyle($columnLetter2facility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetter2facility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->getStyle($columnLetter2facility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

            $columnIdxfacility = $columnIdxfacility + 3;
            $columnLetterfacility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility);
            $columnLetter1facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+1);
            $columnLetter2facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+2);
        }

        return $sheet;
    }

    /**
     * @param $location
     * @param $locationNameKey
     * @param $data
     */
    public function createLocMomentSheet($location, $locationNameKey, $data)
    {
        $sheet = $this->createAndFormatLocationMomentSheet($location);
        $formattedData = $this->formatDataByLocationMoment($location, $locationNameKey, $data);

        $locations = array_keys($formattedData['dataset']);
        $startRow = 3;
        foreach ($locations as $loc) {
            $sheet->setCellValue("A{$startRow}", $loc);
            $startRow++;
        }

        $sheet->getColumnDimension("A")
            ->setAutoSize(true);

        $sheet->fromArray($formattedData['dataset'], "", "B3");
    }

    /**
     * @param $location
     * @return PHPExcel_Worksheet
     * @throws PHPExcel_Exception
     */
    private function createAndFormatLocationMomentSheet($location)
    {
        $sheet = $this->createSheet($this->locationLookup[$location]['loc_moment_index']);
        $sheet->setTitle($this->locationLookup[$location]['loc_moment']);

        $centerStyle = array(
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            )
        );

        $sheet->getStyle("A1:B2")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("A1:B2")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');

        $moments = array(1,2,3,4,5);
        $counterLoc1facility = 1;
        $columnIdxfacility = 1;
        $columnLetterfacility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility);
        $columnLetter1facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+1);
        $columnLetter2facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+2);
        foreach ($moments  as $moment) {
            //B1
            $sheet->setCellValue($columnLetterfacility.$counterLoc1facility, $moment);
            $sheet->setCellValue($columnLetterfacility.($counterLoc1facility+1), 'Passed');
            $sheet->setCellValue($columnLetter1facility.($counterLoc1facility+1), 'Total');
            $sheet->setCellValue($columnLetter2facility.($counterLoc1facility+1), '%');

            //D1
            $sheet->mergeCells($columnLetterfacility.$counterLoc1facility.':'.$columnLetter2facility.$counterLoc1facility);
            $sheet->getStyle($columnLetterfacility.$counterLoc1facility.':'.$columnLetter2facility.$counterLoc1facility)->applyFromArray($centerStyle);

            //COLORS
            $sheet->getStyle($columnLetterfacility.$counterLoc1facility)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetterfacility.$counterLoc1facility)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->getStyle($columnLetterfacility.$counterLoc1facility)->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

            $sheet->getStyle($columnLetterfacility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetterfacility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->getStyle($columnLetterfacility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

            $sheet->getStyle($columnLetter1facility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetter1facility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->getStyle($columnLetter1facility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

            $sheet->getStyle($columnLetter2facility.($counterLoc1facility+1))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($columnLetter2facility.($counterLoc1facility+1))->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('00b582');
            $sheet->getStyle($columnLetter2facility.($counterLoc1facility+1))->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));

            $columnIdxfacility = $columnIdxfacility + 3;
            $columnLetterfacility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility);
            $columnLetter1facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+1);
            $columnLetter2facility  = PHPExcel_Cell::stringFromColumnIndex($columnIdxfacility+2);
        }

        return $sheet;
    }

    /**
     * @param $location
     * @param $locationNameKey
     * @param $data
     * @return array
     */
    private function formatDataByLocationMoment($location, $locationNameKey, $data)
    {
        $dataSet = $uniqueLocation = $finalDataSet = array();
        $moments = array_keys($this->moments);
        foreach ($data as $datum) {
            if (empty($datum[$location]) || empty($datum[$locationNameKey])) {
                continue;
            }

            foreach ($moments as $key) {

                if (empty($datum[$key])) {
                    continue;
                }

                $locKey = $datum[$locationNameKey];
                if (!in_array($locKey, $uniqueLocation)) {
                    $uniqueLocation[] = $locKey;
                }

                if (!isset($dataSet[$locKey][$key]['total'])) {
                    $dataSet[$locKey][$key]['total'] = 0;
                }

                $dataSet[$locKey][$key]['total']++;

                if (!isset($dataSet[$locKey][$key]['passed'])) {
                    $dataSet[$locKey][$key]['passed'] = 0;
                }

                if (!empty($datum['result']) && $datum['result'] == 'PASSED') {
                    $dataSet[$locKey][$key]['passed']++;
                }

                if (!isset($dataSet[$locKey][$key]['percentage'])) {
                    $dataSet[$locKey][$key]['percentage'] = 0;
                }

                $dataSet[$locKey][$key]['percentage'] = round((($dataSet[$locKey][$key]['passed'] / $dataSet[$locKey][$key]['total']) * 100), 2);
            }
        }

        foreach ($dataSet as $locationName => $loc) {
            foreach ($moments as $moment) {
                if (isset($loc[$moment])) {
                    $finalDataSet[$locationName][] = $loc[$moment]['passed'];
                    $finalDataSet[$locationName][] = $loc[$moment]['total'];
                    $finalDataSet[$locationName][] = $loc[$moment]['percentage'] . "%";
                } else {
                    $finalDataSet[$locationName][] = "0";
                    $finalDataSet[$locationName][] = "0";
                    $finalDataSet[$locationName][] = "0%";
                }
            }
        }

        return array('unique_location' => $uniqueLocation, 'dataset' => $finalDataSet);
    }

    /**
     * @param $data
     * @return array
     */
    private function formatRawData($data)
    {
        $detailsColumns = array(
            'date_registered',
            'username',
            'location_1_name',
            'location_2_name',
            'location_3_name',
            'location_4_name',
            'hcw_titlename',
            'hcw_name',
            'moment1',
            'moment2',
            'moment3',
            'moment4',
            'moment5',
            'hh_compliance',
            'result',
            'hh_compliance_type',
            'glove_compliance',
            'gown_compliance',
            'mask_compliance',
            'mask_type',
            'note'
        );

        $newDataSet = array();
        foreach ($data as $index => $item) {
            foreach ($detailsColumns as $key) {
                $newDataSet[$index][$key] = $item[$key];
            }
        }

        return $newDataSet;
    }

    /**
     * @return PHPExcel_Worksheet
     * @throws PHPExcel_Exception
     */
    private function createAndFormatMomentHcwSheet()
    {
        $this->sheetIndex++;
        $sheet = $this->createSheet($this->sheetIndex);
        $sheet->setTitle("Moment-Hcw");

        $sheet->getStyle('A1:P2')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('00b582');

        $sheet->mergeCells('B1:D1')
            ->mergeCells('E1:G1')
            ->mergeCells('H1:J1')
            ->mergeCells('K1:M1')
            ->mergeCells('N1:P1');

        $sheet->setCellValue("B1", 'Moment 1')
            ->setCellValue("E1", 'Moment 2')
            ->setCellValue("H1", 'Moment 3')
            ->setCellValue("K1", 'Moment 4')
            ->setCellValue("N1", 'Moment 5');

        $subHeaders = array(
            'Passed',
            'Total',
            '%'
        );
        $sheet->fromArray($subHeaders, null, "B2");
        $sheet->fromArray($subHeaders, null, "E2");
        $sheet->fromArray($subHeaders, null, "H2");
        $sheet->fromArray($subHeaders, null, "K2");
        $sheet->fromArray($subHeaders, null, "N2");

        $sheet->getStyle('A1:P2')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));
        $sheet->getStyle('A1:P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B3:P5000')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

        $sheet->getColumnDimension("A")
            ->setAutoSize(true);

        return $sheet;
    }

    /**
     * @param $data
     * @return array
     */
    private function formatDataforMomentHcw($data)
    {
        $newDataSet = array();
        foreach ($data as $datum) {
            if (!isset($datum['hcw_title']) || empty($datum['hcw_titlename'])) {
                continue;
            }

            foreach (array_keys($this->moments) as $moment) {
                if (empty($datum[$moment])) {
                    continue;
                }

                $key = "{$datum['hcw_titlename']} (ID {$datum['hcw_title']})";
                if (!isset($newDataSet[$key][$moment]['total'])) {
                    $newDataSet[$key][$moment]['total'] = 0;
                }

                $newDataSet[$key][$moment]['total']++;

                if (!isset($newDataSet[$key][$moment]['passed'])) {
                    $newDataSet[$key][$moment]['passed'] = 0;
                }

                if (isset($datum['result']) && $datum['result'] == 'PASSED') {
                    $newDataSet[$key][$moment]['passed']++;
                }

                if (!isset($newDataSet[$key][$moment]['percentage'])) {
                    $newDataSet[$key][$moment]['percentage'] = 0;
                }

                $newDataSet[$key][$moment]['percentage'] = round((($newDataSet[$key][$moment]['passed'] / $newDataSet[$key][$moment]['total']) * 100), 2);
            }
        }

        $finalResultSet = array();
        foreach ($newDataSet as $hcw => $momentStats) {
            $finalResultSet[$hcw][] = $hcw;
            foreach (array_keys($this->moments) as $moment) {
                if (isset($momentStats[$moment])) {
                    $finalResultSet[$hcw][] = $momentStats[$moment]['passed'];
                    $finalResultSet[$hcw][] = $momentStats[$moment]['total'];
                    $finalResultSet[$hcw][] = "{$momentStats[$moment]['percentage']}%";
                } else {
                    $finalResultSet[$hcw][] = "0";
                    $finalResultSet[$hcw][] = "0";
                    $finalResultSet[$hcw][] = "0%";
                }
            }
        }

        return $finalResultSet;
    }

    /**
     * @return PHPExcel_Worksheet
     * @throws PHPExcel_Exception
     */
    private function createAndFormatDetailsSheet()
    {
        $sheet = $this->getActiveSheet();
        $sheet->setTitle("Details");

        $sheet->getStyle('A1:U2')
            ->getFill()
            ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('00b582');

        $sheet->getDefaultStyle()->getFont()->setSize(11)->setName('Arial');
        $sheet->getStyle("A1:Z2")->getFont()->setBold(true);

        $sheet->mergeCells('C1:F1');
        $sheet->mergeCells('G1:H1');
        $sheet->mergeCells('I1:O1');
        $sheet->mergeCells('I2:M2');

        $sheet->getColumnDimension('A')->setWidth(19.33);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(9.17);
        $sheet->getColumnDimension('D')->setWidth(14.33);
        $sheet->getColumnDimension('E')->setWidth(10.17);
        $sheet->getColumnDimension('F')->setWidth(10.17);
        $sheet->getColumnDimension('G')->setWidth(15);
        $sheet->getColumnDimension('H')->setWidth(18);
        $sheet->getColumnDimension('I')->setWidth(4.17);
        $sheet->getColumnDimension('J')->setWidth(4.17);
        $sheet->getColumnDimension('K')->setWidth(4.17);
        $sheet->getColumnDimension('L')->setWidth(4.17);
        $sheet->getColumnDimension('M')->setWidth(4.17);
        $sheet->getColumnDimension('N')->setWidth(10.17);
        $sheet->getColumnDimension('O')->setWidth(15);
        $sheet->getColumnDimension('P')->setWidth(10);
        $sheet->getColumnDimension('Q')->setWidth(13.67);
        $sheet->getColumnDimension('R')->setWidth(7.67);
        $sheet->getColumnDimension('S')->setWidth(7.67);
        $sheet->getColumnDimension('T')->setWidth(7.67);
        $sheet->getColumnDimension('U')->setWidth(12.83);
        $sheet->getColumnDimension('V')->setWidth(29.17);

        $sheet->setCellValue("C1", 'LOCATION LEVEL');
        $sheet->setCellValue("G1", 'HEALTHCARE WORKER');
        $sheet->setCellValue("I1", 'HAND HYGIENE COMPLIANCE');
        $sheet->setCellValue("P1", 'Occupational');
        $sheet->setCellValue("A2", 'Date & Time');
        $sheet->setCellValue("B2", 'Auditor');
        $sheet->setCellValue("C2", '1');
        $sheet->setCellValue("D2", '2');
        $sheet->setCellValue("E2", '3');
        $sheet->setCellValue("F2", '4');
        $sheet->setCellValue("G2", 'Title');
        $sheet->setCellValue("H2", 'Name');
        $sheet->setCellValue("I2", 'Moment');
        $sheet->setCellValue("N2", 'Action');
        $sheet->setCellValue("O2", 'Result');
        $sheet->setCellValue("P2", 'Exposure');
        $sheet->setCellValue("Q2", 'GLOVES Risk');
        $sheet->setCellValue("R2", 'GOWN');
        $sheet->setCellValue("S2", 'MASK');
        $sheet->setCellValue("T2", 'Mask Type');
        $sheet->setCellValue("U2", 'Notes');

        $sheet->getStyle('A1:V2')->getFont()->applyFromArray(array('color' => array('rgb' => 'FFFFFF')));
        $sheet->getStyle('A1:V2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        return $sheet;
    }
}