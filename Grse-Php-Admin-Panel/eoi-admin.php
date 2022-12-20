<?php
// Start the session
session_start();
if(!isset($_SESSION['user'])){
    $AUTH = false;
}
else{
    $AUTH = true;
}

?>

<?php include 'templates/header.php'; ?>

<?php 

    require 'config/config.php';
    require 'config/db.php';

    // fetch grse order by created_at column in descending order
    $query = 'SELECT * FROM careertable';

    // get results
    $result = mysqli_query($conn, $query);
    //var_dump($result);

    // multiple ways to fetch data
    // here using mysqli and get it into an associative array
    $grse = mysqli_fetch_all($result, MYSQLI_ASSOC);
    //  var_dump($grse);

    // Check For Submit
    if(isset($_POST['submit'])) {

        // GET data from form
        $postName = mysqli_real_escape_string($conn, $_POST['postName']);
        $careerDetails = mysqli_real_escape_string($conn, $_POST['careerDetails']);
        $notificationNum = mysqli_real_escape_string($conn, $_POST['notificationNum']);
        $dateOfcareerPub = mysqli_real_escape_string($conn, $_POST['dateOfcareerPub']);
       // $closingDateTime = mysqli_real_escape_string($conn, $_POST['closingDateTime']);
     //   $bidderPreQualification = mysqli_real_escape_string($conn, $_POST['bidderPreQualification']);
       // $biddingInstruction = mysqli_real_escape_string($conn, $_POST['biddingInstruction']);

        if(!$postName && !$careerDetails && !$notificNum && !$dateOfcareerPub) {
            echo '<script type="text/JavaScript"> 
                    alert("All fields are required");
            </script>'  ;
        } 
        else{


            if($_FILES['pdfTwo']['error'] > 0){
                if(isset($_FILES['pdfOne'])) {// check if the image is uploaded or not
                    $pdf_one_name = $_FILES['pdfOne']['name']; //this will hold usser uploaded pdf name
                    $pdf_one_type = $_FILES['pdfOne']['type']; //this will hold usser uploaded pdf type
                    $tmp_one_name = $_FILES['pdfOne']['tmp_name']; //this temp name will help to save or move this im file to our folder where i store the all user uploaded pdf

                    //now explode the pdf and get the extentions of the file
                    $pdf_one_explode = explode('.', $pdf_one_name);
                    $pdf_one_ext = end($pdf_one_explode); //here we get the extension of the pdf file 
     
        
                    $extensions = ['pdf','PDF']; // storing all supported formate of pdf in this array
                    if(in_array($pdf_one_ext, $extensions) == true) {// if the user uploaded file extensions matched with availabe extesion
                        $time = time(); // hold current time in a var
                                        //when a user upload a pdf the current time will added with the file name
                                        //so all pdf file have a unique name
                        //lets now move the user uloaded pdf to our particular folder
                        $new_pdf_one_name = $time.$pdf_one_name;
                        if(move_uploaded_file($tmp_one_name, "PDFs/".$new_pdf_one_name)){//if user upload pdf move to our folder successfully
                            //now let's insert all the user data to the data base
        
                            $sql2 = "INSERT INTO `careertable` 
                                    (`postName`, `careerDetails`, `notificationNum`, `dateOfcareerPub`, `pdfName`, `pdfTwo`) 
                                    VALUES ('$postName', '$careerDetails', '$notificationNum',  '$dateOfcareerPub',  '$new_pdf_one_name', 'Null')";
                            $insert_data = mysqli_query($conn, $sql2);
                            if($insert_data){ //if data is inserted
                                echo '<script type="text/JavaScript"> 
                                alert("Career uploaded successfully");
                                        </script>'  ;
                                        header('Location: '. ROOT_URL_CAREER);
                                
                            }
                            else{
                                echo '<script type="text/JavaScript"> 
                                alert("Something went wrong..!");
                                        </script>'  ;
                            }
        
                        }
                        
                    }
                    else{
                        echo '<script type="text/JavaScript"> 
                        alert("please select a pdf file");
                                </script>'  ;
                    }
        
                }
                else{
                    echo '<script type="text/JavaScript"> 
                    alert("Please select a pdf");
                            </script>'  ;
                }
            }
           
                  
        
                }
               
            }


        
    // Check For Delete
    echo isset($_POST['delete']);
    if(isset($_POST['delete'])){

        // GET data from form
        $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);
        $delete_name= mysqli_real_escape_string($conn, $_POST['delete_name']);
        $delete_two= mysqli_real_escape_string($conn, $_POST['delete_two']);

        $query = "DELETE FROM eoitable WHERE id='$delete_id'";

        unlink("PDFs/".$delete_name );
        unlink("PDFs/".$delete_two );

        // data is successfuly deleted from grse table then redirect to home page
        if(mysqli_query($conn, $query)) {
            header('Location: '. ROOT_URL_CAREER . '');
        }else {
            echo "ERROR: ". mysqli_error($conn);
        }
    }

    // $AUTH = false;
    
    if(isset($_POST['user'])){
        $loginCred = mysqli_real_escape_string($conn, $_POST['user_input']);

        $query = "SELECT * FROM cred WHERE userId = '$loginCred'";

        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result)){
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user'] = $row['userId'];
            $AUTH = true;
        }
        else{
            echo '<script type="text/JavaScript"> 
            alert("Wrong Credential");
                    </script>'  ;
        }
    }


    // free the result from memory
    mysqli_free_result($result); // expects mysqli result, that is why

    // close connection 
    mysqli_close($conn);
    
