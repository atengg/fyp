<div class="col-lg-4 col-lg-offset-4">
      <?php
      $getqrFirstName = trim($this->input->post('firstname'));
      $getqrLastName = trim($this->input->post('lastname'));
      $userDetails = trim($this->input->post('user-details'));
      $getqr = trim($this->input->post('qr'));

      if($userDetails === 1){
        $userDetailsStyle = 'style="display:block !important"';
        $userWithoutDetails = 'style="display:none !important"';
        $userDetailsCheck = 'checked';
        $userWithoutDetailsCheck = '';
      }else if($userDetails === 0){
        $userDetailsStyle = 'style="display:none !important"';
        $userWithoutDetails = 'style="display:block !important"';
        $userDetailsCheck = '';
        $userWithoutDetailsCheck = 'checked';
      }else{
        $userDetailsStyle = '';
        $userWithoutDetails = '';
        $userDetailsCheck = 'checked';
        $userWithoutDetailsCheck = '';
      }

      if(empty($getqr) && empty($getqrLastName)){
      ?>
        <h2>Generate QR Code</h2>
        <hr>
        

        <div class="with-user">
          <h5>Please enter the required information below, to generate QR.</h5>
          <?php
          $fattr = array('class' => 'form-signin');
          echo form_open(site_url().'main/generateqr/', $fattr);
          ?>
          <input type="hidden" name="user-details" value="1">
          <div class="form-group">
            <?php echo form_input(array('name'=>'firstname', 'id'=> 'firstname', 'placeholder'=>'First Name', 'class'=>'form-control', 'value' => set_value('firstname'))); ?>
            <?php echo form_error('firstname');?>
          </div>
          <div class="form-group">
            <?php echo form_input(array('name'=>'lastname', 'id'=> 'lastname', 'placeholder'=>'Last Name', 'class'=>'form-control', 'value'=> set_value('lastname'))); ?>
            <?php echo form_error('lastname');?>
          </div>
          <div class="form-group">
            <?php echo form_input(array('name'=>'s_mcard', 'id'=> 's_mcard', 'placeholder'=>'Matric Number', 'class'=>'form-control', 'value'=> set_value('s_mcard'))); ?>
            <?php echo form_error('s_mcard');?>
          </div>
          <div class="form-group">
            <?php echo form_input(array('name'=>'s_ic', 'id'=> 's_ic', 'placeholder'=>'IC', 'class'=>'form-control', 'value'=> set_value('s_ic'))); ?>
            <?php echo form_error('s_ic');?>
          </div>
          <div class="form-group">
          <select name="s_type" id="s_type" class="form-control">
            <?php
            echo '
              <option value="CAR" selected>CAR</option>
              <option value="MOTORCYCLE">MOTORCYCLE</option>
              ';            
            ?>
          </select> 
          </div>  
          <div class="form-group">
            <?php echo form_input(array('name'=>'s_plate', 'id'=> 's_plate', 'placeholder'=>'Plate', 'class'=>'form-control', 'value'=> set_value('s_plate'))); ?>
            <?php echo form_error('s_plate');?>
          </div>
          <div class="form-group">
          <select name="s_program" id="s_program" class="form-control">
            <?php
            echo '
            <option value="CS251" selected>CS251</option>
            <option value="AS201">AS201</option>
            <option value="AS203">AS203</option>
            <option value="AS222">AS222</option>
            <option value="AS229">AS229</option>
            <option value="AS243">AS243</option>
            <option value="AS244">AS244</option>
            <option value="AT220">AT220</option>
            <option value="BA233">BA233</option>
            <option value="BA234">BA234</option>
            <option value="CS230">CS230</option>
            <option value="CS240">CS240</option>
            <option value="CS241">CS241</option>
            <option value="CS246">CS246</option>
            <option value="CS251">CS251</option>
            <option value="EC220">EC220</option>
            <option value="IM245">IM245</option>
              ';            
            ?>
          </select> 
          </div>      
          <div class="form-group">
            <?php echo form_input(array('type'=>'hidden','name'=>'role', 'id'=> 'role', 'class'=>'form-control', 'value'=> set_value('4'))); ?>
            <?php echo form_error('role');?>
          </div>  
          <div class="form-group">
            <?php echo form_input(array('name'=>'email', 'id'=> 'email', 'placeholder'=>'Email', 'class'=>'form-control', 'value'=> set_value('email'))); ?>
            <?php echo form_error('email');?>
          </div>          
          <div class="form-group">
            <?php echo form_password(array('name'=>'password', 'id'=> 'password', 'placeholder'=>'Password', 'class'=>'form-control', 'value' => set_value('password'))); ?>
            <?php echo form_error('password') ?>
          </div>
          <div class="form-group">
            <?php echo form_password(array('name'=>'passconf', 'id'=> 'passconf', 'placeholder'=>'Confirm Password', 'class'=>'form-control', 'value'=> set_value('passconf'))); ?>
            <?php echo form_error('passconf') ?>
          </div>
          <div class="form-group">
          <?php echo form_submit(array('value'=>'Save', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
          </div>
          <?php echo form_close();?>
        </div>

        <!-- <div class="without-user">
          <?php
          $frattr = array('class' => 'form-signin');
          echo form_open(site_url().'main/generateqr/', $frattr);
          ?>
          <div class="form-group">
            <?php echo form_input(array('name'=>'qr', 'id'=>'qr', 'placeholder'=>'Your Employee Full Name', 'class'=>'form-control', 'value' => set_value('qr'))); ?>
            <?php echo form_error('name');?>
          </div>
          <input type="hidden" name="user-details" value="0">
          <div class="form-group">
            <?php echo form_submit(array('value'=>'Get it', 'class'=>'btn btn-lg btn-primary btn-block')); ?>
          </div>
          <?php echo form_close(); ?>
        </div> -->

        <?php
        }
        ?>

        <div class="with-user" <?php echo $userDetailsStyle; ?>>

        <?php
            $qr = "{'name':'".$getqrFirstName." ".$getqrLastName."'}";
            if(!empty($getqrFirstName) && !empty($getqrLastName) && $userDetails == 1){
              ?>
              <h2>Generate QR Code</h2>
              <hr>
              <h5><b>Detail Students:</b></h5>
              <p>Name : <?php echo $getqrFirstName." ".$getqrLastName;?> </p>
              <p>Email : <?php echo $this->input->post('email');?> </p>
              <p>Matric Card : <?php echo $this->input->post('s_mcard');?> </p>
              <p>Plate : <?php echo $this->input->post('s_plate');?> </p>
              <p>Program : <?php echo $this->input->post('s_program');?> </p>
              <p>Pass : ****** </p>
              <br>
              <br>
              <?php
                echo '<div>';
                echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.$qr.'&choe=UTF-8" style="margin: 0 auto;display: block;">';
                echo '<br><a href="https://chart.googleapis.com/chart?chs=400x400&cht=qr&chl='.$qr.'&choe=UTF-8" target="_blank"><button class="btn btn-primary" style="margin: 0 auto;display: block;">Save</button></a>';
                echo '</div>';
            }
        ?>
      </div>

      
</div>
