    <div class="container top">

      <ul class="breadcrumb">
        <li>
          <a href="<?php echo site_url("admin"); ?>">
            <?php echo ucfirst($this->uri->segment(1));?>
          </a> 
          <span class="divider">/</span>
        </li>
        <li class="active">
          <?php echo ucfirst($this->uri->segment(2));?>
        </li>
      </ul>

      <div class="page-header users-header">
        <h2>
          <?php echo 'Expenses History';?>  
          <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/add" class="btn btn-success">Add a new expense</a>
          
        </h2>
      </div>
      
      <div class="row">
        <div class="span12 columns">
          <div class="well">
            <a  href="<?php echo site_url("admin").'/'.$this->uri->segment(2); ?>/list/today" id="showToday" class="btn btn-info">Click here to show today expenses only</a>
            <?php $today_expenses = isset($today_expenses)? $today_expenses : 0 ; ?>
            Total expenses today:<span id="total" style="color:red;"> Ksh. <?php echo $today_expenses; ?></span>
          </div>
          <div class="well">
           
            <?php
           
              $attributes = array('class' => 'form-inline reset-margin', 'id' => 'myform');

              echo form_open('admin/expense', $attributes);
       
              echo form_label('Search:', 'search_string');
              echo form_input('search_string', $search_string_selected, 'style="width: 170px; height: 26px;"');

              echo ' ';

              $data_submit = array('name' => 'mysubmit', 'class' => 'btn btn-primary', 'value' => 'Go');

              echo form_submit($data_submit);

              echo form_close();
            ?>


          </div>
          

          <table class="table table-striped table-bordered table-condensed">
            <thead>
              <tr>
                <th class="header">#</th>
                <th class="yellow header headerSortDown">Description</th>
                <th class="yellow header headerSortDown">Memo</th>
                <th class="yellow header headerSortDown">Quantity</th>
                <th class="yellow header headerSortDown">Unit Cost</th>
                <th class="yellow header headerSortDown">Total Cost</th>
                <th class="red header">Date</th>
                <th class="red header">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
              //$x = 1;
              foreach($expenses as $row)
              {
                $date = new dateTime($row['created_at']);
                echo '<tr>';
                echo '<td>'.$row['id'].'</td>';
                echo '<td>'.$row['description'].'</td>';
                echo '<td>'.$row['memo'].'</td>';
                echo '<td>'.$row['quantity'].'</td>';
                echo '<td>'.$row['each'].'</td>';
                echo '<td>'.$row['each'] * $row['quantity'].'</td>';
                echo '<td>'.$date->format('d/m/Y h:i a').'</td>';

                echo '<td class="crud-actions">
                  <a href="'.site_url("admin").'/expense/update/'.$row['id'].'" class="btn btn-info">view & edit</a>  
                  <a id="delete_id" onclick="confirmDeleteExpense('.$row['id'].')" class="btn btn-danger">delete</a>
                </td>';
                echo '</tr>';
              }
              ?>      
            </tbody>
          </table>

          <?php echo '<div class="pagination">'.$this->pagination->create_links().'</div>'; ?>

      </div>
    </div>