<?php
ob_start("ob_gzhandler");

include('../inc/ajax-referer-security.php');
include('../functions/phpfunctions.php');
     $dealertype = $_POST['option'];

      if($dealertype == "msp")
      {
          $dealerTypePiece = "'psp'";
          $mspDealerPiece = "and dealertypehead = ''";
          $mpsDealertype = "Select PSP Dealer Type";
      }
      else if($dealertype == "psp")
      {
          $dealerTypePiece = "'msp'";
          $mpsDealertype = "Select MSP Dealer Type";
      }
      else if($dealertype == "ssp")
      {
          $dealerTypePiece = "'msp','psp'";
          $mpsDealertype = "Select MSP/PSP Dealer Type";
      }


      if($dealerTypePiece!= "") {
          $query = "select businessname,slno from inv_mas_dealer where dealertype in (" . $dealerTypePiece . ")".$mspDealerPiece;
          $result = runmysqlquery($query);
          $count = mysqli_num_rows($result);
          if ($count > 0) {
              $dealers_arr .= '<option value="">'.$mpsDealertype.'</option>';
              while ($fetch = mysqli_fetch_array($result)) {
                  $slno = $fetch['slno'];
                  $businessname = $fetch['businessname'];
                  $dealers_arr .= '<option value="' . $slno . '">' . $businessname . '</option>';
              }
          } else{
              if($dealertype == "msp")
                  $dealers_arr = '<option value="">No More PSP Dealers</option>';
              else
                  $dealers_arr = '<option value="">Select Dealer Type First</option>';
          }
      }
      else
          $dealers_arr = '<option value="">Select Dealer Type First</option>';

        // encoding array to json format
        echo json_encode($dealers_arr);

?>
