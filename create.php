<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$Name = $Branch = $Number = $Gender = "";
$Name_err = $Branch_err = $Number_err =  $Gender_err ="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
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
    
    // Validate Number
    $input_Number = trim($_POST["Number"]);
    if(empty($input_Number)){
        $Number_err = "Please enter Moblie Number";     
    } elseif(!ctype_digit($input_Number)){
        $Number_err = "Please enter a positive integer value.";
    } else{
        $Number = $input_Number;
    }
    
     // Validate Gender
    $input_Gender = trim($_POST["Gender"]);
    if(empty($input_Gender)){
        $Gender_err = "Please enter Gender.";     
    } else{
        $Gender = $input_Gender;
    }
     
    // Check input errors before inserting in database
    if(empty($Name_err) && empty($Branch_err) && empty($Number_err) && empty($Gender_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO StudentDetails (Name, Branch, MobileNumber, Gender) VALUES (:Name, :Branch, :MobileNumber, :Gender)";
         
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":Name",$param_Name);
            $stmt->bindParam(":Branch",$param_Branch);
            $stmt->bindParam(":MobileNumber",$param_Number);
            $stmt->bindParam(":Gender",$param_Gender);
            
            // Set parameters
            $param_Name =$Name;
            $param_Branch=$Branch;
            $param_Number=$Number;
            $param_Gender=$Gender;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
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
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
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
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add Student Details to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="Name" class="form-control <?php echo (!empty($Name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Name; ?>" placeholder="Your Name...">
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
                            <label>Mobile Number</label>
                            <input type="text" name="Number" class="form-control <?php echo (!empty($Number_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $Number; ?>" placeholder="Your Number...">
                            <span class="invalid-feedback"><?php echo $Number_err;?></span>
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
                        
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="index.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>