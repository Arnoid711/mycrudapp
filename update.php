<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Name = $Branch = $Gender = "";
$Name_err = $Branch_err =  $Gender_err ="";
 
// Processing form data when form is submitted
if(isset($_POST["MobileNumber"]) && !empty($_POST["MobileNumber"])){
    echo "1st";
    // Get hidden input value
    $Number = $_POST["MobileNumber"];
    
    // Validate name
    $input_Name = trim($_POST["Name"]);
    if(empty($input_Name)){
        $Name_err = "Please enter a name.";
    } elseif(!filter_var($input_Name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $Name_err = "Please enter a valid name.";
    } else{
        $Name = $input_Name;
    }
    
    // Validate Branch
    $input_Branch = trim($_POST["Branch"]);
    if(empty($input_Branch)){
        $Branch_err = "Please enter Branch.";     
    } else{
        $Branch = $input_Branch;
    }
    
    // Validate Gender
    $input_Gender = trim($_POST["Gender"]);
    if(empty($input_Gender)){
        $Gender_err = "Please enter Gender.";     
    } else{
        $Gender = $input_Gender;
    }
     
    // Check input errors before inserting in database
    if(empty($Name_err) && empty($Branch_err) && empty($Gender_err)){
        // Prepare an update statement
        $sql = "UPDATE StudentDetails SET Name= :Name, Branch=:Branch, Gender=:Gender WHERE MobileNumber = :MobileNumber";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":Name", $param_Name);
            $stmt->bindParam(":Branch", $param_Branch);
            $stmt->bindParam(":Gender", $param_Gender);
            $stmt->bindParam(":MobileNumber", $param_Number);
            
            // Set parameters
            $param_Name =$Name;
            $param_Branch=$Branch;
            $param_Number=$Number;
            $param_Gender=$Gender;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         // Close statement
        unset($stmt);
    }
    // Close connection
    unset($pdo);}

else{
    // Check existence of MoblieNumber parameter before processing further
    if(isset($_GET["MobileNumber"]) && !empty(trim($_GET["MobileNumber"]))){
   
    // Get URL parameter
    $Number =  trim($_GET["MobileNumber"]);
    // Prepare a select statement
    $sql = "SELECT * FROM StudentDetails WHERE MobileNumber = :MobileNumber";
    
    if($stmt = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $stmt->bindParam(":MobileNumber", $param_Number);
        
        // Set parameters
        $param_Number = $Number;
        
        // Attempt to execute the prepared statement
        if($stmt->execute()){
            if($stmt->rowCount() == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Retrieve individual field value
                 $Name = $row["Name"] ;
                 $Branch = $row["Branch"];
                 $Gender = $row["Gender"];
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: error.php");
                exit();
            }
            
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
    
    // Close statement
          unset($stmt);
    
    // Close connection
    unset($pdo);
    }else{
        // URL doesn't contain id parameter. Redirect to error page
        header("location: error.php");
        exit();
    }
}

?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Update Record</h2>
                    <p>Please edit the input values and submit to update the student details.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="Name" id = "Name" class="form-control <?php echo (!empty($Name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Name; ?>" placeholder="Your Name...">
                            <span class="invalid-feedback"><?php echo $Name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Branch</label>
                            <select id="Branch" name="Branch" class="form-control <?php echo (!empty($Branch_err)) ? 'is-invalid' : ''; ?>"><?php echo $Branch; ?>
                             <span class="invalid-feedback"><?php echo $Branch_err;?></span>
                             <option value="Select">Select</option>
                             <option value="Computer Science">Computer Science</option>
                             <option value="Mechanical">Mechanical</option>
                             <option value="Electrical">Electrical</option>
                             <option value="Electronics">Electronics</option>
                           </select>
                           
                        </div>
                        
                       <div class="form-group">
                            <label>Gender</label>
                            <select id="Gender" name="Gender" class="form-control <?php echo (!empty($Gender_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Gender; ?>">
                                <span class="invalid-feedback"><?php echo $Gender_err;?></span>
                              <option value="Select">Select</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                              <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <input type="hidden" name="Number" id="Number" value="<?php echo $Number; ?>" >
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>