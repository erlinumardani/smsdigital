<?php
 
class Datatable_model extends CI_Model {
 
    public function __construct()
    {
        parent::__construct();
    }
 
    private function _get_datatables_query($table, $column_order, $column_search, $order, $filter='created_at IS NOT Null')
    {
         
        $this->db->from($table)->where($filter);
 
        $i = 0;
     
        foreach ($column_search as $item) // looping awal
        {
            if($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {
                 
                if($i===0) // looping awal
                {
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                }
                else
                {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
 
                if(count($column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) 
        {
            $this->db->order_by($column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($order))
        {
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
 
    function get_datatables($table, $column_order, $column_search, $order, $filter='created_at IS NOT Null')
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $filter);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
 
    function count_filtered($table, $column_order, $column_search, $order, $filter='created_at IS NOT Null')
    {
        $this->_get_datatables_query($table, $column_order, $column_search, $order, $filter);
        $query = $this->db->get();
        return $query->num_rows();
    }
 
    public function count_all($table)
    {
        $this->db->from($table);
        return $this->db->count_all_results();
    }
 
}