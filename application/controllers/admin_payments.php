<?php

/**
 * Admin_payments class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */


class Admin_payments extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */

    public function __construct()
    {
        parent::__construct();
        $this->load->model('payments_model');
        
        $this->load->model('manufacturers_model');
        

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
        $config['base_url'] = base_url().'admin/payments';
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
            $data['manufactures'] = $this->payments_model->get_payments();

            $data['count_products']= $this->payments_model->count_payments($manufacture_id, $search_string, $order);
            $config['total_rows'] = $data['count_products'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['products'] = $this->payments_model->get_payments($manufacture_id, $search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->payments_model->get_payments($manufacture_id, $search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['products'] = $this->payments_model->get_payments($manufacture_id, '', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['products'] = $this->payments_model->get_payments($manufacture_id, '', '', $order_type, $config['per_page'],$limit_end);        
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
            $data['count_payments']= $this->payments_model->count_payments();
            $data['products'] = $this->payments_model->get_payments('', $search_string, '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_payments'];

        }//!isset($manufacture_id) && !isset($search_string) && !isset($order)

        //initializate the panination helper 
        $this->pagination->initialize($config);   

        //load the view
        $data['main_content'] = 'admin/payments/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function cheque($str){
        if($str == 'cheque'){
            if(!$this->input->post('cheque_name') || !$this->input->post('cheque_name')){
                $this->form_validation->set_message('cheque', 'Please enter the cheque name and the cheque number');
                return false;
            } else return true;
        } elseif ($str == 'cash') {
            if($this->input->post('cheque_name') || $this->input->post('cheque_name')){
                $this->form_validation->set_message('cheque', 'You cannot enter the cheque name and the cheque number for cash payments');
                return false;
            }
        }

    }

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
           
            $this->form_validation->set_rules('id', 'Client', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('mode', 'Mode of Payment', 'trim|required|xss_clean|callback_cheque');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('cheque_name', 'Cheque Name', 'trim|xss_clean');
            $this->form_validation->set_rules('cheque_number', 'Cheque Number', 'trim|xss_clean');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(

                    'mode' => $this->input->post('mode'),
                    'client_id' => $this->input->post('id'),
                    'amount' => $this->input->post('amount'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'cheque_name' => $this->input->post('cheque_name'),
                    'cheque_number' => $this->input->post('cheque_number')

                );
                //if the insert has returned true then we show the flash message
                if($this->payments_model->store_payments($data_to_store)){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }

        }
        //fetch manufactures data to populate the select field
        $data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        //fetch measurements data to populate the select field
        //$data['measurements'] = $this->measurements_model->get_measurements();

        //fetch products data to populate the select field
        //$data['products'] = $this->payment_model->get_products();


        //load the view
        $data['main_content'] = 'admin/payments/add';
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
            
          

            $this->form_validation->set_rules('mode', 'Mode of Payment', 'trim|required|xss_clean|callback_cheque');
            $this->form_validation->set_rules('amount', 'Amount', 'trim|required|numeric|xss_clean');
            $this->form_validation->set_rules('cheque_name', 'Cheque Name', 'trim|xss_clean');
            $this->form_validation->set_rules('cheque_number', 'Cheque Number', 'trim|xss_clean');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(

                    'mode' => $this->input->post('mode'),
                    //'client_id' => $this->input->post('client_id'),
                    'amount' => $this->input->post('amount'),
                    'cheque_name' => $this->input->post('cheque_name'),
                    'cheque_number' => $this->input->post('cheque_number')
                    
                );

                //if the insert has returned true then we show the flash message
                if($this->payments_model->update_payments($id, $data_to_store) == TRUE){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }
                //redirect('admin/products');

            }//validation run

        }

        //if we are updating, and the data did not pass trough the validation
        //the code below wel reload the current data

        //release data 
        $data['products'] = $this->payments_model->get_payments_by_id($id);
        //fetch manufactures data to populate the select field
        //$data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        //load the view
        $data['main_content'] = 'admin/payments/edit';
        $this->load->view('includes/template', $data);            

    }//update

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        //product id 
        //$id = $this->uri->segment(4);
        $id = $this->input->post('delete_id');
        $this->payments_model->delete_payment($id);
        //redirect('admin/products
        //$this->index();

        echo 0;
    }

    public function js_clients(){


        $data = $this->payments_model->autocomplete($this->input->post('keyword'));

        if(!count($data)){ return false; die; }

            foreach ($data as $rs) {
                // put in bold the written text
                $client_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['name']);
                //$client_name = $rs['name'];
                // add new option
                $test = array('list' => '<li onclick="set_item(\''.$rs['name'].'\')">'.$client_name.'</li>',
                    'id' => $rs['id']
                    );

                echo json_encode($test);
            } 
              
    }

    public function js_clients_pro(){

        $id = $this->input->post('id');

        $data = $this->payments_model->autocomplete_pro($id);

        //if(!count($data)){ return false; die; }
        $test = array();

            foreach ($data as $rs) {
            $test[] = $rs;
            } 
                
            echo json_encode(array('data'=>$test));

    }

   
}