<?php
require '../model/addressbook.php';

session_start();
$details = isset($_SESSION['detailsDisplay'])
    ? unserialize($_SESSION['detailsDisplay'])
    : new addressbook(); 
    $res = isset($_SESSION['spotbCities'])
    ? unserialize($_SESSION['spotbCities'])
    : new addressbook(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="../libs/bootstrap.css">
    <style type="text/css">
        .wrapper{
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
                        <h2>Update Addressbook</h2>
                    </div>
                    <p>Please fill this form and submit to add Addresbook record in the database.</p>
                    <form action="../index.php?act=update" method="post" >


                        <!-- first field -->
                        <div class="form-group <?php echo !empty(
                            $details->category_msg
                        )
                            ? 'has-error'
                            : ''; ?>">
                            <label>Category</label>
                            <input type="text" name="category" class="form-control" value="<?php echo $details->category; ?>">
                            <span class="help-block"><?php echo $details->category_msg; ?></span>
                        </div>


                        <!-- second field -->
                        <div class="form-group <?php echo !empty(
                            $details->name_msg
                        )
                            ? 'has-error'
                            : ''; ?>">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $details->name; ?> ">
                            <span class="help-block"><?php echo $details->name_msg; ?></span>
                        </div>


                        <!-- third field -->
                         <div class="form-group <?php echo !empty(
                            $details->name_msg
                        )
                            ? 'has-error'
                            : ''; ?>">
                            <label>Street</label>
                            <input type="text" name="street" class="form-control" value="<?php echo $details->street; ?> ">
                            <span class="help-block"><?php echo $details->name_msg; ?></span>
                        </div>



                         <!-- fourth field -->
                         <div class="form-group <?php echo !empty(
                            $details->zip_code_msg
                        )
                            ? 'has-error'
                            : ''; ?>">
                            <label>zip_code</label>
                            <input type="text" name="zip_code" class="form-control" value="<?php echo $details->zip_code; ?> ">
                            <span class="help-block"><?php echo $details->zip_code_msg; ?></span>
                        </div>



                         <!-- fifth field -->
                         <div class="form-group <?php echo !empty(
                            $details->city_msg
                        )
                            ? 'has-error'
                            : ''; ?>">
                            <label for="text">City</label>
                            <select name="city" class="form-control" placeholder="Select your city">
                            <option value= "<?php echo $details->city; ?>">
                                <?php echo $details->city; ?>
                            </option>
                                <?php
                                for($i=0; $i<count($res); $i++){?>
                                    <option value= "<?php echo $res[$i]?>">
                                    <?php echo $res[$i]?>
                                    </option>
                                <?php }?>   
                            </select>
                            <span class="help-block"><?php echo $details->city_msg; ?></span>
                        </div>

                        <div class="form-group">
                            
                        </div>

 
                          
                        <!-- submit form -->
                        <input type="hidden" name="id" value="<?php echo $details->id; ?>"/>
                        <input type="submit" name="updatebtn" class="btn btn-primary" value="Submit">
                        <a href="../index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>