<?php


/**
 * Reports_model class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */



class Reports_model extends CI_Model {
 
    /**
    * Responsable for auto load the database
    * @return void
    */
    public $in, $out;

    public function __construct()
    {
        $this->load->database();
    }

    /**
    * Get product by his is
    * @param int $product_id 
    * @return array
    */
    public function get_client_report($id)
    {
		/*$this->db->select('*');
		$this->db->from('release');
		$this->db->where('id', $id); */

		$this->db->select('*');
		$this->db->select('stock.id as id');
		$this->db->select('stock.manufacture_id');
		$this->db->select('manufacturers.name as manufacture_name');
        $this->db->order_by('stock.created_at','Desc');
		$this->db->from('stock');

		$this->db->join('manufacturers', 'stock.manufacture_id = manufacturers.id', 'left');
		$this->db->join('measurements', 'stock.measurement_id = measurements.measurement_id', 'left');
		$this->db->join('products', 'stock.product_id = products.id', 'left');
	
		$this->db->where('stock.manufacture_id', $id);
        $this->db->where('stock.type', 0);
        $this->db->where('quantity !=', 0);
		

		//$this->db->where('stock.type', 0);

		$query = $this->db->get();
		return $query->result_array(); 
    }

    public function get_client_report_in($id)
    {
        /*$this->db->select('*');
        $this->db->from('release');
        $this->db->where('id', $id); */

        $this->db->select('*');
        $this->db->select('stock.id as id');
        $this->db->select('stock.manufacture_id');
        $this->db->select('manufacturers.name as manufacture_name');
        $this->db->order_by('stock.created_at','Desc');
        $this->db->from('stock');

        $this->db->join('manufacturers', 'stock.manufacture_id = manufacturers.id', 'left');
        $this->db->join('measurements', 'stock.measurement_id = measurements.measurement_id', 'left');
        $this->db->join('products', 'stock.product_id = products.id', 'left');
    
        $this->db->where('stock.manufacture_id', $id);
        $this->db->where('stock.type', 1);
        

        //$this->db->where('stock.type', 0);

        $query = $this->db->get();
        return $query->result_array(); 
    }

    public function get_clients_products_all($manufacture_id){
    	$this->db->select('*');
    	$this->db->where('manufacture_id', $manufacture_id);
    	$this->db->from('products');
		$this->db->join('manufacturers', 'products.manufacture_id = manufacturers.id', 'left');
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

    public function getSum_in($manufacture_id) {
        $this->db->select('*');
    	$this->db->select('sum(quantity) as sum_in');
    	$this->db->from('stock');
    	$this->db->where('type', 1);
    	$this->db->where('stock.manufacture_id', $manufacture_id);
    	$this->db->join('products', 'stock.product_id = products.id', 'left');
    	$this->db->group_by('products.id');

    	$query = $this->db->get();

    	return $in = $query->result_array();

    	//$out = $this->getSum_out($manufacture_id);

		//return array($out, $in);
		  
	}

	public function getSum_out($manufacture_id) {
        $this->db->select('*');
    	$this->db->select('sum(quantity) as sum_out');
    	$this->db->from('stock');
    	$this->db->where('type', 0);
    	$this->db->where('stock.manufacture_id', $manufacture_id);
    	$this->db->join('products', 'stock.product_id = products.id', 'left');
    	$this->db->group_by('products.id');

    	$this->out = $query = $this->db->get();
		return $query->result_array();
		  
	}

    public function array_add_by_key( $array1, $array2 ) { //(in, out)
          foreach ( $array2 as $k => $a ) {
              if ( array_key_exists( $k, $array1 ) ) {
                  $array1[$k] += $a;
              } else {
                  $array1[$k] = $a;
              }
          }
          return $array1;
    }

    public function multiply_product_by_storage_fee($manufacture_id){
        $this->db->select('*');
        $this->db->select('products.storage_fee');
        $this->db->select('sum(quantity) * products.storage_fee as storage_charge');
        $this->db->from('stock');
        $this->db->where('type', 1);
        $this->db->where('stock.driver_name !=', 'repackage');
        $this->db->where('stock.manufacture_id', $manufacture_id);
        $this->db->where('products.manufacture_id', $manufacture_id);
        $this->db->join('products', 'stock.product_id = products.id', 'left');
        $this->db->group_by('product_id');

        $this->out = $query = $this->db->get();
        return $query->result_array();

    }

    public function repackage_fees($manufacture_id){
        $this->db->select('sum(quantity * fees) as fees');
        $this->db->where('manufacture_id', $manufacture_id);
        $this->db->group_by('manufacture_id');
        $this->db->from('repackage');

        $this->out = $query = $this->db->get();
        return $query->result_array();
    }

 }

 ?>