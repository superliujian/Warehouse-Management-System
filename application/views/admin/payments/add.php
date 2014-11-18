    <div class="container top">
      
      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li>
          <a href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>">
            <?php echo ucfirst($this->uri->segment(2));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          <a href="#">New</a>
        </li>
      </ul>

      <div class="page-header">
        <h2>
          Receive Payments
        </h2>
      </div>
 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> payments received successfully.';
          echo '</div>';       
        }else{
          echo '<div class="alert alert-error">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Oh snap!</strong> change a few things up and try submitting again.';
          echo '</div>';          
        }
      }
      ?>
      
      <?php
      //form data
      $attributes = array('class' => 'form-horizontal', 'id' => '');
      $options_manufacture = array('' => "Select");
      foreach ($manufactures as $row)
      {
        $options_manufacture[$row['id']] = $row['name'];
      }

      

      //form validation
      echo validation_errors();
      
      echo form_open('admin/payments/add', $attributes);
      ?>
        <fieldset>

          <div class="control-group">
            <label for="manufacture_id" class="control-label">Client</label>
            <div class="controls">
              <?php //echo form_dropdown('client_id', $options_manufacture, set_value('client_id'), 'class="span2"'); ?>
               <input type="text" id="manufacture_id" name="manufacture_id" onkeyup="auto()" autocomplete="off">
                
                  <ul id="manufacture_list_id"></ul>
            </div>
          </div>

          <input type="hidden" id="id" name="id" value="">

          <div class="control-group">
            <label for="inputError" class="control-label">Amount</label>
            <div class="controls">
              <input type="text" id="" name="amount" value="<?php echo set_value('amount'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Mode of payment</label>
            <div class="controls">
              <select name="mode">
                <option value="">Select</option>
                <option value="cash">Cash</option>
                <option value="cheque">Cheque</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Cheque Name</label>
            <div class="controls">
              <input type="text" id="" name="cheque_name" value="<?php echo set_value('cheque_name'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Cheque Number</label>
            <div class="controls">
              <input type="text" id="" name="cheque_number" value="<?php echo set_value('cheque_number'); ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
                   
          

          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save </button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     