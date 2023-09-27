<?php
$userid = imaxgetcookie('userid');
$auditorid = array('195','196');
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="navigation">
        <div>
          <ul class="sf-menu">
            <li class="current"> <A href="./index.php?a_link=home_dashboard">Home</A> </li>
            <li class="current"><A>Masters</A>
              <UL>
                <?php if($p_products == 'yes') { ?>
                <li><a href="./index.php?a_link=product">Products</a> </li>
                <?php } ?>
                <?php if($p_usermanagement == 'yes') {  ?>
                <li><a href="./index.php?a_link=usereditor">User Editor</a> </li>
                <?php } ?>
                <?php if(($p_scheme == 'yes') ||($p_schemetodealer == 'yes') ||($p_producttodealer == 'yes') || ($p_schemepricing == 'yes') ) { ?>
                <li ><a ><span class="sf-menupointer" style="text-align:left">Scheme</span></a>
                  <ul class="sf-menu">
                    <?php if($p_scheme == 'yes') { ?>
                    <li><a href="./index.php?a_link=scheme">Scheme Master</a> </li>
                    <?php } ?>
                    <?php if($p_schemetodealer == 'yes') { ?>
                    <li><a href="./index.php?a_link=schememapping">Scheme to Dealer</a> </li>
                    <?php } ?>
                    <?php if($p_producttodealer == 'yes') { ?>
                    <li><a href="./index.php?a_link=productmapping">Product to Dealer(Product)</a> </li>
                    <?php } ?>
                    <?php if($p_producttodealers == 'yes') { ?>
                    <li><a href="./index.php?a_link=producttodealers">Product to Dealer(Dealers)</a> </li>
                    <?php } ?>
                    <?php if($p_schemepricing == 'yes') { ?>
                    <li><a href="./index.php?a_link=schemepricing">Scheme Pricing</a> </li>
                    <?php } ?>
                  </ul>
                </li>
                <?php } ?>
                  <?php if(($p_masterimplementation == 'yes') || ($p_createimplementation == 'yes')) { ?>
            <li class="current"><A><span class="sf-menupointer">Implementation</span></A>
              <UL>
                <?php if($p_masterimplementation == 'yes') { ?>
                <li><a href="./index.php?a_link=deployment">Implementation Master</a> </li>
                <?php } ?>
                 <?php if($p_createimplementation == 'yes') { ?>
                <li><a href="./index.php?a_link=implementation">Create Implementation</a> </li>
                <?php } ?>
                 <li><a href="http://imax.relyonsoft.net/implementation/demo/" target="_blank">Video Tutorials </a> </li>
              </UL>
            </li>
            <?php } ?>
              </UL>
            </li>
            <li class="current"><A>Dealers</A>
              <UL>
                <?php if($p_dealer == 'yes') { ?>
                <li><a href="./index.php?a_link=dealer">Dealer Master</a> </li>
                <?php } ?>
                <?php if($p_credits == 'yes') { ?>
                <li><a href="./index.php?a_link=credits">Credits</a> </li>
                <?php } ?>
                <?php if($p_bills == 'yes') { ?>
                <li><a href="./index.php?a_link=bill">Purchases</a> </li>
                <?php } ?>
                 <?php if($p_addbills == 'yes') { ?>
                <li><a href="./index.php?a_link=addbills">Add Purchases</a> </li>
                <?php } ?>
                <?php if($p_districtmapping == 'yes') { ?>
                <li><a href="./index.php?a_link=districtmapping">District to Dealer</a> </li>
                <?php } ?>
                <?php //if($p_smscreditstodealer == 'yes') { ?>
                <!--<li><a href="./index.php?a_link=smscreditsdealers">SMS Credits to Dealer</a> </li>-->
                <?php //} ?>
              </UL>
            </li>
            <li class="current"><A>Customers</A>
              <UL><?php if(!in_array($userid, $auditorid, true)) { ?>
                <li><a href="./index.php?a_link=customer">Customer Master </a> </li>
                <li><a href="./index.php?a_link=customeramc">Customer AMCs </a> </li>
                <?php } if($p_hardwarelock == 'yes') { ?>
                <li><a href="./index.php?a_link=hardwarelock">Hardware Lock</a> </li>
                <?php } ?>
                <?php if($p_cusattachcard == 'yes') { ?>
                <li><a href="./index.php?a_link=cusattachcard">Attach PIN Number to customer</a> </li>
                <?php } ?>
                <?php if($p_dealerattachcard == 'yes') { ?>
                <li><a href="./index.php?a_link=dealerattachcard">Attach PIN Number to Dealer</a> </li>
                <?php } ?>
                <?php  if(($p_mergecustomer == 'yes') || ($p_suggestedmerging == 'yes')) {?>
                <li ><A ><span class="sf-menupointer" style="text-align:left">Merge</span></A>
                  <ul class="sf-menu">
                    <?php if($p_mergecustomer == 'yes') { ?>
                    <li><a href="./index.php?a_link=mergecustomer">Single Merge Customer</a> </li>
                    <?php } ?>
                    <?php if($p_suggestedmerging == 'yes') { ?>
                  <li><a href="./index.php?a_link=mergecustomerlist">Merge Suggestions</a> </li>
                  <?php } ?>
                  </ul>
                </li>
                <?php } if(!in_array($userid, $auditorid, true)) { ?>
                <li><a href="./index.php?a_link=cusinteraction">Interactions </a> </li>
                <?php } if($p_viewrcidata == 'yes') { ?>
                <li><a href="./index.php?a_link=rcidataviewer">View RCI Data</a> </li>
                <?php  } ?>
                <?php if($p_crossproductsales == 'yes') { ?>
                <li><a href="./index.php?a_link=crossproduct">Cross Product Sales</a> </li>
                <?php } ?>
                <?php if($p_customuser == 'yes') { ?>
                <li><a href="./index.php?a_link=customuser">Custom User</a> </li>
                <?php } ?>
                <?php if($p_mailamccustomer == 'yes') { ?>
                <li><a href="./index.php?a_link=mailamccustomer">Mail Amc Customer</a> </li>
                <?php } ?>
              </UL>
            </li>
            <li class="current"><A>SMS Service</A>
              <UL>
                <?php if($p_smssummary == 'yes') { ?>
                <li><a href="./index.php?a_link=smscreditssummary">SMS Summary</a> </li>
                <?php }?>
                <?php if($p_smsaccounttocustomers == 'yes') { ?>
                <li><a  href="./index.php?a_link=smsaccount">Customer - Activation</a> </li>
                <?php }?>
                <?php if($p_smscreditstocustomers == 'yes') { ?>
                <li><a  href="./index.php?a_link=smscredits">Customer - Credits</a> </li>
                <?php }?>
                <?php if($p_smsreceipttocustomers == 'yes') { ?>
                <li><a  href="./index.php?a_link=smsreceipt">Customer - Receipts</a> </li>
                <?php }?>
              </UL>
            </li>
            <li class="current"><A>PIN Numbers</A>
              <UL><?php if(!in_array($userid, $auditorid, true)) { ?>
                <li><a href="./index.php?a_link=cardsearch">PIN number Search</a> </li>
                <?php } if($p_blockcancel == 'yes') { ?>
                <li><a  href="./index.php?a_link=blockcancel">Block/Cancel PIN number</a> </li>
                <?php } ?>
                 <?php if($p_newtransferpin == 'yes') { ?>
                <li><a  href="./index.php?a_link=transferpin">Transfer Pin Number</a> </li>
                <?php } ?>
                <?php if($p_pindetails == 'yes') { ?>
                <li><a  href="./index.php?a_link=pindetails">Pin Details</a> </li>
                <?php } ?>
              </UL>
            </li>
            <?php if(($p_dealerreceipts == 'yes') || ($p_dealerreceiptreconciliation == 'yes') || ($p_invoicing == 'yes') || ($p_matrixinvoicing=='yes') || ($p_autoreceiptreconsilation == 'yes') || ($p_invoiceregister == 'yes') || ($p_dealerinvoiceregister == 'yes') || ($p_matrixinvoiceregister == 'yes') || $p_managematrixinvoice == 'yes' || $p_matrixbulkprintinvoice == 'yes' || ($p_receiptsregister == 'yes') || ($p_outstandingregister == 'yes')|| ($p_custpayment == 'yes') || ($p_bulkprintinvoice == 'yes') || ($p_dealerbulkprintinvoice == 'yes') || ($p_manageinvoice == 'yes') || ($p_managedealerinvoice == 'yes')|| ($p_addinvoice == 'yes') || ($p_importinvoices == 'yes' || $p_receiptreconsilation == 'yes') || ($p_importreceipt == 'yes')|| ($p_importinvoicesnew == 'yes')){ ?>
            <li class="current"><A>Billing</A>
              <UL>
                <?php if($p_invoicing == 'yes' || $p_matrixinvoicing == 'yes') { ?>
                  <li ><A ><span class="sf-menupointer"  style="text-align:left">Invoicing</span></A>
                <ul class="sf-menu">
                <?php if($p_invoicing == 'yes') { echo '<li><a href="./index.php?a_link=invoicing">Product Invoicing</a></li>'; } ?>
                <?php if($p_matrixinvoicing == 'yes') { echo '<li><a href="./index.php?a_link=matrixinvoicing">Matrix Invoicing</a></li>'; } ?>
                </ul></li>
                <?php } ?>
                <?php if($p_invoicing == 'yes' || $p_dealerreceipts == 'yes') { ?>
                <li ><A ><span class="sf-menupointer"  style="text-align:left">Receipts</span></A>
                  <ul class="sf-menu">
                  <?php if($p_invoicing == 'yes') { echo '<li><a href="./index.php?a_link=receipts">Customer Receipts</a></li>'; } ?>
                  <?php if($p_dealerreceipts == 'yes') { echo '<li><a href="./index.php?a_link=dealerreceipts">Dealer Receipts</a></li>'; } ?>
                  <?php if($p_matrixinvoicing == 'yes') { echo '<li><a href="./index.php?a_link=matrixreceipts">Matrix Receipts</a></li>'; } ?>
                </ul></li>
                  <?php } ?>
                  <?php if($p_dealerreceiptreconciliation == 'yes' || $p_autoreceiptreconsilation == 'yes' || $p_receiptreconsilation == 'yes') { ?>
                <li ><A ><span class="sf-menupointer">Reconciliation</span></A>
                  <ul class="sf-menu">
                  <?php if($p_dealerreceiptreconciliation == 'yes') { echo '<li style="width:210px"><a href="./index.php?a_link=dealerreceiptreconciliation">Dealer Receipts Reconciliation</a></li>'; } ?>
                  <?php if($p_autoreceiptreconsilation == 'yes') { echo '<li style="width:210px"><a href="./index.php?a_link=autoreceiptreconciliation">Auto Receipts Reconciliation</a></li>'; } ?>
                  <?php if($p_receiptreconsilation == 'yes') { echo '<li  style="width:210px"><a href="./index.php?a_link=receiptreconciliation">Customer Receipts Reconciliation</a></li>'; } ?>
                </ul></li>
                  <?php } ?>
                     <?php if($p_addinvoice == 'yes') { ?>
                 <li><a href="./index.php?a_link=addinvoices">Add Invoices</a> </li>
                <?php } ?>
                <?php if(($p_invoiceregister == 'yes') || ($p_matrixinvoiceregister == 'yes') || ($p_dealerinvoiceregister == 'yes') || ($p_receiptsregister == 'yes') || ($p_outstandingregister == 'yes')) { ?>
                <li ><A ><span class="sf-menupointer"  style="text-align:left">Registers</span></A>
                  <ul class="sf-menu">
                    <?php if($p_invoiceregister == 'yes') { ?>
                    <li><a href="./index.php?a_link=invoiceregister">Invoice Register</a> </li>
                    <?php } ?>
                    <?php if($p_matrixinvoiceregister == 'yes') { ?>
                    <li><a href="./index.php?a_link=matrixinvoiceregister">Matrix Invoice Register</a> </li>
                    <?php } ?>
                    <?php if($p_dealerinvoiceregister == 'yes') { ?>
                    <li><a href="./index.php?a_link=dealerinvoiceregister">Dealer Invoice Register</a> </li>
                    <?php } ?>
                    <?php if($p_receiptsregister == 'yes') { ?>
                    <li><a href="./index.php?a_link=receiptregister">Receipt Register</a> </li>
                    <?php } ?>
                    <?php if($p_outstandingregister == 'yes') { ?>
                    <li><a href="./index.php?a_link=outstandingregister">Outstanding Register</a> </li>
                    <?php } ?>
                  </ul>
                </li>
                <?php } ?>
                <?php if($p_manageinvoice == 'yes' || $p_managedealerinvoice == 'yes' || $p_managematrixinvoice == 'yes') { 
                  echo '<li ><A ><span class="sf-menupointer"  style="text-align:left">Manage Invoice</span></A><ul class="sf-menu">';
                   if($p_invoicing == 'yes') { echo '<li><a href="./index.php?a_link=manageinvoice">Product Invoices</a> </li>'; }
                   if($p_managedealerinvoice == 'yes') { echo '<li><a href="./index.php?a_link=managedealerinvoice">Dealer Invoices</a> </li>'; }
                   if($p_managematrixinvoice == 'yes') { echo '<li><a href="./index.php?a_link=managematrixinvoice">Matrix Invoices</a> </li>'; }
                   echo '</ul></li>';
                } ?>
                <?php if($p_dealerbulkprintinvoice == 'yes' || $p_bulkprintinvoice == 'yes' || $p_matrixbulkprintinvoice == 'yes') { ?>
                  <li ><A ><span class="sf-menupointer"  style="text-align:left">Bulk Prints</span></A>
                <ul class="sf-menu">
                <?php if($p_dealerbulkprintinvoice == 'yes') { echo '<li><a href="./index.php?a_link=dealerbulkprint">Dealer Bulk Print (Invoices)</a></li>'; } ?>
                <?php if($p_bulkprintinvoice == 'yes') { echo '<li><a href="./index.php?a_link=bulkprint">Product Bulk Print (Invoices)</a></li>'; } ?>
                <?php if($p_matrixbulkprintinvoice == 'yes') { echo '<li><a href="./index.php?a_link=matrixbulkprint">Matrix Bulk Print (Invoices)</a></li>'; } ?>
                </ul></li>
                <?php } ?>
        
                <?php if($p_custpayment == 'yes') { ?>
                <li><a href="./index.php?a_link=custpayment">Customer Payment Request</a> </li>
                <?php } ?>
                 <?php if($p_importinvoices == 'yes') { ?>
                <li><a href="./index.php?a_link=invoiceimport">Invoice Import</a> </li>
                <?php } ?>
                <?php if($p_importinvoicesgst == 'yes') { ?>
                <li><a href="./index.php?a_link=importinvoicesgst">Invoice Import GST</a> </li>
                <?php } ?>
                 <?php if($p_importreceipt == 'yes') { ?>
                <li><a href="./index.php?a_link=receiptimport">Receipt Import</a> </li>
                <?php } ?>
              </UL>
            </li>
            <?php } ?>
            
           
            <li class="current"><A>Reports</A>
              <UL>
                <?php if(($p_contactdetailsreport == 'yes') || ($p_labelprint == 'yes') ||($p_updationdetailsreport == 'yes') || ($p_cuspinattachedreport == 'yes')) { ?>
                <li ><A ><span class="sf-menupointer" >Customers </span></A>
                  <ul class="sf-menu">
                    <?php if($p_contactdetailsreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=contactdetails">Customer Contact Details</a></li>
                    <?php } ?>
                    <?php  if($p_labelprint == 'yes') { ?>
                    <li><a href="./index.php?a_link=labelcontactdetails">Label Print (Customers) </a></li>
                    <?php } ?>
                    <?php if($p_updationdetailsreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=updationduedetails">Updation Due Details</a></li>
                    <?php }  ?>
                    <?php if($p_cuspinattachedreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=cuscardattachreport">PIN No Attached Details</a></li>
                    <?php } ?>
                  </ul>
                </li>
                <?php } ?>
                <?php if(($p_regreports == 'yes') ||($p_productshippedreports == 'yes') || $p_invoicereports == 'yes')  { ?>
                <li ><A ><span class="sf-menupointer" >Registration</span></A>
                  <ul class="sf-menu">
                    <?php if($p_regreports == 'yes') { ?>
                    <li><a href="./index.php?a_link=registrationdetails">Registration Details</a></li>
                    <?php } ?>
                    <?php if($p_invoicereports == 'yes') { ?>
                    <li><a href="./index.php?a_link=invoicedetails">Invoice Details</a></li>
                    <?php } ?>
                    <?php if($p_productshippedreports == 'yes') { ?>
                    <li><a href="./index.php?a_link=productshippeddetails">Product Shipped Details</a></li>
                    <?php } ?>
                  </ul>
                </li>
                <?php } ?>
                <?php if(($p_dealerinvreports == 'yes')) { ?>
                <li ><a ><span class="sf-menupointer" style="text-align:left">Dealer</span></a>
                  <ul class="sf-menu">
<!--                    --><?php// if($p_dealerinvreports == 'yes') { ?>
<!--                    <li><a href="./index.php?a_link=dealerdetails">Dealer Stock Details</a></li>-->
<!--                    --><?php// } ?>
                    <?php if($p_newdealerinvreports == 'yes') { ?>
                    <li><a href="./index.php?a_link=newdealerdetails">Dealer Stock Details</a></li>
                    <?php } ?>
                  </ul>
                </li>
                <?php } ?>
                <?php if(($p_updationdetailedreport == 'yes') ||($p_crossproductreport == 'yes') || ($p_updationsummaryreport == 'yes')) { ?>
                <li ><A ><span class="sf-menupointer"  style="text-align:left">Statistics</span></A>
                  <ul class="sf-menu">
                    <?php  if($p_updationdetailedreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=updationdetailedreport">Customer Stats (Year Wise)</a></li>
                    <?php }?>
                    <?php  if($p_crossproductreport == 'yes') {  ?>
                    <li><a href="./index.php?a_link=crossproductdetails">Cross Product Sales Details </a></li>
                    <?php }?>
                    <?php  if($p_updationsummaryreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=updationsummary">Updation Due Summary </a></li>
                    <?php }?> 
                      <?php  if($p_categorysummaryreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=categorysummary">Category Summary </a></li>
                    <?php }?>
                  </ul>
                </li>
                <?php } ?>
                <?php if(($p_impsummaryreport == 'yes')) { ?>
                <li ><A ><span class="sf-menupointer" >Implementation</span></A>
                  <ul class="sf-menu">
                    <?php if($p_impsummaryreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=implementationsummary">Implementation Summary</a></li>
                    <?php } ?>
                    <?php  if($p_impstatusreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=implementationdetailed">Implementation Detailed Report </a></li>
                    <?php }?>
                  </ul>
                </li>
                <?php } ?>
                <?php if($p_datainaccuracyreport == 'yes') { ?>
                <li><a href="./index.php?a_link=customeranalysis">Data Inaccuracy Report </a> </li>
                <?php } ?>
                 <?php if($p_surrenderreport == 'yes') { ?>
                <li><a href="./index.php?a_link=surrenderreport">Surrender Report</a> </li>
                <?php } ?> 
                 <?php if($p_transactionsreport == 'yes') { ?>
                <li><a href="./index.php?a_link=transactionsreport">Transactions Report</a> </li>
                <?php } ?> 
                  <?php if($p_activitylog == 'yes') { ?>
                <li><a href="./index.php?a_link=activitylog">Activity Log</a> </li>
                <?php } ?> 
                 <?php if($p_notregisteredreport == 'yes') { ?>
                <li><a href="./index.php?a_link=notregistered">Not registered (Ageing)</a> </li>
                <?php } ?>
                  <?php if($p_transferredpinsreport == 'yes') { ?>
                      <li><a href="./index.php?a_link=transferredpinsreport">Transferred Pins</a> </li>
                  <?php } ?>
              </UL>
            </li>
            <li class="current"><A>Profile</A>
              <UL>
                <li><a href="./index.php?a_link=changepassword">Change Password</a></li>
              </UL>
            </li>
            <!--<li class="current"><A>Pending Requests</A>
              <UL>
             <?php //if($p_customerpendingrequest == 'yes') { ?>
              	<li><a href="./index.php?a_link=customerprofileupdate">Customer Profile Update</a></li>
  <?php //} ?>
                    <?php //if($p_dealerpendingrequest == 'yes') { ?>
              	<li><a href="./index.php?a_link=dealerprofileupdate">Dealer Profile Update</a></li>
                  <?php// } ?>
              </UL>
             
           </li>-->
            <li class="current"><A href="../logout.php">Logout</A></li>
          </UL>
          <DIV class="clear"></DIV>
        </DIV>
      </DIV></td>
  </tr>
</table>