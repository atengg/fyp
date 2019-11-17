<div class="container">
<div class="row">
    <div>
        <h2>Settings</h2>
        <h5>Hello <span><?php echo $first_name; ?></span>.</h5>
        <hr>
        <?php
        $fattr = array('class' => 'form-signin');
        echo form_open(site_url().'main/settings/', $fattr);

        function tz_list() {
            $zones_array = array();
            $timestamp = time();
            foreach(timezone_identifiers_list() as $key => $zone) {
              date_default_timezone_set($zone);
              $zones_array[$key]['zone'] = $zone;
            }
            return $zones_array;
        }
        ?>

        <?php echo '<input type="hidden" name="id" value="'.$id.'">'; ?>
        <div class="form-group">
        <span>Masa</span>
          <?php echo form_input(array('name'=>'start_time', 'id'=> 'start_time', 'placeholder'=>'Start', 'class'=>'form-control', 'value' => set_value('start_time', $start))); ?>
          <?php echo form_error('start_time');?>
        </div>
        <div class="c">
        <span>Tarikh</span>
          <?php echo form_input(array('name'=>'date', 'id'=> 'date', 'placeholder'=>'Tarikh', 'class'=>'form-control', 'value'=> set_value('date', $date))); ?>
          <?php echo form_error('date');?>
        </div><br></br>
        <div class="form-group">
           <?php echo form_label("Kesalahan",'',$attributes=array());?>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Meletak di tempat larangan dikhaskan kepada... ",set_checkbox("offences[]","Meletak di tempat larangan dikhaskan kepada..."));?>Meletak di tempat larangan dikhaskan kepada...
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Meletak di luar petak/petak kuning",set_checkbox("offences[]","Meletak di luar petak/petak kuning"));?>Meletak di luar petak/petak kuning
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Menghalang laluan",set_checkbox("offences[]","Menghalang laluan"));?>Menghalang laluan
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Tiada lesen memandu/tamat tempoh",set_checkbox("offences[]","Tiada lesen memandu/tamat tempoh"));?>Tiada lesen memandu/tamat tempoh
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Lesen (L) membawa pembonceng",set_checkbox("offences[]","Lesen (L) membawa pembonceng"));?>Lesen (L) membawa pembonceng
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Tiada cukai jalan yang sah/tamat tempoh",set_checkbox("offences[]","Tiada cukai jalan yang sah/tamat tempoh"));?>Tiada cukai jalan yang sah/tamat tempoh
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Melanggar jalan sehala/dilarang masuk",set_checkbox("offences[]","Melanggar jalan sehala/dilarang masuk"));?>Melanggar jalan sehala/dilarang masuk
              </label>
           </div>

            <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Tidak memakai topi keledar",set_checkbox("offences[]","Tidak memakai topi keledar"));?>Tidak memakai topi keledar
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Tiada pelekat UiTM tahun...",set_checkbox("offences[]","Tiada pelekat UiTM tahun..."));?>Tiada pelekat UiTM tahun...
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","Meletak di koridor/pejalan kaki",set_checkbox("offences[]","Meletak di koridor/pejalan kaki"));?>Meletak di koridor/pejalan kaki
              </label>
           </div>

           <div class="checkbox">
              <label>
                  <?php echo form_checkbox("offences[]","kenderaan dikunci",set_checkbox("offences[]","kenderaan dikunci"));?>kenderaan dikunci
              </label>
           </div>

           
        </div>
        <div class="col-md-4 col-lg-4">
        
          <?php echo form_submit(array('value'=>'Save', 'name'=>'submit', 'class'=>'btn btn-primary btn-block', 'style' => 'margin-right:10px')); ?>
          <?php echo form_close(); ?>
          </div>
          <br><br></br></br>
    <div class="col-md-4 col-lg-4">
        <h2>Send Notification</h2>
        <br>
        <hr>
        <?php
        function generateRandomString() {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = sha1($characters[rand(0, $charactersLength - 1)]);
            return $randomString;
        }
        
        echo '<div class="alert alert-info" role="alert">Send notification</div>';
        echo '<a href="mailto:?subject=Share " title="Send to email"><i class="fa fa-envelope-o" aria-hidden="true"></i></a> ';
        
        echo '<div style="margin-top:100px;">';
        echo '</div>';
        ?>
        <script>
        
        </script>
    </div>
</div>
</div>
