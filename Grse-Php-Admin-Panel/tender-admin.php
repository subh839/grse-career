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
    $query = 'SELECT * FROM grsetable';

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
        $unitName = mysqli_real_escape_string($conn, $_POST['unitName']);
        $enquiryNum = mysqli_real_escape_string($conn, $_POST['enquiryNum']);
        $enquiryDate = mysqli_real_escape_string($conn, $_POST['enquiryDate']);
        $tenderDetails = mysqli_real_escape_string($conn, $_POST['tenderDetails']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        $closingDateTime = mysqli_real_escape_string($conn, $_POST['closingDateTime']);
        $costOfTenderDoc = mysqli_real_escape_string($conn, $_POST['costOfTenderDoc']);
        $emd = mysqli_real_escape_string($conn, $_POST['emd']);
        $bidderPreQualification = mysqli_real_escape_string($conn, $_POST['bidderPreQualification']);
        $biddingInstruction = mysqli_real_escape_string($conn, $_POST['biddingInstruction']);

        if(!$unitName && !$enquiryNum && !$enquiryDate && !$tenderDetails && !$status && !$closingDateTime && !$costOfTenderDoc && !$emd && !$bidderPreQualification && !$biddingInstruction) {
            echo '<script type="text/JavaScript"> 
                    alert("All fields are required");
            </script>'  ;
        } 
        else{
            if($_FILES['pdfTwo']['error'] > 0){


                if(isset($_FILES['pdfOne'])) {// check if the pdf is uploaded or not
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
        
                            $sql2 = "INSERT INTO `grsetable` 
                                    (`unitName`, `enquiryNum`, `enquiryDate`, `tenderDetails`, `status`, `closingDateTime`, `costOfTenderDoc`, `emd`, `pdfName`,`biddingInstruction`,`bidderPreQualification`, `pdfTwo`) 
                                    VALUES ('$unitName', '$enquiryNum', '$enquiryDate', '$tenderDetails', '$status', '$closingDateTime', '$costOfTenderDoc', '$emd', '$new_pdf_one_name', '$biddingInstruction', '$bidderPreQualification', 'Null')";
                            $insert_data = mysqli_query($conn, $sql2);
                            if($insert_data){ //if data is inserted
                                echo '<script type="text/JavaScript"> 
                                alert("Tender uploaded successfully");
                                        </script>'  ;
                                        header('Location: '. ROOT_URL_TENDER);
                                
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
            else{
                if(isset($_FILES['pdfOne']) && isset($_FILES['pdfTwo'])) {// check if the pdf is uploaded or not
                    $pdf_one_name = $_FILES['pdfOne']['name']; //this will hold usser uploaded pdf name
                    $pdf_one_type = $_FILES['pdfOne']['type']; //this will hold usser uploaded pdf type
                    $tmp_one_name = $_FILES['pdfOne']['tmp_name']; //this temp name will help to save or move this im file to our folder where i store the all user uploaded pdf
                    
                    $pdf_two_name = $_FILES['pdfTwo']['name']; //this will hold usser uploaded pdf name
                    $pdf_two_type = $_FILES['pdfTwo']['type']; //this will hold usser uploaded pdf type
                    $tmp_two_name = $_FILES['pdfTwo']['tmp_name']; //this temp name will help to save or move this im file to our folder where i store the all user uploaded pdf
        
                    //now explode the pdf and get the extentions of the file
                    $pdf_one_explode = explode('.', $pdf_one_name);
                    $pdf_one_ext = end($pdf_one_explode); //here we get the extension of the pdf file 
                    
                    //now explode the pdf and get the extentions of the file
                    $pdf_two_explode = explode('.', $pdf_two_name);
                    $pdf_two_ext = end($pdf_two_explode); //here we get the extension of the pdf file 
    
    
                    $extensions = ['pdf','PDF']; // storing all supported formate of pdf in this array
                    if(in_array($pdf_one_ext, $extensions) == true && in_array($pdf_two_ext, $extensions) == true) {// if the user uploaded file extensions matched with availabe extesion
                        $time = time(); // hold current time in a var
                                        //when a user upload a pdf the current time will added with the file name
                                        //so all pdf file have a unique name
                        //lets now move the user uloaded pdf to our particular folder
                        $new_pdf_one_name = $time.$pdf_one_name;
                        $new_pdf_two_name = $time.$pdf_two_name;
                        if(move_uploaded_file($tmp_one_name, "PDFs/".$new_pdf_one_name) && move_uploaded_file($tmp_two_name, "PDFs/".$new_pdf_two_name)){//if user upload pdf move to our folder successfully
                            //now let's insert all the user data to the data base
        
                            $sql2 = "INSERT INTO `grsetable` 
                                    (`unitName`, `enquiryNum`, `enquiryDate`, `tenderDetails`, `status`, `closingDateTime`, `costOfTenderDoc`, `emd`, `pdfName`,`biddingInstruction`,`bidderPreQualification`, `pdfTwo`) 
                                    VALUES ('$unitName', '$enquiryNum', '$enquiryDate', '$tenderDetails', '$status', '$closingDateTime', '$costOfTenderDoc', '$emd', '$new_pdf_one_name', '$biddingInstruction', '$bidderPreQualification', '$new_pdf_two_name')";
                            $insert_data = mysqli_query($conn, $sql2);
                            if($insert_data){ //if data is inserted
                                echo '<script type="text/JavaScript"> 
                                alert("Tender uploaded successfully");
                                        </script>'  ;
                                        header('Location: '. ROOT_URL_TENDER);
                                
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
                        alert("Two files must be pdf");
                                </script>'  ;
                    }
        
                }
                else{
                    echo '<script type="text/JavaScript"> 
                    alert("Please select two pdf files");
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



        $query = "DELETE FROM grsetable WHERE id='$delete_id'";

        // data is successfuly deleted from grse table then redirect to home page
        if(mysqli_query($conn, $query)) {
            unlink("PDFs/".$delete_name );
            unlink("PDFs/".$delete_two );
            header('Location: '. ROOT_URL_TENDER . '');
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
    include './templates/navbar.php';
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
        <h1 class=" pt-5 font-weight-normal display-4 text-center">TENDER Admin</h1>
        <form class="p-5" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
            <fieldset>
                <div class="form-group">
                    <label for="unitName" class="form-label mt-4">Unit Name</label>
                    <input type="text" name="unitName" class="form-control bg-secondary" id="unitName" placeholder="Unit Name">
                </div>
                <div class="form-group">
                    <label for="enquiryNum" class="form-label mt-4">Enquiry No.</label>
                    <input type="text" name="enquiryNum" class="form-control bg-secondary" id="enquiryNum" placeholder="Enquiry No.">
                </div>
                <div class="form-group">
                    <label for="enquiryDate" class="form-label mt-4">Enquiry Date</label>
                    <input type="date" name="enquiryDate" class="form-control bg-secondary" id="enquiryDate" placeholder="Enquiry Date">
                </div>
                
                <div class="form-group">
                    <label for="tenderDetails" class="form-label mt-4">Tender Details</label>
                    <input type="text" name="tenderDetails" class="form-control bg-secondary" id="tenderDetails" placeholder="Tender Details">
                </div>
                <div class="form-group">
                    <label for="status" class="form-label mt-4">Status</label>
                    <select class="form-select bg-secondary" aria-label="Default select example" name="status">
                        <option value="Single" selected>Single</option>
                        <option value="Open">Open</option>
                        <option value="Limited">Limited</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="closingDateTime" class="form-label mt-4">Closing Date/Time</label>
                    <input type="datetime-local" name="closingDateTime" class="form-control bg-secondary" id="closingDateTime" placeholder="Closing Date / Time">
                </div>
                <div class="form-group">
                    <label for="costOfTenderDoc" class="form-label mt-4">Cost of Tender Document</label>
                    <input type="number" step="0.01" name="costOfTenderDoc" class="form-control bg-secondary" id="costOfTenderDoc" placeholder="Cost of Tender Document">
                </div>
                <div class="form-group">
                    <label for="emd" class="form-label mt-4">EMD</label>
                    <input type="number" step="0.01" name="emd" class="form-control bg-secondary" id="emd" placeholder="EMD" />
                </div>
                <div class="form-group">
                    <label for="emd" class="form-label mt-4">Bidding Instruction</label>
                    <input type="text" name="biddingInstruction" class="form-control bg-secondary" id="emd" placeholder="Bidding Instruction" />
                </div>
                <div class="form-group">
                    <label for="emd" class="form-label mt-4">Bidder's Pre Qualification</label>
                    <input type="text" name="bidderPreQualification" class="form-control bg-secondary" id="emd" placeholder="Bidder's Pre Qualification" />
                </div>
                <div class="form-group">
                    <label for="formFile" class="form-label mt-4">Upload PDF one</label>
                    <input class="form-control bg-secondary" type="file" name='pdfOne' id="formFile" require />
                    <label for="formFile" class="form-label mt-4">Upload PDF two</label>
                    <input class="form-control bg-secondary" type="file" name='pdfTwo' id="formFile" require />
                    <label for="formFile" class="form-label mt-4">Only PDF allowed</label>
                </div>

                <button type="submit" name="submit" class="mt-3 px-5 btn btn-outline-primary">Submit</button>
            </fieldset>
        </form>
    </div>

    <!-- Table -->
    <div class="p-5 m-5">
        <h1 class="text-center pb-5 display-4">Tender Published</h1>
        <table class="table table-hover text-dark " style="border: 1px solid black;">
            <thead style="border: 1px solid black;">
                <tr >
                    <th scope="col" style="border: 1px solid black;">Sl No.</th>
                    <th scope="col" style="border: 1px solid black;">Unit Name</th>
                    <th scope="col" style="border: 1px solid black;">Enquiry No.</th>
                    <th scope="col" style="border: 1px solid black;">Enquiry Date</th>
                    <th scope="col" style="border: 1px solid black;">Tender Details</th>
                    <th scope="col" style="border: 1px solid black;">Status</th>
                    <th scope="col" style="border: 1px solid black;">Closing Date/Time</th>
                    <th scope="col" style="border: 1px solid black;">Cost of Tender Document</th>
                    <th scope="col" style="border: 1px solid black;">EMD</th>
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
                    <a href="tender-view.php?id=<?php echo $post['id'] ?>" target="_blank">
                        <?php echo $post['enquiryNum']; ?></td>
                    </a>
                    <td style="border: 1px solid black;"><?php echo $post['enquiryDate']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['tenderDetails']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['status']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['closingDateTime']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['costOfTenderDoc']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['emd']; ?></td>
                    <td style="border: 1px solid black;"><?php echo $post['pdfName']; ?></td>
                    <td style="border: 1px solid black;">
                    <a href="tender-edit.php?id=<?php echo $post['id'] ?>" target="_blank" class="btn btn-outline-primary">
                    Edit
                    </a>
                </td>
                    <td style="border: 1px solid black;">
                     <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <input type="hidden" name="delete_id" value="<?php echo $post['id']; ?>" />
                        <input type="hidden" name="delete_name" value="<?php echo $post['pdfName']; ?>" />
                        <input type="hidden" name="delete_two" value="<?php echo $post['pdfTwo']; ?>" />
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
