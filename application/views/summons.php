<div class="container">
<div class="row">
    <div class="col-md-4 col-lg-4 col-lg-offset-2">
        <h2>Summons</h2>
        <hr>
        <?php
        $fattr = array('class' => 'form-signin');
        echo form_open(site_url().'main/summons/', $fattr);

        ?>

        <?php echo '<input type="hidden" name="id" value="'.$id.'">'; ?>
        <div class="form-group">
        <span>Time</span>
          <?php echo form_input(array('name'=>'time', 'id'=> 'time', 'placeholder'=>'Masa', 'class'=>'form-control', 'value' => set_value('time', $time))); ?>
          <?php echo form_error('time');?>
        </div>
        <div class="form-group">
        <span>Date</span>
          <?php echo form_input(array('name'=>'date', 'id'=> 'date', 'placeholder'=>'Tarikh', 'class'=>'form-control', 'value'=> set_value('date', $date))); ?>
          <?php echo form_error('date');?>
        </div>
        <div class="form-group">
        <span>Offence</span>
          <?php echo form_input(array('name'=>'offence', 'id'=> 'offence', 'placeholder'=>'Kesalahan', 'class'=>'form-control', 'value'=> set_value('offence', $offence))); ?>
          <?php echo form_error('offence');?>
        </div>
        
          <?php echo form_submit(array('value'=>'Save', 'name'=>'submit', 'class'=>'btn btn-primary btn-block', 'style' => 'margin-right:10px')); ?>
          <?php echo form_close(); ?>
          
    </div>
    <div class="col-md-4 col-lg-4">
        <h2>Share The Key</h2>
        <br>
        <hr>
        <?php
        function generateRandomString() {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = sha1($characters[rand(0, $charactersLength - 1)]);
            return $randomString;
        }

        // the QR
        $qr = "{'url':'".base_url()."', 'key':'".$key."'}";

        echo '<div class="alert alert-info" role="alert">Share the KEY via:</div>';
        // echo '<a href="whatsapp://send?text='.$key.'" data-action="share/whatsapp/share"><i class="fa fa-whatsapp" aria-hidden="true"></i></a> ';
        echo '<a href="mailto:?subject=Share &amp;body=email: '.$summons.'" title="Share"><i class="fa fa-envelope-o" aria-hidden="true"></i></a> ';
        echo '<a class="copy-text" data-clipboard-target="#key" href="#"><i class="fa fa-clipboard" aria-hidden="true"></i></a>';

        echo '<div style="margin-top:100px;">';
        echo '<img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.$qr.'&choe=UTF-8" style="margin: 0 auto;display: block;">';
        echo '</div>';
        ?>
        <script>
        function myFunction() {
            document.getElementById("key").value = "<?php echo generateRandomString(); ?>";
        }
        </script>
    </div>
</div>
</div>
