<?php 
require '../model/addressbook.php';
require '../model/cities.php';

session_start();
    $res = isset($_SESSION['detailsDisplay'])
    ? unserialize($_SESSION['detailsDisplay'])
    : new addressbook(); 
    $details = isset($_SESSION['detailsDisplay'])
    ? unserialize($_SESSION['detailsDisplay'])
    : new addressbook(); 
  ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Address Book</title>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"> -->
<link rel="stylesheet" href="../libs/bootstrap.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <div class="row">
            <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="page-header">
                        <h4>Add Details</h4>
                    </div>
                    <!-- <p>Please fill this form and submit to add details record in the database.</p> -->
                    <form action="../index.php?act=add" method="post" >


                        <!-- first field -->
                        <div class="form-group">
                            <label>name</label>
                            <input type="text" name="name" class="form-control" required> 
                        </div>


                        <!-- second field -->
                        <div class="form-group">
                            <label>FirstName</label>
                            <input name="firstname" type ="text" class="form-control" required> 
                        </div>


                        <!-- third field -->
                         <div class="form-group">
                            <label>Street Name</label>
                            <input name="street" type ="text" class="form-control" required> 
                        </div>



                        <!-- fourth field -->
                         <div class="form-group">
                            <label>zip_code</label>
                            <input type ="text" name="zip_code" class="form-control" required> 
                        </div>


                         <!-- fourth field -->
                        <div class="form-group">
                            <label for="text">City</label>
                            <select name="city" class="form-control" placeholder="Select your city">
                                <?php 
                                for($i=0; $i<count($res); $i++){?>
                                    <option value= "<?php echo $res[$i]?>">
                                    <?php echo $res[$i]?>
                                    </option>
                                <?php }?>   
                            </select>
                        </div>


                        <!-- submit form -->                        
                        <input type="submit" name="addbtn" class="btn btn-primary" value="Submit">
                        <a href="../index.php" class="btn btn-default">Cancel</a>
                    </form>

                     
            </div>
            <div class="col-md-4"></div>
            </div>        
        </div>
    </div>
</body>
</html>
       