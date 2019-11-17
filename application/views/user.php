<div class="container">
        <h2>Users</h2>
        <div class="table-responsive">
            <table class="table table-hover table-bordered table-striped">
              <tr>
                  <th>
                      Name
                  </th>
                  <th>
                      Email
                  </th>
                  <th>
                      Matric Card
                  </th>
                  <th>
                      IC
                  </th>
                  <th>
                      Program
                  </th>
                  <th>
                        Type of Vehicle
                  </th>
                  <th>
                        Plate Number
                  </th>
                  <th>
                        Last Login
                  </th>
                  <th>
                      Level Name
                  </th>
                  <th>
                      QR Code
                  </th>                  
                  <th>
                      Edit
                  </th>
                  <th colspan="1">
                      Delete
                  </th>
              </tr>
                    <?php
                        foreach($groups as $row)
                        {
                        if($row->role == 1){
                            $rolename = "Admin";
                        }elseif($row->role == 3){
                            $rolename = "Employee";
                        }elseif($row->role == 4){
                            $rolename = "Student";
                        }

                        echo '<tr>';
                        echo '<td>'.$row->first_name.'</td>';
                        echo '<td>'.$row->email.'</td>';
                        echo '<td>'.$row->s_mcard.'</td>';
                        echo '<td>'.$row->s_ic.'</td>';
                        echo '<td>'.$row->s_program.'</td>';
                        echo '<td>'.$row->s_type.'</td>';
                        echo '<td>'.$row->s_plate.'</td>';
                        echo '<td>'.$row->last_login.'</td>';
                        echo '<td>'.$rolename.'</td>';
                        echo '<td><img class="img-responsive img-thumbnail" src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl='.$row->id.'&choe=UTF-8" style="margin: 0 auto;display: block;"></td>';
                        echo '<td><a href="'.site_url().'main/edituser/"><button type="button" class="btn btn-primary">Edit</button></a></td>';
                        echo '<td><a href="'.site_url().'main/deleteuser/'.$row->id.'"><button type="button" class="btn btn-danger">Delete</button></a></td>';
                        echo '</tr>';
                        }
                    ?>
            </table>
        </div>
    </div>
