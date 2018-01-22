<?php
/**
 * Class Mdl_Charts
 */
class Mdl_charts extends CI_Model
{
    const LOCATION1 = 'loc1';
    const LOCATION2 = "loc2";
    const LOCATION3 = "loc3";
    const LOCATION4 = "loc4";
    const HEALTHCARE_WORKER = "hcw";
    const COUNT_PER_MOMENT= "cpm";
    const COMPLIANCE_BY_MOMENT = "cbm";
    const LOCATION1_BY_HCW = "loc1hcw";
    const LOCATION1_PER_MOMENT = "loc1m";
    const LOCATION2_BY_HCW = "loc2hcw";
    const LOCATION2_PER_MOMENT = "loc2m";
    const LOCATION3_BY_HCW = "loc3hcw";
    const LOCATION3_PER_MOMENT = "loc3m";
    const LOCATION4_BY_HCW = "loc4hcw";
    const LOCATION4_PER_MOMENT = "loc4m";

    const PASSED = 'PASSED';
    const FAILED = 'FAILED';

    /**
     * @param $chartType
     * @param $data
     * @return array
     */
    public function generateChartDataSets($chartType, $data)
    {
        switch ($chartType) {
            case self::LOCATION1:
                $location = 'location_level1';
                $locationName = 'location_1_name';
                return $this->parseByLocation($location, $locationName, $data);
            case self::LOCATION2:
                $location = 'location_level2';
                $locationName = 'location_2_name';
                return $this->parseByLocation($location, $locationName, $data);
            case self::LOCATION3:
                $location = 'location_level3';
                $locationName = 'location_3_name';
                return $this->parseByLocation($location, $locationName, $data);
            case self::LOCATION4:
                $location = 'location_level4';
                $locationName = 'location_4_name';
                return $this->parseByLocation($location, $locationName, $data);
            case self::HEALTHCARE_WORKER:
                return $this->parseByHcw($data);
            case self::COUNT_PER_MOMENT:
                return $this->parseByMoment($data);
            case self::COMPLIANCE_BY_MOMENT:
                return $this->parseByMoment($data, true);
            case self::LOCATION1_BY_HCW:
                return $this->parseByLocationHcw('location_level1', $data);
            case self::LOCATION1_PER_MOMENT:
                $locationName = 'location_1_name';
                return $this->parseByLocationMoment('location_level1', $data, $locationName);
            case self::LOCATION2_BY_HCW:
                return $this->parseByLocationHcw('location_level2', $data);
            case self::LOCATION2_PER_MOMENT:
                $locationName = 'location_2_name';
                return $this->parseByLocationMoment('location_level2', $data, $locationName);
            case self::LOCATION3_BY_HCW:
                return $this->parseByLocationHcw('location_level3', $data);
            case self::LOCATION3_PER_MOMENT:
                $locationName = 'location_3_name';
                return $this->parseByLocationMoment('location_level3', $data, $locationName);
            case self::LOCATION4_BY_HCW:
                return $this->parseByLocationHcw('location_level4', $data);
            case self::LOCATION4_PER_MOMENT:
                $locationName = 'location_4_name';
                return $this->parseByLocationMoment('location_level4', $data, $locationName);
        }
    }

    /**
     * @param $location
     * @param $locationName
     * @param $chartData
     * @return array
     */
    private function parseByLocation($location, $locationName, $chartData)
    {
        $dataSet = array();
        foreach ($chartData as $datum) {
            if (isset($datum[$location]) && !empty($datum[$location])) {
                $dataSet[$datum[$location]]['label'] = $datum[$locationName];

                if (!isset($dataSet[$datum[$location]]['total'])) {
                    $dataSet[$datum[$location]]['total'] = 0;
                }

                $dataSet[$datum[$location]]['total']++;

                if ($datum['result'] == self::PASSED) {
                    if (!isset($dataSet[$datum[$location]]['passed'])) {
                        $dataSet[$datum[$location]]['passed'] = 0;
                    }

                    $dataSet[$datum[$location]]['passed']++;
                }
            }
        }

        return $this->generateDataSets($dataSet);
    }

    /**
     * @param $chartData
     * @return array
     */
    private function parseByHcw($chartData)
    {
        $dataSet = array();
        foreach ($chartData as $datum) {
            $key = $datum['hcw_title'];
            if (isset($datum['hcw_title']) && !empty($datum['hcw_title'])) {
                $dataSet[$key]['label'] = $datum['hcw_titlename'];
            }

            if (!isset($dataSet[$key]['total'])) {
                $dataSet[$key]['total'] = 0;
            }

            $dataSet[$key]['total']++;

            if ($datum['result'] == self::PASSED) {
                if (!isset($dataSet[$key]['passed'])) {
                    $dataSet[$key]['passed'] = 0;
                }

                $dataSet[$key]['passed']++;
            }
        }

        return $this->generateDataSets($dataSet);
    }

