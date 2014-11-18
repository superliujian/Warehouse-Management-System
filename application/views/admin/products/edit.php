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
      
      <div class="page-header users-header">
        <h2>
          Updating Product
          <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2).'/repackage/'.$this->uri->segment(4); ?>" class="btn btn-success">Repackage this product</a>
       
        </h2>
      </div>

 
      <?php
      //flash messages
      if(isset($flash_message)){
        if($flash_message == TRUE)
        {
          echo '<div class="alert alert-success">';
            echo '<a class="close" data-dismiss="alert">×</a>';
            echo '<strong>Well done!</strong> product updated with success.';
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

      //$options_measurement = array($product[0]['measurement_name'] => $product[0]['measurement_name']);
      foreach ($measurements as $measurement)
      {
        $options_measurement[$measurement['measurement_name']] = $measurement['measurement_name'];
      }

      //form validation
      echo validation_errors();

      echo form_open('admin/products/update/'.$this->uri->segment(4).'', $attributes);
      ?>
        <fieldset>

          <div class="control-group">
            <label for="manufacture_id" class="control-label">Client</label>
            <div class="controls">

              <input type="text" id="manufacture_id" readonly="readonly" onkeyup="auto()" value="<?php echo $product[0]['manufacture_name']; ?>" autocomplete="off">
                
                  <ul id="manufacture_list_id"></ul>
              
              <?php //echo form_dropdown('manufacture_id', $options_manufacture, $product[0]['manufacture_id'], 'class="span2"'); ?>

            </div>
          </div>

          <input type="hidden" id="id" name="manufacture_id" value="">

          <div class="control-group">
            <label for="inputError" class="control-label">Description</label>
            <div class="controls">
              <input type="text" id="" name="description" value="<?php echo $product[0]['description']; ?>" >
              <!--<span class="help-inline">Woohoo!</span>-->
            </div>
          </div>

          <?php  $selected = $product[0]['measurement_name']; ?>

          <div class="control-group">
            <label for="inputError" class="control-label">Unit of measurement: </label>
            <div class="controls">
              <?php echo form_dropdown('measurement_name', $options_measurement, $selected ); ?>
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>
                    
          <div class="control-group">
            <label for="inputError" class="control-label">Storage Fee</label>
            <div class="controls">
              <input type="text" id="" name="storage_fee" value="<?php echo $product[0]['storage_fee'];?>">
              <!--<span class="help-inline">Cost Price</span>-->
            </div>
          </div>
          <div class="control-group">
            <label for="inputError" class="control-label">Penalty Fee</label>
            <div class="controls">
              <input type="text" name="penalty_fee" value="<?php echo $product[0]['penalty_fee']; ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>

          <div class="control-group">
            <label for="inputError" class="control-label">Repackaging Fee</label>
            <div class="controls">
              <input type="text" name="repackaging_fee" value="<?php echo $product[0]['repackaging_fee']; ?>">
              <!--<span class="help-inline">OOps</span>-->
            </div>
          </div>

         
          <div class="form-actions">
            <button class="btn btn-primary" type="submit">Save changes</button>
            <button class="btn" type="reset">Cancel</button>
          </div>
        </fieldset>

      <?php echo form_close(); ?>

    </div>
     