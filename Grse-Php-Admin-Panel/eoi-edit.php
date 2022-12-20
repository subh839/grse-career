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

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // fetch posts
    $query = 'SELECT * FROM eoitable WHERE id = ' . $id;

    // get results
    $result = mysqli_query($conn, $query);
    // var_dump($result);

    // multiple ways to fetch data
    // here using mysqli and get it into an associative array
    $post = mysqli_fetch_assoc($result);


    // Check For Submit
    if(isset($_POST['submit'])) {

        // GET data from form
        $unitName = mysqli_real_escape_string($conn, $_POST['unitName']);
        $eoiDetails = mysqli_real_escape_string($conn, $_POST['eoiDetails']);
        $eoiNum = mysqli_real_escape_string($conn, $_POST['eoiNum']);
        $dateOfEoiPub = mysqli_real_escape_string($conn, $_POST['dateOfEoiPub']);
        $closingDateTime = mysqli_real_escape_string($conn, $_POST['closingDateTime']);
        $bidderPreQualification = mysqli_real_escape_string($conn, $_POST['bidderPreQualification']);
        $biddingInstruction = mysqli_real_escape_string($conn, $_POST['biddingInstruction']);
        $oldPdfOne = mysqli_real_escape_string($conn, $_POST['oldPdfOne']);
        $oldPdfTwo = mysqli_real_escape_string($conn, $_POST['oldPdfTwo']);

        if(!$unitName && !$eoiDetails && !$eoiNum && !$tenderDetails && !$dateOfEoiPub && !$closingDateTime && !$bidderPreQualification && !$biddingInstruction) {
            echo '<script type="text/JavaScript"> 
                    alert("All fields are required");
            </script>'  ;
        } 
        else{
            if($_FILES['pdfOne']['error'] > 0 && $_FILES['pdfTwo']['error'] > 0){

                $sql2 = "UPDATE eoitable SET unitName = '$unitName', eoiDetails = '$eoiDetails', eoiNum = '$eoiNum', dateOfEoiPub = '$dateOfEoiPub', closingDateTime = '$closingDateTime', bidderPreQualification = '$bidderPreQualification', biddingInstruction = '$biddingInstruction' WHERE id = $id";
                $insert_data = mysqli_query($conn, $sql2);
                if($insert_data){ //if data is inserted
                    echo '<script type="text/JavaScript"> 
                    alert("Tender updated successfully but both pdf not updated");
                            </script>'  ;
                                                                    header('Location: '. ROOT_URL_EOI);   
                    
                }
                else{
                    echo '<script type="text/JavaScript"> 
                    alert("Something went wrong..!");
                            </script>'  ;
                }
                
            }
            elseif($_FILES['pdfOne']['error'] == 0 && $_FILES['pdfTwo']['error'] == 0){
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
    
                        $sql2 = 'UPDATE `eoitable`  SET `unitName` = "'.$unitName.'", `eoiDetails` = "'.$eoiDetails.'", `eoiNum` = "'.$eoiNum.'", `dateOfEoiPub` = "'.$dateOfEoiPub.'", `closingDateTime` = "'.$closingDateTime.'", `bidderPreQualification` = "'.$bidderPreQualification.'", `biddingInstruction` = "'.$biddingInstruction.'", `pdfName` = "'.$new_pdf_one_name.'", `pdfTwo` = "'.$new_pdf_two_name.'" WHERE `id` = "'.$id.'"';
                        

                        unlink("PDFs/".$oldPdfOne );
                        unlink("PDFs/".$oldPdfTwo );


                        $insert_data = mysqli_query($conn, $sql2);
                        if($insert_data){ //if data is inserted
                            echo '<script type="text/JavaScript"> 
                            alert("Tender updated successfully with both pdf");
                                    </script>'  ;
                                                                            header('Location: '. ROOT_URL_EOI);
                            
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
            elseif($_FILES['pdfOne']['error'] > 0 && $_FILES['pdfTwo']['error'] == 0){
                if(isset($_FILES['pdfTwo'])) {// check if the pdf is uploaded or not
                    $pdf_two_name = $_FILES['pdfTwo']['name']; //this will hold usser uploaded pdf name
                    $pdf_two_type = $_FILES['pdfTwo']['type']; //this will hold usser uploaded pdf type
                    $tmp_two_name = $_FILES['pdfTwo']['tmp_name']; //this temp name will help to save or move this im file to our folder where i store the all user uploaded pdf

                    //now explode the pdf and get the extentions of the file
                    $pdf_two_explode = explode('.', $pdf_two_name);
                    $pdf_two_ext = end($pdf_two_explode); //here we get the extension of the pdf file 
    
    
                    $extensions = ['pdf','PDF']; // storing all supported formate of pdf in this array
                    if(in_array($pdf_two_ext, $extensions) == true) {// if the user uploaded file extensions matched with availabe extesion
                        $time = time(); // hold current time in a var
                                        //when a user upload a pdf the current time will added with the file name
                                        //so all pdf file have a unique name
                        //lets now move the user uloaded pdf to our particular folder
                        $new_pdf_two_name = $time.$pdf_two_name;
                        if(move_uploaded_file($tmp_two_name, "PDFs/".$new_pdf_two_name)){//if user upload pdf move to our folder successfully
                            //now let's insert all the user data to the data base
        
                             $sql2 = "UPDATE `eoitable` SET `unitName` = '$unitName', `eoiDetails` = '$eoiDetails', `eoiNum` = '$eoiNum', `dateOfEoiPub` = '$dateOfEoiPub', `closingDateTime` = '$closingDateTime', `bidderPreQualification` = '$bidderPreQualification', `biddingInstruction` = '$biddingInstruction', `pdfTwo` = '$new_pdf_two_name' WHERE `id` = '$id'"; 
                            
                             unlink("PDFs/".$oldPdfTwo );
                            
                             $insert_data = mysqli_query($conn, $sql2);
                            if($insert_data){ //if data is inserted
                                echo '<script type="text/JavaScript"> 
                                alert("Tender upated successfully with second pdf");
                                        </script>'  ;
                                                                                header('Location: '. ROOT_URL_EOI);
                                
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
        
                             $sql2 = "UPDATE `eoitable` SET `unitName` = '$unitName', `eoiDetails` = '$eoiDetails', `eoiNum` = '$eoiNum', `dateOfEoiPub` = '$dateOfEoiPub', `closingDateTime` = '$closingDateTime', `bidderPreQualification` = '$bidderPreQualification', `biddingInstruction` = '$biddingInstruction', `pdfName` = '$new_pdf_one_name' WHERE `id` = '$id'";
                            
                             unlink("PDFs/".$oldPdfOne );
                             
                             
                             $insert_data = mysqli_query($conn, $sql2);
                            if($insert_data){ //if data is inserted
                                echo '<script type="text/JavaScript"> 
                                alert("Tender updated successfully with first pdf");
                                        </script>'  ;
                                                                                header('Location: '. ROOT_URL_EOI);
                                
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
        }

    }


        
    // Check For Delete
    echo isset($_POST['delete']);
    if(isset($_POST['delete'])){

        // GET data from form
        $delete_id = mysqli_real_escape_string($conn, $_POST['delete_id']);

        $query = "DELETE FROM eoitable WHERE id='$delete_id'";

        // data is successfuly deleted from grse table then redirect to home page
        if(mysqli_query($conn, $query)) {
            header('Location: '. ROOT_URL_EOI . '');
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
    include './templates/navbarEoi.php';
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
        <h1 class=" pt-5 font-weight-normal display-4 text-center">EOI Admin</h1>
        <form class="p-5" method="post" action="eoi-edit.php?id=<?php echo $post['id'] ?>" enctype="multipart/form-data">
            <fieldset>
                <div class="form-group">
                    <label for="unitName" class="form-label mt-4">Unit Name</label>
                    <input type="text" name="unitName" class="form-control bg-secondary" id="unitName" placeholder="Unit Name" value="<?php echo $post['unitName'] ?>">
                </div>
                <div class="form-group">
                    <label for="eoiDetails" class="form-label mt-4">EOI Details</label>
                    <input type="text" name="eoiDetails" class="form-control bg-secondary" id="eoiDetails" placeholder="EOI Details" value="<?php echo $post['eoiDetails'] ?>">
                </div>
                <div class="form-group">
                    <label for="eoiNum" class="form-label mt-4">EOI No.</label>
                    <input type="text" name="eoiNum" class="form-control bg-secondary" id="eoiNum" placeholder="EOI No." value="<?php echo $post['eoiNum'] ?>">
                </div>
                <div class="form-group">
                    <label for="dateOfEoiPub" class="form-label mt-4">Date Of Publication</label>
                    <input type="date" name="dateOfEoiPub" class="form-control bg-secondary" id="dateOfEoiPub" placeholder="Date Of Publication" value="<?php echo $post['dateOfEoiPub'] ?>">
                </div>
                <div class="form-group">
                    <label for="closingDateTime" class="form-label mt-4">Closing Date/Time</label>
                    <input type="datetime-local" name="closingDateTime" class="form-control bg-secondary" id="closingDateTime" placeholder="Closing Date / Time" value="<?php echo $post['closingDateTime'] ?>">
                </div>
                <div class="form-group">
                    <label for="emd" class="form-label mt-4">Bidding Instruction</label>
                    <input type="text" name="biddingInstruction" class="form-control bg-secondary" id="emd" placeholder="Bidding Instruction" value="<?php echo $post['biddingInstruction'] ?>" />
                </div>
                <div class="form-group">
                    <label for="emd" class="form-label mt-4">Bidder's Pre Qualification</label>
                    <input type="text" name="bidderPreQualification" class="form-control bg-secondary" id="emd" placeholder="Bidder's Pre Qualification" value="<?php echo $post['bidderPreQualification'] ?>" />
                </div>
                <div class="form-group">
                    <label for="formFile" class="form-label mt-4">Upload PDF one</label>
                    <input class="form-control bg-secondary" type="file" name='pdfOne' id="formFile" require />
                    <input class="form-control bg-secondary" type="hidden" name='oldPdfOne' id="formFile" require value="<?php echo $post['pdfName'] ?>"/>
                    <label for="formFile" class="form-label mt-4">Upload PDF two</label>
                    <input class="form-control bg-secondary" type="file" name='pdfTwo' id="formFile" require />
                    <input class="form-control bg-secondary" type="hidden" name='oldPdfTwo' id="formFile" require value="<?php echo $post['pdfTwo'] ?>"/>
                    <label for="formFile" class="form-label mt-4">Only PDF allowed</label>
                </div>

                <button type="submit" name="submit" class="mt-3 px-5 btn btn-outline-primary">Update</button>
            </fieldset>
        </form>
    </div>

<?php include 'templates/footer.php'; ?>

    <?php } ?>
