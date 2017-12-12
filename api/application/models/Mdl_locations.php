<?php
/**
 * Class Mdl_locations
 */
class Mdl_locations extends CI_Model
{
    /**
     * @param null $companyId
     * @return mixed
     */
    public function getHealthCareWorkers($companyId = null)
    {
        $query = $this->db->select('id, cid, name')
            ->from('locations')
            ->where('category', 'healthcare');

        if ($companyId) {
            $query->where('cid', $companyId);
        }

        return $query->get()->result_array();
    }

    /**
     * @param null $companyId
     * @return mixed
     */
    public function getLocations($companyId = null)
    {
        $query = $this->db->select('id, cid, name, category')
            ->from('locations')
            ->where_in('category', array('location1', 'location2', 'location3', 'location4'));

        if ($companyId) {
            $query->where('cid', $companyId);
        }

        return $query->get()->result_array();
    }
}