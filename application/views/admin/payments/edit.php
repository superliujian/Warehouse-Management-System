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
          <a href="#">Update</a>
        </li>
      </ul>
      
      <div class="page-header">
        <h2>
          Updating Release Order
        </h2>
      </div>

 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> stock updated with success.';
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
        
      //form validation
      echo validation_errors();

      echo form_open('admin/payments/update/'.$this->uri->segment(4).'', $attributes);
      ?>
        <fieldset>

          <div class="control-group">
            <label for="manufacture_id" class="control-label">Client</label>
            <div class="controls">
             <?php echo form_input(array('value'=>$products[0]['manufacture_name'],'readonly'=>'readonly')); ?>
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Amount</label>
            <div class="controls">
              <input type="text" id="" name="amount" value="<?php echo $products[0]['amount']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Mode of payment</label>
            <div class="controls">
              <select name="mode">
                <option value="<?php echo $products[0]['mode']; ?>"><?php echo $products[0]['mode']; ?></option>
                <option value="cash">Cash</option>
                <option value="cheque">Cheque</option>
              </select>
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Cheque Name</label>
            <div class="controls">
              <input type="text" id="" name="cheque_name" value="<?php echo $products[0]['cheque_name']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Cheque Number</label>
            <div class="controls">
              <input type="text" id="" name="cheque_number" value="<?php echo $products[0]['cheque_number']; ?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
                 
        
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     