?>

    <!-- Form -->
    <?php
  if($AUTH == 1){
    include './templates/navbarCareer.php';
  }
  ?>
    <?php if($AUTH != 1) { ?>
    <div class="login_page">
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="card">
                <label for="user">Please Provide Your Passcode</label>
                <input type="text" name="user_input" id="user_input">
                <button class="checking_btn" name="user">Login</button>
            </div>
        </form>
    </div>
    
    <?php } else { ?>
    
    <div class="container bg-white">
        <h1 class=" pt-5 font-weight-normal display-4 text-center">Career Admin</h1>
        <form class="p-5" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <fieldset>
                <div class="form-group">
                    <label for="unitName" class="form-label mt-4">Post Name</label>
                    <input type="text" name="unitName" class="form-control bg-secondary" id="unitName" placeholder="Unit Name">
                </div>
                <div class="form-group">
                    <label for="eoiDetails" class="form-label mt-4">Career Details</label>
                    <input type="text" name="eoiDetails" class="form-control bg-secondary" id="eoiDetails" placeholder="EOI Details">
                </div>
                <div class="form-group">
                    <label for="eoiNum" class="form-label mt-4">Notification No.</label>
                    <input type="text" name="eoiNum" class="form-control bg-secondary" id="eoiNum" placeholder="EOI No.">
                </div>
                <div class="form-group">
                    <label for="dateOfEoiPub" class="form-label mt-4">Date Of Publication</label>
                    <input type="date" name="dateOfEoiPub" class="form-control bg-secondary" id="dateOfEoiPub" placeholder="Date Of Publication">
                </div>
          
                <div class="form-group">
                    <label for="formFile" class="form-label mt-4">Upload PDF</label>
                    <input class="form-control bg-secondary" type="file" name='pdfOne' id="formFile" require />
                  
                    <label for="formFile" class="form-label mt-4">Only PDF allowed</label>
                </div>

                <button type="submit" name="submit" class="mt-3 px-5 btn btn-outline-primary">Submit</button>
            </fieldset>
        </form>
    </div>

    <!-- Table -->
    <div class="p-5 m-5">
        <h1 class="text-center pb-5 display-4">EOI Published</h1>
        <table class="table table-hover text-dark " style="border: 1px solid black;">
            <thead style="border: 1px solid black;">
                <tr >
                    <th scope="col" style="border: 1px solid black;">Sl No.</th>
                    <th scope="col" style="border: 1px solid black;">Unit Name</th>
                    <th scope="col" style="border: 1px solid black;">EOI Details</th>
                    <th scope="col" style="border: 1px solid black;">EOI No.</th>
                    <th scope="col" style="border: 1px solid black;">Date Of Publication</th>
                    <th scope="col" style="border: 1px solid black;">Closing Date/Time</th>
                    <th scope="col" style="border: 1px solid black;">PDF</th>
                    <th scope="col" style="border: 1px solid black;">EDIT</th>
                    <th scope="col" style="border: 1px solid black;">DELETE</th>
                </tr>
            </thead>
            <tbody style="border: 1px solid black;">
                <?php foreach($grse as $post) : ?>
                <tr style="border: 1px solid black;">
                    <th scope="row" style="border: 1px solid black;"><?php echo $post['id']; ?></th>
                    <td style="border: 1px solid black;">
                        <?php echo $post['unitName']; ?>
                    </td>
                    <td style="border: 1px solid black;">
                    <a href="eoi-view.php?id=<?php echo $post['id'] ?>" target="_blank">
                        <?php echo $post['eoiDetails']; ?></td>
                    </a>
                    <td style="border: 1px solid black;"><?php echo $post['eoiNum']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['dateOfEoiPub']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['closingDateTime']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['pdfName']; ?></td>
                    <td style="border: 1px solid black;">
                    <a href="eoi-edit.php?id=<?php echo $post['id'] ?>" target="_blank" class="btn btn-outline-primary">
                    Edit
                    </a>
                </td>
                    <td style="border: 1px solid black;">
                     <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>" />
                        <input type="hidden" name="delete_name" value="<?php echo $post['pdfName']; ?>" />
                  
                        <input type="submit" name="delete" class="btn btn-outline-danger" value="Delete" />
                     </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php include 'templates/footer.php'; ?>

    <?php } ?>
