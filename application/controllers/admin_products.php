<?php

/**
 * Admin_products class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */

class Admin_products extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('products_model');
        $this->load->model('manufacturers_model');
        $this->load->model('measurements_model');
        $this->load->model('release_model');
        $this->load->model('stock_model');

        if(!$this->session->userdata('is_logged_in')){
            redirect('admin/login');
        }
    }
 
    /**
    * Load the main view with all the current model model's data.
    * @return void
    */
    public function index()
    {

        //all the posts sent by the view
        $manufacture_id = $this->input->post('manufacture_id');        
        $search_string = $this->input->post('search_string');        
        $order = $this->input->post('order'); 
        $order_type = $this->input->post('order_type'); 

        //pagination settings
        $config['per_page'] = 20;
        $config['base_url'] = base_url().'admin/products';
        $config['use_page_numbers'] = TRUE;
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a>';
        $config['cur_tag_close'] = '</a></li>';

        //limit end
        $page = $this->uri->segment(3);

        //math to get the initial record to be select in the database
        $limit_end = ($page * $config['per_page']) - $config['per_page'];
        if ($limit_end < 0){
            $limit_end = 0;
        } 

        //if order type was changed
        if($order_type){
            $filter_session_data['order_type'] = $order_type;
        }
        else{
            //we have something stored in the session? 
            if($this->session->userdata('order_type')){
                $order_type = $this->session->userdata('order_type');    
            }else{
                //if we have nothing inside session, so it's the default "Asc"
                $order_type = 'Asc';    
            }
        }
        //make the data type var avaible to our view
        $data['order_type_selected'] = $order_type;        


        //we must avoid a page reload with the previous session data
        //if any filter post was sent, then it's the first time we load the content
        //in this case we clean the session filter data
        //if any filter post was sent but we are in some page, we must load the session data

        //filtered && || paginated
        if($manufacture_id !== false && $search_string !== false && $order !== false || $this->uri->segment(3) == true){ 
           
            /*
            The comments here are the same for line 79 until 99

            if post is not null, we store it in session data array
            if is null, we use the session data already stored
            we save order into the the var to load the view with the param already selected       
            */

            if($manufacture_id !== 0){
                $filter_session_data['manufacture_selected'] = $manufacture_id;
            }else{
                $manufacture_id = $this->session->userdata('manufacture_selected');
            }
            $data['manufacture_selected'] = $manufacture_id;

            if($search_string){
                $filter_session_data['search_string_selected'] = $search_string;
            }else{
                $search_string = $this->session->userdata('search_string_selected');
            }
            $data['search_string_selected'] = $search_string;

            if($order){
                $filter_session_data['order'] = $order;
            }
            else{
                $order = $this->session->userdata('order');
            }
            $data['order'] = $order;

            //save session data into the session
            $this->session->set_userdata($filter_session_data);

            //fetch manufacturers data into arrays
            $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

            $data['count_products']= $this->products_model->count_products($manufacture_id, $search_string, $order);
            $config['total_rows'] = $data['count_products'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['products'] = $this->products_model->get_products($manufacture_id, $search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->products_model->get_products($manufacture_id, $search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['products'] = $this->products_model->get_products($manufacture_id, '', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->products_model->get_products($manufacture_id, '', '', $order_type, $config['per_page'],$limit_end);        
                }
            }

        }else{

            //clean filter data inside section
            $filter_session_data['manufacture_selected'] = null;
            $filter_session_data['search_string_selected'] = null;
            $filter_session_data['order'] = null;
            $filter_session_data['order_type'] = null;
            $this->session->set_userdata($filter_session_data);

            //pre selected options
            $data['search_string_selected'] = '';
            $data['manufacture_selected'] = 0;
            $data['order'] = 'id';

            //fetch sql data into arrays
            $data['manufactures'] = $this->manufacturers_model->get_manufacturers();
            $data['count_products']= $this->products_model->count_products();
            $data['products'] = $this->products_model->get_products('', $search_string, '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_products'];

        }//!isset($manufacture_id) && !isset($search_string) && !isset($order)

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/products/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
            $this->form_validation->set_rules('description', 'Stock name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('measurement_name', 'Measurement', 'trim|required|xss_clean');
            $this->form_validation->set_rules('storage_fee', 'Storage Fee', 'trim|required|xss_clean');
            $this->form_validation->set_rules('penalty_fee', 'Penalty Fee', 'trim|required|xss_clean');
            $this->form_validation->set_rules('repackaging_fee', 'Repackaging Fee', 'trim|xss_clean');
            $this->form_validation->set_rules('manufacture_id', 'Client', 'required|is_natural_no_zero|xss_clean');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'description'           => $this->input->post('description'),
                    'measurement_name'      =>  $this->input->post('measurement_name'),
                    'penalty_fee'           => $this->input->post('penalty_fee'),
                    'storage_fee'           => $this->input->post('storage_fee'),  
                    'repackaging_fee'       => $this->input->post('repackaging_fee'),        
                    'manufacture_id'        => $this->input->post('manufacture_id')
                );
                //if the insert has returned true then we show the flash message
                if($this->products_model->store_product($data_to_store)){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }

        }
        //fetch manufactures data to populate the select field
        $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        //fetch measurements data to populate the select field
        $data['measurements'] = $this->measurements_model->get_measurements();


        //load the view
        $data['main_content'] = 'admin/products/add';
        $this->load->view('includes/template', $data);  
    }       

    /**
    * Update item by his id
    * @return void
    */
    public function update()
    {
        //product id 
        $id = $this->uri->segment(4);
  
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {
            //form validation
            $this->form_validation->set_rules('description', 'Stock name', 'trim|required|xss_clean');
            $this->form_validation->set_rules('storage_fee', 'Storage Fee', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('measurement_name', 'Repackage To', 'required|xss_clean');
            $this->form_validation->set_rules('penalty_fee', 'Penalty Fee', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('repackaging_fee', 'Repackaging Fee', 'trim|numeric|xss_clean');
            //$this->form_validation->set_rules('manufacture_id', 'Client', 'trim|required|numeric|xss_clean');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(
                    'description'       =>  $this->input->post('description'),
                    'measurement_name'  =>  $this->input->post('measurement_name'),
                    'penalty_fee'       =>  $this->input->post('penalty_fee'),
                    'storage_fee'       =>  $this->input->post('storage_fee'),
                    'repackaging_fee'   =>  $this->input->post('repackaging_fee')          
                    //'manufacture_id'  => $this->input->post('manufacture_id')
                );

                //if the insert has returned true then we show the flash message
                if($this->products_model->update_product($id, $data_to_store) == TRUE){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }
                //redirect(base_url("admin").'/products');

            }//validation run

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //product data 
        $data['product'] = $this->products_model->get_product_by_id($id);
        //fetch manufactures data to populate the select field
        $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        $data['measurements'] = $this->measurements_model->get_measurements();
        //load the view
        $data['main_content'] = 'admin/products/edit';
        $this->load->view('includes/template', $data);            

    }//update

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {

        //product id 
        $id = $this->input->post('delete_id');

        if(!$this->products_model->has_stock($id)){
            $this->products_model->delete_product($id);
             echo '1';
        }
            echo '0';
    }//edit

    public function valid($str){
        if($this->input->post('from_unit') != '50kg Bags'){
            if($this->input->post('from_unit') == '25kg Bags' && $this->input->post('to_unit') == '25kg Bags'){
               //$this->form_validation->set_message('valid','Unsupported repackaging');
                return true;
                die;  
            } else if($this->input->post('from_unit') == '100kg Bags') {
                if($this->input->post('to_unit') != '50kg Bags'){
                    $this->form_validation->set_message('valid','Unsupported repackaging');
                    return false;
                } return true;

            } else {

                $this->form_validation->set_message('valid','Unsupported repackaging');
                return false;
            }
            
        } else {
            
            if($this->input->post('to_unit') != '25kg Bags' && $this->input->post('to_unit') != '50kg Bags'){
               
                $this->form_validation->set_message('valid','Unsupported repackaging');
                return false;
                die;
            } 
        } 
    }

    public function setfees($str){
        if($str < 1){
            $this->form_validation->set_message('setfees','Please set the repackaging fees first. Go back and edit this product.');
            return false;
        } return true;
    }

    public function nostock($str){
        if($str == 0){
            $this->form_validation->set_message('nostock','You do not have stock to repackage for this product.');
            return false;
        }
    }

    public function excess($str){
        if($str > $this->input->post('rem_quantity')){
            $this->form_validation->set_message('excess','You can repackage quantity exceeding available stock.');
            return false;
        }
    }

    public function repackage(){

        $id = $this->uri->segment(4);

    
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {   

            //form validation
           
            $this->form_validation->set_rules('description', 'Description', 'required|xss_clean');
            $this->form_validation->set_rules('storage_fee', 'Storage', 'required|numeric|xss_clean');
            $this->form_validation->set_rules('penalty_fee', 'Penalty', 'required|numeric|xss_clean');
            $this->form_validation->set_rules('repackaging_fee', 'Repackage Fees', 'required|numeric|xss_clean|callback_setfees');
            $this->form_validation->set_rules('to_unit', 'Repackage To', 'required|xss_clean');
            $this->form_validation->set_rules('from_unit', 'From', 'required|callback_valid');
            $this->form_validation->set_rules('manufacture_id', 'Client', 'required|numeric|xss_clean');
            $this->form_validation->set_rules('rem_quantity', 'Quantity', 'required|numeric|xss_clean|callback_nostock');
            $this->form_validation->set_rules('quantity', 'Quantity', 'required|numeric|xss_clean|callback_excess');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            if ($this->form_validation->run())
            {

                $unique_id = substr(md5(uniqid()), 0, 5);

                $data_to_store_new_product = array(
                    'description'       => $this->input->post('description'),
                    'measurement_name'  => $this->input->post('to_unit'),
                    'unique_id'         => $unique_id,
                    'penalty_fee'       => $this->input->post('penalty_fee'),
                    'storage_fee'       => $this->input->post('storage_fee'),  
                    'repackaging_fee'   => $this->input->post('repackaging_fee'),        
                    'manufacture_id'    => $this->input->post('manufacture_id')
                );

               // CHECK IF THAT NEW PPRODUCT EXISTS

                if(!$this->products_model->check_product($this->input->post('description'), $this->input->post('to_unit'), $this->input->post('manufacture_id'))){

                    if($p_id = $this->products_model->store_product($data_to_store_new_product))
                        {
                            $data_to_store2 = [
                                'manufacture_id'        => $this->input->post('manufacture_id'),
                                'driver_name'           => 'repackage',
                                'driver_id'             => '',  
                                'type'                  => 1, // This stands for in. Never mind
                                'unique_id'             => $unique_id,     
                                'product_id'            => $p_id,
                                'truck_number_plate'    => 'repackaged',
                                'container_number'      => 'repackaged',
                                'quantity'              => $this->input->post('quantity') * 2,
                                'measurement_id'        => '',
                                'created_at'            => date('Y-m-d H:i:s'),
                                'user'                  => 1
                            ];

                        $this->stock_model->store_stock($data_to_store2);

                        $data_to_store_release_immediately = [
                            'manufacture_id' => $this->input->post('manufacture_id'),
                            'driver_name' => '',
                            'driver_id' => '',  
                            'type' => 0, // This stands for in. Never mind
                            'unique_id'             => $unique_id, 
                            'release_no' => '',      
                            'product_id' => $p_id,
                            'truck_number_plate' =>'',
                            'container_number' => '',
                            'quantity' => 0,
                            'measurement_id' => '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'user' => 1
                        ];

                            $this->stock_model->store_stock($data_to_store_release_immediately);

                            //$quan = $this->input->post('rem_quantity') - $this->input->post('quantity');

                            $data_to_store3 = [
                                'manufacture_id'        => $this->input->post('manufacture_id'),
                                'driver_name'           => 'repackage',
                                'driver_id'             => '',  
                                'type'                  => 0, // This stands for out. Never mind 
                                'unique_id'             => $unique_id,      
                                'product_id'            => $this->input->post('product_id'),
                                'truck_number_plate'    => 'repackaged',
                                'container_number'      => 'repackaged',
                                'quantity'              => $this->input->post('quantity'),
                                'measurement_id'        => '',
                                'created_at'            => date('Y-m-d H:i:s'),
                                'user'                  => 1
                            ];
                       
                        $this->release_model->store_release($data_to_store3);

                        $data_to_store_repackage = [
                                'product_id'    => $this->input->post('product_id'),
                                'manufacture_id'    => $this->input->post('manufacture_id'),
                                'quantity'          => $this->input->post('quantity'),
                                'fees'              => $this->input->post('repackaging_fee'),
                                'unique_id'         => $unique_id,
                                'from_unit'         => $this->input->post('from_unit'),
                                'to_unit'           => $this->input->post('to_unit'),
                                'created_at'        => date('Y-m-d h:i:s')

                            ];

                        $this->products_model->store_repackage($data_to_store_repackage);

                            $data['flash_message'] = TRUE; 

                            $data['undo'] = '. Click <a href="'.base_url("admin").'/repackage/undo/'.$unique_id.'">here</a> to undo';
                        } else {
                            $data['flash_message'] = FALSE; 
                        }

                } else {

                    $existing_product_id = $this->products_model->get_id($this->input->post('description'), $this->input->post('to_unit'), $this->input->post('manufacture_id'));
                    
                    //THE PRODUCT EXIST. NOW ADD STOCK OF EQUAL MEASURE

                    $data_to_store2 = [
                        'manufacture_id'        => $this->input->post('manufacture_id'),
                        'driver_name'           => 'repackage',
                        'driver_id'             => '',  
                        'type'                  => 1, // This stands for in. Never mind  
                        'unique_id'             => $unique_id,     
                        'product_id'            => $existing_product_id,
                        'truck_number_plate'    => 'repackaged',
                        'container_number'      => 'repackaged',
                        'quantity'              => $this->input->post('quantity') * 2,
                        'measurement_id'        => '',
                        'created_at'            => date('Y-m-d H:i:s'),
                        'user'                  => 1
                    ];

                if($this->input->post('to_unit') == $this->input->post('from_unit')){
                    $data_to_store2 = [
                        'manufacture_id'        => $this->input->post('manufacture_id'),
                        'driver_name'           => 'repackage',
                        'driver_id'             => '',  
                        'type'                  => 1, // This stands for in. Never mind 
                        'unique_id'             => $unique_id,      
                        'product_id'            => $existing_product_id,
                        'truck_number_plate'    => 'repackaged',
                        'container_number'      => 'repackaged',
                        'quantity'              => $this->input->post('quantity'),
                        'measurement_id'        => '',
                        'created_at'            => date('Y-m-d H:i:s'),
                        'user'                  => 1
                    ];
                }

                $this->stock_model->store_stock($data_to_store2);

                    $data_to_store_release_immediately = [
                        'manufacture_id' => $this->input->post('manufacture_id'),
                        'driver_name' => '',
                        'driver_id' => '',  
                        'type' => 0, // This stands for in. Never mind 
                        'unique_id'             => $unique_id,
                        'release_no' => '',      
                        'product_id' => $existing_product_id,
                        'truck_number_plate' =>'',
                        'container_number' => '',
                        'quantity' => 0,
                        'measurement_id' => '',
                        'created_at' => date('Y-m-d H:i:s'),
                        'user' => 1
                    ];

                        $this->stock_model->store_stock($data_to_store_release_immediately);              

                    //TREATED AS RELEASED STOCK AFTER REPACKAGING
                    $data_to_store3 = [
                        'manufacture_id'        => $this->input->post('manufacture_id'),
                        'driver_name'           => 'repackage',
                        'driver_id'             => '',  
                        'type'                  => 0, // This stands for OUT. Never mind
                        'unique_id'             => $unique_id,       
                        'product_id'            => $this->input->post('product_id'),
                        'truck_number_plate'    => 'repackaged',
                        'container_number'      => 'repackaged',
                        'quantity'              => $this->input->post('quantity'),
                        'measurement_id'        => '',
                        'created_at'            => date('Y-m-d H:i:s'),
                        'user'                  => 1
                    ];

                   
                    $this->release_model->store_release($data_to_store3);

                    $data_to_store_repackage = [
                                'product_id'        => $this->input->post('product_id'),
                                'manufacture_id'    => $this->input->post('manufacture_id'),
                                'quantity'          => $this->input->post('quantity'),
                                'fees'              => $this->input->post('repackaging_fee'),
                                'unique_id'         => $unique_id,
                                'from_unit'         => $this->input->post('from_unit'),
                                'to_unit'           => $this->input->post('to_unit'),
                                'created_at'        => date('Y-m-d h:i:s')

                            ];

                        $this->products_model->store_repackage($data_to_store_repackage);

                     $data['flash_message'] = TRUE;

                     $data['undo'] = '. Click <a href="'.base_url("admin").'/repackage/undo/'.$unique_id.'">here</a> to undo'; 

                }

                
            }
            $id = $this->input->post('product_id');
        } 
        //product data 
        $data['product'] = $this->products_model->get_product_by_id($id);
        //fetch manufactures data to populate the select field
        $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        $data['rem_stock'] = $this->release_model->all_remaining_stock_by_id($id);

        $data['measurements'] = $this->measurements_model->get_measurements();
        //load the view
        $data['main_content'] = 'admin/products/repackage';
        $this->load->view('includes/template', $data);

    }

    public function undo_repackage()
    {
        $unique_id = $this->uri->segment(4);

        $this->db->where('unique_id', $unique_id);
        $this->db->delete('products');

        $this->db->where('unique_id', $unique_id);
        $this->db->delete('stock');

        $this->db->where('unique_id', $unique_id);
        $this->db->delete('repackage');

        redirect(base_url("admin").'/products');
    }

}