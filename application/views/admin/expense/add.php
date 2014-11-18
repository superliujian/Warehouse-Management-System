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
          Add new expense
        </h2>
      </div>
 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> expense paid successfully.';
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

      //echo 'Cash At Hand: Ksh. '.$cash;
      
      echo form_open('admin/expense/add', $attributes);
      ?>
        <fieldset>
          <div class="control-group">
            <label for="manufacture_id" class="control-label">Description</label>
            <div class="controls">
                <?php echo form_input(array('name'=>'description','value'=>set_value('description'))); ?>
                 <span class="help-inline">e.g Rent, Electricity, etc</span>
            </div>
          </div>

          <div class="control-group">
            <label for="manufacture_id" class="control-label">Memo</label>
            <div class="controls">
                <?php echo form_textarea(array('name'=>'memo','value'=>set_value('memo'))); ?>
                 <span class="help-inline"></span>
            </div>
          </div>

          <input type="hidden" id="id" name="id" value="">

          <div class="control-group">
            <label for="inputError" class="control-label">Quantity</label>
            <div class="controls">
              <?php echo form_input(array('name'=>'quantity','value'=>set_value('quantity'))); ?>
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Unit Cost</label>
            <div class="controls">
              <?php echo form_input(array('name'=>'each','value'=>set_value('each'))); ?>
               <span class="help-inline">(Cash At Hand: Ksh. <b><?php echo $cash; ?><b>)</span>
            </div>
          </div>
                   
          

          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save </button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     