<?php


/**
 * Payments_model class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */



class Payments_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Get product by his is
    * @param int $product_id 
    * @return array
    */
    public function get_payments_by_id($id)
    {
        /*$this->db->select('*');
        $this->db->from('release');
        $this->db->where('id', $id); */

        $this->db->select('*');
        $this->db->select('payments.id as id');
        $this->db->select('payments.client_id');
        $this->db->select('manufacturers.name as manufacture_name');
        $this->db->from('payments');

        $this->db->join('manufacturers', 'payments.client_id = manufacturers.id', 'left');
        
        $this->db->where('payments.id', $id);
        
        //$this->db->where('stock.type', 0);

        $query = $this->db->get();

        return $query->result_array(); 
    }

    /**
    * Fetch products data from the database
    * possibility to mix search, filter and order
    * @param int $manufacuture_id 
    * @param string $search_string 
    * @param strong $order
    * @param string $order_type 
    * @param int $limit_start
    * @param int $limit_end
    * @return array
    */
    
    public function get_payments($manufacture_id=null, $search_string=null, $order=null, $order_type='Asc', $limit_start=null, $limit_end=null)
    {
        
        $this->db->select('*');
        $this->db->select('payments.id as id');
        $this->db->select('payments.client_id');
        $this->db->select('manufacturers.name as manufacture_name');
        $this->db->from('payments');
        if($manufacture_id != null && $manufacture_id != 0){
            $this->db->where('client_id', $manufacture_id);
        }

        $this->db->join('manufacturers', 'payments.client_id = manufacturers.id', 'inner');
        //$this->db->group_by('payments.id');

        if($search_string){
            $this->db->like('manufacturers.name', $search_string);
        }

        if($order){
            $this->db->order_by($order, $order_type);
        }else{
            $this->db->order_by('payments.id', $order_type);
        }


        $this->db->limit($limit_start, $limit_end);
        //$this->db->limit('4', '4');

        //$this->db->where('stock.type', 0);


        $query = $this->db->get();
        
        return $query->result_array();  
    }

    /**
    * Count the number of rows
    * @param int $manufacture_id
    * @param int $search_string
    * @param int $order
    * @return int
    */
    function count_payments($manufacture_id=null, $search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('payments');
        if($manufacture_id != null && $manufacture_id != 0){
            $this->db->where('manufacture_id', $manufacture_id);
        }
        if($search_string){
            //$this->db->like('description', $search_string);
        }
        if($order){
            $this->db->order_by($order, 'Asc');
        }else{
            $this->db->order_by('id', 'Asc');
        }

        //$this->db->where('stock.type', 0);

        $query = $this->db->get();
        return $query->num_rows();        
    }

    public function getTotalPayments($manufacture_id){
        $this->db->select('sum(amount) as sum_total_paid');
        $this->db->from('payments');
        $this->db->where('client_id', $manufacture_id);

        return $query = $this->db->get()->result();
        
        //$query->result_array(); 
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_payments($data)
    {
        $insert = $this->db->insert('payments', $data);
        return $insert;
    }

    /**
    * Update product
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_payments($id, $data)
    {
        $this->db->where('id', $id);
        
        if($this->db->update('payments', $data)){

            return true;
        }else{
            return false;
        }
    }

    /**
    * Delete product
    * @param int $id - product id
    * @return boolean
    */
    function delete_payment($id){
        $this->db->where('id', $id);
        $this->db->delete('payments'); 
    }

    function autocomplete($keyword=null, $client_id=null){

        $this->db->like('name', $keyword);
        $this->db->from('manufacturers');
        
        $query = $this->db->get();
        return $query->result_array(); 
    }

    function autocomplete_pro($client_id){

            $this->db->select('*');
            $this->db->from('products');
            $this->db->where('manufacture_id', $client_id);

        $query = $this->db->get();
        return $query->result_array(); 
    }
 
}
?>  
