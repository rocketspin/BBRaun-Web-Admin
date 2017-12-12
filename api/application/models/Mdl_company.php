<?php
/**
 * Class Mdl_Institutions
 *
 */

class Mdl_company extends CI_Model
{

    /**
     * Mdl_company constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getInstitutions()
    {
        $query = $this->db->select('id, name')
            ->from('company');

        return $query->get()->result_array();
    }
}