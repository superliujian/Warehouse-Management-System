<?php

/**
 * Expense_model class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */


class Expense_model extends CI_Model {
 
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
    public function get_expense_by_id($id)
    {

        $this->db->select('*');
        $this->db->from('expense');

        $this->db->where('id', $id);

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

    public function get_expenses_sum(){
        $this->db->select('sum(each * quantity) as total_spent_today');
        $this->db->from('expense');
    }
    
    public function get_expenses($today = null, $search_string=null, $order=null, $order_type=null, $limit_start=null, $limit_end=null)
    {
        
        $this->db->select('*');
        $this->db->from('expense');

        if($search_string){
            $this->db->like('description', $search_string);
            $this->db->or_like('memo', $search_string);
            $this->db->or_like('created_at', $search_string);
        }

        if($today){
            $this->db->where('created_at > ', date('Y-m-d'));
        }

       $this->db->order_by('created_at', 'Desc');

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
    function count_expenses($search_string=null, $order=null)
    {
        $this->db->select('*');
        $this->db->from('expense');
        
        $query = $this->db->get();
        return $query->num_rows();        
    }

    function totalAmountCollected_storage(){

        $this->db->select_sum('amount');
        $query = $this->db->get('payments');

       // $amt = $query->result();

        $x = array();

        foreach ($query->result() as $row)
            {
               $x[] =  $row->amount;

            }
            return $x[0];
    }

    function totalAmountCollected_repackage(){

        $this->db->select('sum(quantity * fees) as amount');
        $query = $this->db->get('repackage');

       // $amt = $query->result();

        $x = array();

        foreach ($query->result() as $row)
            {
               $x[] =  $row->amount;

            }
            return $x[0];
    }

    function totalAmountSpent($today = null){
        $this->db->select('SUM(`each` * `quantity`) as total_spent');

        if($today){
            $this->db->where('created_at > ', date('Y-m-d'));
        }

        $query = $this->db->get('expense');

        

        $x = array();

        foreach ($query->result() as $row)
            {
               $x[] =  $row->total_spent;

            }
            return $x[0];

    }

    function cashAtHand(){
        return $this->totalAmountCollected_storage() + $this->totalAmountCollected_repackage() - $this->totalAmountSpent();
    }

    /**
    * Store the new item into the database
    * @param array $data - associative array with data to store
    * @return boolean 
    */
    function store_expenses($data)
    {
        $insert = $this->db->insert('expense', $data);
        return $insert;
    }

    /**
    * Update product
    * @param array $data - associative array with data to store
    * @return boolean
    */
    function update_expense($id, $data)
    {
        $this->db->where('id', $id);
        
        if($this->db->update('expense', $data)){

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
    function delete_expense($id){
        $this->db->where('id', $id);
        $this->db->delete('expense'); 
    }

    function autocomplete($keyword){

        $this->db->like('name', $keyword);
        $this->db->from('manufacturers');
        $query = $this->db->get();
        return $query->result_array(); 
    }
 
}
?>  