    /**
     * @param $chartData
     * @param bool $blnWithCompliance
     * @return array
     */
    private function parseByMoment($chartData, $blnWithCompliance=false)
    {
        $dataSet = array();
        $moments = array(
            'moment1' => 'Moment 1',
            'moment2' => 'Moment 2',
            'moment3' => 'Moment 3',
            'moment4' => 'Moment 4',
            'moment5' => 'Moment 5',
        );
        foreach ($moments as $key => $moment) {
            foreach ($chartData as $datum) {
                if (isset($datum[$key]) && !empty($datum[$key])) {
                    $dataSet[$key]['label'] = $moment;
                } else {
                    continue;
                }

                if (!isset($dataSet[$key]['total'])) {
                    $dataSet[$key]['total'] = 0;
                }

                $dataSet[$key]['total']++;
                if (!isset($dataSet[$key]['passed'])) {
                    $dataSet[$key]['passed'] = 0;
                }

                if ($datum['result'] == self::PASSED) {
                    $dataSet[$key]['passed']++;
                }
            }
        }

        if ($blnWithCompliance) {
            return $this->generateDataSets($dataSet);
        }

        return $this->generateDataSetsForCount($dataSet);
    }

    /**
     * @param $location
     * @param $chartData
     * @return array
     */
    private function parseByLocationHcw($location, $chartData)
    {
        $dataSet = array();
        foreach ($chartData as $datum) {
            if (!isset($datum[$location]) || empty($datum[$location])) {
                continue;
            }

            $key = $datum['hcw_title'];
            if (isset($datum['hcw_title']) && !empty($datum['hcw_title'])) {
                $dataSet[$key]['label'] = $datum['hcw_titlename'];
            }

            if (!isset($dataSet[$key]['total'])) {
                $dataSet[$key]['total'] = 0;
            }

            $dataSet[$key]['total']++;

            if ($datum['result'] == self::PASSED) {
                if (!isset($dataSet[$key]['passed'])) {
                    $dataSet[$key]['passed'] = 0;
                }

                $dataSet[$key]['passed']++;
            }
        }

        return $this->generateDataSets($dataSet);
    }

    /**
     * @param $location
     * @param $chartData
     * @return array
     */
    private function parseByLocationMoment($location, $chartData, $locationKeyName)
    {
        $dataSet = array();
        $moments = array(
            'moment1' => 'Moment 1',
            'moment2' => 'Moment 2',
            'moment3' => 'Moment 3',
            'moment4' => 'Moment 4',
            'moment5' => 'Moment 5',
        );

        foreach ($chartData as $datum) {
            if (!isset($datum[$location]) || empty($datum[$location]) || empty($datum[$locationKeyName])) {
                continue;
            }

            $dataSet[$datum[$location]]['label'] = $datum[$locationKeyName];
            foreach ($moments as $key => $moment) {

                $dataSet[$datum[$location]]['data'][$key]['label'] = $moment;

                if (empty($dataSet[$datum[$location]]['data'][$key]['total'])) {
                    $dataSet[$datum[$location]]['data'][$key]['total'] = 0;
                }

                if (empty($dataSet[$datum[$location]]['data'][$key]['passed'])) {
                    $dataSet[$datum[$location]]['data'][$key]['passed'] = 0;
                }

                if (!empty($datum[$key])) {
                    $dataSet[$datum[$location]]['data'][$key]['total']++;

                    if ($datum['result'] == self::PASSED) {
                        $dataSet[$datum[$location]]['data'][$key]['passed']++;
                    }
                }
            }
        }

        foreach ($dataSet as $locationId => $loc) {
            foreach ($loc['data'] as $key => $moment) {
                $dataSet[$locationId]['data'][$key]['percentage'] = (isset($moment['passed']) && isset($moment['total']) && $moment['total'] > 0) ? round((($moment['passed'] / $moment['total']) * 100), 2) : 0;
            }
        }

        return $this->generateChartDataSetsForLocationMoment($dataSet);
    }

    /**
     * @param $dataSet
     * @return array
     */
    private function generateDataSets($dataSet)
    {
        $dataPercentage = array(
            'columns' => array(),
            'values' => array(),
        );
        foreach ($dataSet as $key => $item) {
            if (isset($item['passed']) && isset($item['label']) && isset($item['total'])) {
                $dataPercentage['columns'][] = $item['label'];
                $dataPercentage['values'][] = round((($item['passed'] / $item['total']) * 100), 2);
            }
        }

        return $dataPercentage;
    }

    /**
     * @param $dataSet
     * @return array
     */
    private function generateDataSetsForCount($dataSet)
    {
        $data = array(
            'columns' => array(),
            'values' => array(),
        );
        foreach ($dataSet as $item) {
            $data['columns'][] = $item['label'];
            $data['values'][] = !empty($item['passed']) ? $item['passed'] : 0;
        }

        return $data;
    }

    /**
     * Format location per moment values
     *
     * @param array $dataSet
     * @return array
     */
    private function generateChartDataSetsForLocationMoment($dataSet) {
        $data = array();
        foreach ($dataSet as $key => $value) {
            $data[$key]['label'] = $value['label'];
            foreach ($value['data'] as $val) {
                $data[$key]['columns'][] = $val['label'];
                $data[$key]['values'][] = $val['percentage'];
            }
        }

        return $data;
    }
}
