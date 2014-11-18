<?php

/**
 * Admin_expense class file.
 *
 * @author Richard Keep <r.kipsang@gmail.com>
 * @copyright Copyright &copy; 2014 
 */


class Admin_expense extends CI_Controller {
 
    /**
    * Responsable for auto load the model
    * @return void
    */


    public function __construct()
    {
        parent::__construct();
        $this->load->model('expense_model');

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
        $config['base_url'] = base_url().'admin/expense';
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
            $data['expenses'] = $this->expense_model->get_expenses();

            $data['count_expenses']= $this->expense_model->count_expenses($search_string, $order);
            $config['total_rows'] = $data['count_expenses'];

            //fetch sql data into arrays
            if($search_string){
                if($order){
                    $data['expenses'] = $this->expense_model->get_expenses('',$search_string, $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['expenses'] = $this->expense_model->get_expenses('',$search_string, '', $order_type, $config['per_page'],$limit_end);           
                }
            }else{
                if($order){
                    $data['expenses'] = $this->expense_model->get_expenses('','', $order, $order_type, $config['per_page'],$limit_end);        
                }else{
                    $data['expenses'] = $this->expense_model->get_expenses('','', '', $order_type, $config['per_page'],$limit_end);        
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
            $data['count_expenses']= $this->expense_model->count_expenses();
            $data['expenses'] = $this->expense_model->get_expenses('', $search_string, '', $order_type, $config['per_page'],$limit_end);        
            $config['total_rows'] = $data['count_expenses'];

        }//!isset($manufacture_id) && !isset($search_string) && !isset($order)

        //initializate the panination helper 
        $this->pagination->initialize($config);
        //$today = true;
        $today = $this->uri->segment(4);
        if(isset($today)){
             $data['expenses'] = $this->expense_model->get_expenses($today, $search_string, '', $order_type, $config['per_page'],$limit_end);
        }
        //$today = true;

        $data['today_expenses']= $this->expense_model->totalAmountSpent($today = true);

        //load the view
        $data['main_content'] = 'admin/expense/list';
        $this->load->view('includes/template', $data);  

    }//index

    public function username_check($str)
    {
        if ($str == 'test')
        {
            $this->form_validation->set_message('username_check', 'The %s field can not be the word "test"');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    public function excess($quantity){
        $user_input = $quantity * $this->input->post('each');

        $cashAtHand = $this->expense_model->cashAtHand();

        //$cashAtHand = 1000;

        if($user_input > $cashAtHand){

            $this->form_validation->set_message('excess', 'You have insufficient amount to spent Ksh. '.$user_input.'. You have Ksh. '.$cashAtHand.' in the account.');
            return false;
        } else {
            return true;
        }
    }

    public function add()
    {
        //if save button was clicked, get the data sent via post
        if ($this->input->server('REQUEST_METHOD') === 'POST')
        {

            //form validation
           
            $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[4]|xss_clean');
            $this->form_validation->set_rules('memo', 'Memo', 'trim|min_length[4]|xss_clean');
            $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|numeric|xss_clean|callback_excess');
            $this->form_validation->set_rules('each', 'Unit Cost', 'trim|required|numeric|xss_clean');
            
            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');

            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(

                    'description' => $this->input->post('description'),
                    'memo' => $this->input->post('memo'),
                    'quantity' => $this->input->post('quantity'),
                    'each' => $this->input->post('each'),
                    'created_at' => date('Y-m-d H:i:s')

                );
                //if the insert has returned true then we show the flash message
                if($this->expense_model->store_expenses($data_to_store)){
                    $data['flash_message'] = TRUE; 
                }else{
                    $data['flash_message'] = FALSE; 
                }

            }

        }

        $data['cash'] =  $this->expense_model->cashAtHand();

        //load the view
        $data['main_content'] = 'admin/expense/add';
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
            
            $this->form_validation->set_rules('description', 'Description', 'trim|required|min_length[4]|xss_clean');
            $this->form_validation->set_rules('memo', 'Memo', 'trim|min_length[4]|xss_clean');
            $this->form_validation->set_rules('quantity', 'Quantity', 'trim|required|is_natural_no_zero|xss_clean');
            $this->form_validation->set_rules('each', 'Unit Cost', 'trim|required|numeric|xss_clean');

            $this->form_validation->set_error_delimiters('<div class="alert alert-error"><a class="close" data-dismiss="alert">×</a><strong>', '</strong></div>');
            //if the form has passed through the validation
            if ($this->form_validation->run())
            {
                $data_to_store = array(

                    'description' => $this->input->post('description'),
                    'memo' => $this->input->post('memo'),
                    'quantity' => $this->input->post('quantity'),
                    'each' => $this->input->post('each'),
                    //'created_at' => date('Y-m-d H:i:s')
                    
                );

                //if the insert has returned true then we show the flash message
                if($this->expense_model->update_expense($id, $data_to_store) == TRUE){
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
        $data['expenses'] = $this->expense_model->get_expense_by_id($id);
        //fetch manufactures data to populate the select field
        //$data['manufactures'] = $this->manufacturers_model->get_manufacturers();

        //load the view
        $data['main_content'] = 'admin/expense/edit';
        $this->load->view('includes/template', $data);            

    }//update

    /**
    * Delete product by his id
    * @return void
    */
    public function delete()
    {
        
        $id = $this->input->post('delete_id');

        $this->expense_model->delete_expense($id);
        //redirect('admin/products
       return true;
    }

    public function js(){

        $data = $this->payments_model->autocomplete($this->input->post('keyword'));

        if(!count($data)){ return false; die; }

            foreach ($data as $rs) {
                // put in bold the written text
                $client_name = str_replace($_POST['keyword'], '<b>'.$_POST['keyword'].'</b>', $rs['name']);
                // add new option
                $test = array('list' => '<li onclick="set_item(\''.$rs['name'].'\')">'.$client_name.'</li>',
                    'id' => $rs['id']
                    );

                echo json_encode($test);
            }     
    }

   
}