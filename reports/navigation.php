<?
$userid = imaxgetcookie('userid');
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="navigation">
        <div>
          <ul class="sf-menu">
            <li class="current"> <A href="./index.php?a_link=home_dashboard">Home</A> </li>
            <li class="current"><A>Masters</A>
              <UL>
                <? if($p_products == 'yes') { ?>
                <li><a href="./index.php?a_link=product">Products</a> </li>
                <? } ?>
                <? if($userid == '1') {  ?>
                <li><a href="./index.php?a_link=usereditor">User Editor</a> </li>
                <? } ?>
                <? if(($p_scheme == 'yes') ||($p_schemetodealer == 'yes') ||($p_producttodealer == 'yes') || ($p_schemepricing == 'yes') ) { ?>
                <li ><a ><span class="sf-menupointer" style="text-align:left">Scheme</span></a>
                  <ul class="sf-menu">
                    <? if($p_scheme == 'yes') { ?>
                    <li><a href="./index.php?a_link=scheme">Scheme Master</a> </li>
                    <? } ?>
                    <? if($p_schemetodealer == 'yes') { ?>
                    <li><a href="./index.php?a_link=schememapping">Scheme to Dealer</a> </li>
                    <? } ?>
                    <? if($p_producttodealer == 'yes') { ?>
                    <li><a href="./index.php?a_link=productmapping">Product to Dealer(Product)</a> </li>
                    <? } ?>
                    <? if($p_producttodealers == 'yes') { ?>
                    <li><a href="./index.php?a_link=producttodealers">Product to Dealer(Dealers)</a> </li>
                    <? } ?>
                    <? if($p_schemepricing == 'yes') { ?>
                    <li><a href="./index.php?a_link=schemepricing">Scheme Pricing</a> </li>
                    <? } ?>
                  </ul>
                </li>
                <? } ?>
                  <? if(($p_masterimplementation == 'yes') || ($p_createimplementation == 'yes')) { ?>
            <li class="current"><A><span class="sf-menupointer">Implementation</span></A>
              <UL>
                <? if($p_masterimplementation == 'yes') { ?>
                <li><a href="./index.php?a_link=deployment">Implementation Master</a> </li>
                <? } ?>
                 <? if($p_createimplementation == 'yes') { ?>
                <li><a href="./index.php?a_link=implementation">Create Implementation</a> </li>
                <? } ?>
                 <li><a href="http://imax.relyonsoft.com/implementation/demo/" target="_blank">Video Tutorials </a> </li>
              </UL>
            </li>
            <? } ?>
              </UL>
            </li>
            <li class="current"><A>Dealers</A>
              <UL>
                <? if($p_dealer == 'yes') { ?>
                <li><a href="./index.php?a_link=dealer">Dealer Master</a> </li>
                <? } ?>
                <? if($p_credits == 'yes') { ?>
                <li><a href="./index.php?a_link=credits">Credits</a> </li>
                <? } ?>
                <? if($p_bills == 'yes') { ?>
                <li><a href="./index.php?a_link=bill">Purchases</a> </li>
                <? } ?>
                 <? if($p_addbills == 'yes') { ?>
                <li><a href="./index.php?a_link=addbills">Add Purchases</a> </li>
                <? } ?>
                <? if($p_districtmapping == 'yes') { ?>
                <li><a href="./index.php?a_link=districtmapping">District to Dealer</a> </li>
                <? } ?>
                <? //if($p_smscreditstodealer == 'yes') { ?>
                <!--                <li><a href="./index.php?a_link=smscreditsdealers">SMS Credits to Dealer</a> </li>-->
                <? //} ?>
              </UL>
            </li>
            <li class="current"><A>Customers</A>
              <UL>
                <li><a href="./index.php?a_link=customer">Customer Master </a> </li>
                <li><a href="./index.php?a_link=customeramc">Customer AMCs </a> </li>
                <? if($p_hardwarelock == 'yes') { ?>
                <li><a href="./index.php?a_link=hardwarelock">Hardware Lock</a> </li>
                <? } ?>
                <? if($p_cusattachcard == 'yes') { ?>
                <li><a href="./index.php?a_link=cusattachcard">Attach PIN Number to customer</a> </li>
                <? } ?>
                <?  if(($p_mergecustomer == 'yes') || ($p_suggestedmerging == 'yes')) {?>
                <li ><A ><span class="sf-menupointer" style="text-align:left">Merge</span></A>
                  <ul class="sf-menu">
                    <? if($p_mergecustomer == 'yes') { ?>
                    <li><a href="./index.php?a_link=mergecustomer">Single Merge Customer</a> </li>
                    <? } ?>
                    <? if($p_suggestedmerging == 'yes') { ?>
                  <li><a href="./index.php?a_link=mergecustomerlist">Merge Suggestions</a> </li>
                  <? } ?>
                  </ul>
                </li>
                <? } ?>
                <li><a href="./index.php?a_link=cusinteraction">Interactions </a> </li>
                <? if($p_viewrcidata == 'yes') { ?>
                <li><a href="./index.php?a_link=rcidataviewer">View RCI Data</a> </li>
                <?  } ?>
                <? if($p_crossproductsales == 'yes') { ?>
                <li><a href="./index.php?a_link=crossproduct">Cross Product Sales</a> </li>
                <? } ?>
              </UL>
            </li>
            <li class="current"><A>SMS Service</A>
              <UL>
                <? if($p_smssummary == 'yes') { ?>
                <li><a href="./index.php?a_link=smscreditssummary">SMS Summary</a> </li>
                <? }?>
                <? if($p_smsaccounttocustomers == 'yes') { ?>
                <li><a  href="./index.php?a_link=smsaccount">Customer - Activation</a> </li>
                <? }?>
                <? if($p_smscreditstocustomers == 'yes') { ?>
                <li><a  href="./index.php?a_link=smscredits">Customer - Credits</a> </li>
                <? }?>
                <? if($p_smsreceipttocustomers == 'yes') { ?>
                <li><a  href="./index.php?a_link=smsreceipt">Customer - Receipts</a> </li>
                <? }?>
              </UL>
            </li>
            <li class="current"><A>PIN Numbers</A>
              <UL>
                <li><a href="./index.php?a_link=cardsearch">PIN number Search</a> </li>
                <? if($p_blockcancel == 'yes') { ?>
                <li><a  href="./index.php?a_link=blockcancel">Block/Cancel PIN number</a> </li>
                <? } ?>
                 <? if($p_newtransferpin == 'yes') { ?>
                <li><a  href="./index.php?a_link=transferpin">Transfer Pin Number</a> </li>
                <? } ?>
              </UL>
            </li>
            <? if(($p_invoicing == 'yes') ||($p_invoiceregister == 'yes') ||($p_receiptsregister == 'yes') || ($p_outstandingregister == 'yes')|| ($p_custpayment == 'yes') || ($p_bulkprintinvoice == 'yes') || ($p_manageinvoice == 'yes')|| ($p_addinvoice == 'yes') || ($p_importinvoices == 'yes' || $p_receiptreconsilation == 'yes') || ($p_importreceipt == 'yes')){ ?>
            <li class="current"><A>Billing</A>
              <UL>
                <? if($p_invoicing == 'yes') { ?>
                <li><a href="./index.php?a_link=invoicing">Invoicing</a> </li>
                <li><a href="./index.php?a_link=receipts">Receipts</a> </li>
                 <? } if($p_receiptreconsilation == 'yes') { ?>
                 <li><a href="./index.php?a_link=receiptreconciliation">Receipts Reconciliation</a> </li>
                   <? } ?>
                     <? if($p_addinvoice == 'yes') { ?>
                 <li><a href="./index.php?a_link=addinvoices">Add Invoices</a> </li>
                <? } ?>
                <? if(($p_invoiceregister == 'yes') ||($p_receiptsregister == 'yes') || ($p_outstandingregister == 'yes')) { ?>
                <li ><A ><span class="sf-menupointer"  style="text-align:left">Registers</span></A>
                  <ul class="sf-menu">
                    <? if($p_invoiceregister == 'yes') { ?>
                    <li><a href="./index.php?a_link=invoiceregister">Invoice Register</a> </li>
                    <? } ?>
                    <? if($p_receiptsregister == 'yes') { ?>
                    <li><a href="./index.php?a_link=receiptregister">Receipt Register</a> </li>
                    <? } ?>
                    <? if($p_outstandingregister == 'yes') { ?>
                    <li><a href="./index.php?a_link=outstandingregister">Outstanding Register</a> </li>
                    <? } ?>
                  </ul>
                </li>
                <? } ?>
                <? if($p_manageinvoice == 'yes') { ?>
                <li><a href="./index.php?a_link=manageinvoice">Manage Invoices</a> </li>
                <? } ?>
                 <? if($p_bulkprintinvoice == 'yes') { ?>
                <li><a href="./index.php?a_link=bulkprint">Bulk Print (Invoices)</a> </li>
                <? } ?>
                <? if($p_custpayment == 'yes') { ?>
                <li><a href="./index.php?a_link=custpayment">Customer Payment Request</a> </li>
                <? } ?>
                 <? if($p_importinvoices == 'yes') { ?>
                <li><a href="./index.php?a_link=invoiceimport">Invoice Import</a> </li>
                <? } ?>
                 <? if($p_importreceipt == 'yes') { ?>
                <li><a href="./index.php?a_link=receiptimport">Receipt Import</a> </li>
                <? } ?>
              </UL>
            </li>
            <? } ?>
            
           
            <li class="current"><A>Reports</A>
              <UL>
                <? if(($p_contactdetailsreport == 'yes') || ($p_labelprint == 'yes') ||($p_updationdetailsreport == 'yes') || ($p_cuspinattachedreport == 'yes')) { ?>
                <li ><A ><span class="sf-menupointer" >Customers </span></A>
                  <ul class="sf-menu">
                    <? if($p_contactdetailsreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=contactdetails">Customer Contact Details</a></li>
                    <? } ?>
                    <?  if($p_labelprint == 'yes') { ?>
                    <li><a href="./index.php?a_link=labelcontactdetails">Label Print (Customers) </a></li>
                    <? } ?>
                    <? if($p_updationdetailsreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=updationduedetails">Updation Due Details</a></li>
                    <? }  ?>
                    <? if($p_cuspinattachedreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=cuscardattachreport">PIN No Attached Details</a></li>
                    <? } ?>
                  </ul>
                </li>
                <? } ?>
                <? if(($p_regreports == 'yes') ||($p_productshippedreports == 'yes') || $p_invoicereports == 'yes')  { ?>
                <li ><A ><span class="sf-menupointer" >Registration</span></A>
                  <ul class="sf-menu">
                    <? if($p_regreports == 'yes') { ?>
                    <li><a href="./index.php?a_link=registrationdetails">Registration Details</a></li>
                    <? } ?>
                    <? if($p_invoicereports == 'yes') { ?>
                    <li><a href="./index.php?a_link=invoicedetails">Invoice Details</a></li>
                    <? } ?>
                    <? if($p_productshippedreports == 'yes') { ?>
                    <li><a href="./index.php?a_link=productshippeddetails">Product Shipped Details</a></li>
                    <? } ?>
                  </ul>
                </li>
                <? } ?>
                <? if(($p_dealerinvreports == 'yes')) { ?>
                <li ><a ><span class="sf-menupointer" style="text-align:left">Dealer</span></a>
                  <ul class="sf-menu">
                    <? if($p_dealerinvreports == 'yes') { ?>
                    <li><a href="./index.php?a_link=dealerdetails">Dealer Stock Details</a></li>
                    <? } ?>
                  </ul>
                </li>
                <? } ?>
                <? if(($p_updationdetailedreport == 'yes') ||($p_crossproductreport == 'yes') || ($p_updationsummaryreport == 'yes')) { ?>
                <li ><A ><span class="sf-menupointer"  style="text-align:left">Statistics</span></A>
                  <ul class="sf-menu">
                    <?  if($p_updationdetailedreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=updationdetailedreport">Customer Stats (Year Wise)</a></li>
                    <? }?>
                    <?  if($p_crossproductreport == 'yes') {  ?>
                    <li><a href="./index.php?a_link=crossproductdetails">Cross Product Sales Details </a></li>
                    <? }?>
                    <?  if($p_updationsummaryreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=updationsummary">Updation Due Summary </a></li>
                    <? }?> 
                      <?  if($p_categorysummaryreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=categorysummary">Category Summary </a></li>
                    <? }?>
                  </ul>
                </li>
                <? } ?>
                <? if(($p_impsummaryreport == 'yes')) { ?>
                <li ><A ><span class="sf-menupointer" >Implementation</span></A>
                  <ul class="sf-menu">
                    <? if($p_impsummaryreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=implementationsummary">Implementation Summary</a></li>
                    <? } ?>
                    <?  if($p_impstatusreport == 'yes') { ?>
                    <li><a href="./index.php?a_link=implementationdetailed">Implementation Detailed Report </a></li>
                    <? }?>
                  </ul>
                </li>
                <? } ?>
                <? if($p_datainaccuracyreport == 'yes') { ?>
                <li><a href="./index.php?a_link=customeranalysis">Data Inaccuracy Report </a> </li>
                <? } ?>
                 <? if($p_surrenderreport == 'yes') { ?>
                <li><a href="./index.php?a_link=surrenderreport">Surrender Report</a> </li>
                <? } ?> 
                 <? if($p_transactionsreport == 'yes') { ?>
                <li><a href="./index.php?a_link=transactionsreport">Transactions Report</a> </li>
                <? } ?> 
                  <? if($p_activitylog == 'yes') { ?>
                <li><a href="./index.php?a_link=activitylog">Activity Log</a> </li>
                <? } ?> 
                 <? if($p_notregisteredreport == 'yes') { ?>
                <li><a href="./index.php?a_link=notregistered">Not registered (Ageing)</a> </li>
                <? } ?>
              </UL>
            </li>
            <li class="current"><A>Profile</A>
              <UL>
                <li><a href="./index.php?a_link=changepassword">Change Password</a></li>
              </UL>
            </li>
            <!--<li class="current"><A>Pending Requests</A>
              <UL>
             <? //if($p_customerpendingrequest == 'yes') { ?>
              	<li><a href="./index.php?a_link=customerprofileupdate">Customer Profile Update</a></li>
  <?//} ?>
                    <? //if($p_dealerpendingrequest == 'yes') { ?>
              	<li><a href="./index.php?a_link=dealerprofileupdate">Dealer Profile Update</a></li>
                  <?// } ?>
              </UL>
             
           </li>-->
            <li class="current"><A href="../logout.php">Logout</A></li>
          </UL>
          <DIV class="clear"></DIV>
        </DIV>
      </DIV></td>
  </tr>
</table>
