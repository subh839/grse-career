<?php 

    require 'config/config.php';
    require 'config/db.php';

    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // fetch posts
    $query = 'SELECT * FROM grsetable WHERE id = ' . $id;

    // get results
    $result = mysqli_query($conn, $query);
    // var_dump($result);

    // multiple ways to fetch data
    // here using mysqli and get it into an associative array
    $post = mysqli_fetch_assoc($result);
    //  print_r($post);
    //  var_dump($posts);

    // free the result from memory
    mysqli_free_result($result); // expects mysqli result, that is why

    // close connection 
    mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        body {
            font-family: sans-serif;
        }
        .tableHeadings {
            font-weight: bold;
        }
        </style>
</head>
<body>
    <table width="80%" border="0" cellpadding="5" cellspacing="1" bgcolor="#CCCCCC">


 <tbody>
     <tr>

    <td colspan="6" align="center" bgcolor="#FFFFFF" class="content">
    <span class="orangetext">
       
    <h2 style=" text-align:center; margin:0;">Tender Details</h2><br>
    <span class="orangetext"></span>
    <br></span></td>

</tr>
 
  <tr>
    <td height="25" colspan="6" bgcolor="#FFFFFF"><span class="green_txt2"><strong><u style="color: #209006">Open Tender details :</u></strong></span> </td>
  </tr>
  <tr class="blackbold">
    <td width="70" bgcolor="#FFFFFF">&nbsp;</td>
    <td align="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="107" bgcolor="#FFFFFF">&nbsp;</td>

    <td width="168" align="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="12" align="middle" bgcolor="#FFFFFF">&nbsp;</td>
    <td width="95" align="middle" bgcolor="#FFFFFF">&nbsp;</td>
  </tr>
  <tr>
    <td height="22" bgcolor="#FFFFFF" class="tableHeadings"> &nbsp;Enquiry No.</td>
    <td width="278" height="22" bgcolor="#FFFFFF" class="content">&nbsp; <?php echo $post['enquiryNum'] ?></td>

    <td height="22" bgcolor="#FFFFFF" class="blackbold tableHeadings">&nbsp;Tender Cost</td>
    <td height="22" colspan="2" bgcolor="#FFFFFF" class="content">&nbsp; <?php echo $post['costOfTenderDoc'] ?></td>
  </tr>
 
  <tr>
    <td height="22" bgcolor="#FFFFFF" class="blackbold tableHeadings">&nbsp;Enquiry Date</td>
    <td height="22" bgcolor="#FFFFFF" class="content">&nbsp; <?php echo $post['enquiryDate'] ?></td>

    <td height="22" bgcolor="#FFFFFF" class="blackbold tableHeadings">&nbsp;Closing Date</td>
    <td height="22" align="Cender" bgcolor="#FFFFFF" class="content">&nbsp; <?php echo $post['closingDateTime'] ?></td>
    <td height="22" bgcolor="#FFFFFF" class="blackbold tableHeadings">&nbsp;EMD(Rs.) </td>
    <td height="22" bgcolor="#FFFFFF" class="content">&nbsp; <?php echo $post['emd'] ?></td>
  </tr>
 
  <tr>
    <td height="22" bgcolor="#FFFFFF" class="blackbold tableHeadings">&nbsp;Title</td>

    <td height="22" colspan="5" align="left" valign="top" bgcolor="#FFFFFF"><?php echo $post['tenderDetails'] ?></td>
  </tr>
   <tr>
    <td height="22" bgcolor="#FFFFFF" class="blackbold tableHeadings">&nbsp;Status</td>

    <td height="22" colspan="5" align="left" valign="top" bgcolor="#FFFFFF"><?php echo $post['status'] ?></td>
  </tr>

  <tr>
    <td height="22" colspan="6" valign="top" bgcolor="#FFFFFF" class="tableHeadings"><span style="color: #209006">Bidder's Pre Qualification</span></td>
   <!-- <td align="middle" valign="top">&nbsp;</td> -->
  </tr>

  <tr bgcolor="#FFFFFF" class="Content">

    <td colspan="6" bgcolor="#FFFFFF"><table width="100%" border="0" class="content">
     
     <tbody><tr bgcolor="#FFFFFF">
    <!-- <td width="4%" valign="top">1.</td> -->
        <td colspan="4"><?php echo $post['bidderPreQualification'] ?></td>
    </tr> 
    </tbody></table></td>
  </tr>
  <tr style="background-color: #FFF" class="Content">

    <td>&nbsp;</td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
    <td valign="top">&nbsp;</td>
  </tr> 
        <tr>
            <td colspan="6" valign="top" style="background-color: #FFF; color: #209006" class="tableHeadings">&nbsp;Bidding Instruction</td>

        <!--     <td align="middle" valign="top">&nbsp;</td> -->
        </tr>
           <tr bgcolor="#FFFFFF" class="Content">
          <td colspan="6" bgcolor="#FFFFFF">&nbsp; <?php echo $post['biddingInstruction'] ?> <br></td>
        </tr>
          
          <tr class="body">
            <td colspan="2" bgcolor="#FFFFFF"><span class="blackbold ">&nbsp;</span><span class="tableHeadings" style="color: #209006">Download Tender Document</span></td>

            <td colspan="2" bgcolor="#FFFFFF"><table width="100%">
           
              <tbody><tr>
                <td width="10%" class="blackbold">&nbsp;</td>
                <!--<td width="90%" class="blackbold"><a href="Admin/PressTenderFiles/" target="_blank" title="View Document Details"></a></td>-->
                <td width="90%" class="blackbold">&nbsp;<a href="PDFs/<?php echo $post['pdfName'] ?>" target="_blank"><br /><strong><?php echo $post['pdfName'] ?></strong></a><br>Filetype: PDF<br />
                <td width="90%" class="blackbold">&nbsp;<a href="PDFs/<?php echo $post['pdfTwo'] ?>" target="_blank"><br /><strong><?php echo $post['pdfTwo'] ?></strong></a><br>Filetype: PDF<br />
            &nbsp;  
                
                </td>
              </tr>
             
            </tbody></table></td>
           
            <td bgcolor="#FFFFFF">&nbsp;</td>

            <td bgcolor="#FFFFFF">&nbsp;</td>
          </tr>
          
         
  <tr>
    <td height="35" colspan="6" bgcolor="#FFFFFF"><div align="right" style="padding-right:20px;"><strong>
     
    </strong></div></td>
  </tr>
</tbody></table>
</body>
</html>