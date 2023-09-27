<?
if($p_usermanagement <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/usereditor.js?dummy=<? echo (rand());?>"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" class="active-leftnav">User Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action=""  onsubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="userselectionprocess" style="padding:0">&nbsp;</td>
                        <td width="29%"  style="padding:0"><div class="resendtext"><a onclick="displayfilterdiv()" style="cursor:pointer">Filter>></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2"><div id="displayfilter" style="display:none">
                            <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#f7faff" style=" border:1px solid #ADD8F1">
                              <tr>
                                <td colspan="2"></td>
                              </tr>
                              <tr>
                                <td colspan="2"><strong>Login:
                                  <label> </label>
                                  </strong>
                                  <label><br />
                                  <input name="login_type" type="radio" id="logintype0" value="no" checked="checked" />
                                  Enabled</label>
                                  <label>
                                  <input type="radio" name="login_type" id="logintype1" value="yes" />
                                  Disabled</label>
                                  <label>
                                  <input type="radio" name="login_type" id="logintype2" value="" />
                                  All</label></td>
                              </tr>
                              <tr>
                                <td width="40%">&nbsp;</td>
                                <td width="60%"><div align="right" class="resendtext"><a onclick="refreshuserarray();" style="cursor:pointer">Load&gt;&gt;</a></div></td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" style="width:204px" onkeyup="usersearch(event);" autocomplete="off" />
                          <div id="detailloaddealerlist">
                            <select name="userlist" size="5" class="swiftselect" id="userlist" style="width:210px; height:400px;" onchange="selectfromlist();" onclick="selectfromlist()">
                              <option></option>
                            </select>
                          </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong> </td>
                      <td width="55%" id="totalcount" align="left">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">User Editor</td>
                            <td width="40%"><div align="right"></div></td>
                            <td width="33%"><div align="right"></div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#EDF4FF">
                                            <td width="28%" align="left" valign="top"  bgcolor="#EDF4FF">User Name:</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF"><input name="username" type="text" class="swifttext-mandatory" id="username" size="45" maxlength="40"  autocomplete="off" />
                                              <input type="hidden" name="lastslno" id="lastslno" />                                            </td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Full Name:</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#f7faff"><input name="fullname" type="text" class="swiftselect-mandatory" id="fullname" size="45" maxlength="40" autocomplete="off" /></td>
                                          </tr>
                                          <tr>
                                            <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr bgcolor="#EDF4FF">
                                                  <td width="29%" height="25px" align="left" valign="top" bgcolor="#EDF4FF">Password:</td>
                                                  <td width="71%"  height="25px" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td colspan="2" bgcolor="#EDF4FF" ><div id="displaypassworddfield" style="display:none" bgcolor="#F7FAFF" ><span onclick="Displaydiv1()" class="resentfont" >Reset User Password</span></div>
                                                          <div id="resetpwd" style="display:none; ">
                                                            <input name="password" type="text" class="swifttext" id="password" size="45" maxlength="30" autocomplete="off" />
                                                            &nbsp;&nbsp;<img src="../images/imax-pwdreset-button.jpeg" align="absmiddle" title="Password Update" alt="Password Update"  onclick="validatepwd()" style="cursor:pointer"  />&nbsp;&nbsp;<img src="../images/imax-pwdclose-button.jpeg" align="absmiddle" title="Close" alt="Close" onclick="closefunc()" style="cursor:pointer" /> </div></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td colspan="2" height="25px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td width="29%" height="19" align="left" valign="top" bgcolor="#f7faff" >Last Password: </td>
                                                        <td width="71%" style="padding-left:2px"  align="left" valign="top" bgcolor="#f7faff" id="passwordfield"> <span id="initialpassworddfield" style="display:none"> 
                                                        <input name="initialpassword" type="text" class="swifttext"                   id="initialpassword" size="45" readonly="readonly" style="background:#FEFFE6; color:#000000" autocomplete="off"  maxlength="30" /> 
                                                        </span>
                                                         <span id="displayresetpwd" style="display:none"> 
                                                         <input name="resetpassword" type="text" class="swifttext" id="resetpassword" size="45" readonly="readonly" style="background:#FEFFE6; color:#FF0000;" maxlength="30" autocomplete="off"/></span>
                                                        </td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Cell No:</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF"><input name="cellno" type="text" class="swiftselect" id="cellno" size="45" maxlength="20" autocomplete="off" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Emailid:</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#f7faff"><input name="emailid" type="text" class="swiftselect" id="emailid" size="45" maxlength="300" autocomplete="off" /></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Description:</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF"><textarea name="description" cols="42" class="swifttextarea" id="description"></textarea></td>
                                          </tr>
                                          <tr>
                                            <td valign="top" bgcolor="#F7FAFF">General Permissions:</td>
                                            <td valign="top" bgcolor="#F7FAFF"><label for="dealer">
                                              <input type="checkbox" name="dealer" id="dealer" />
                                              &nbsp;Dealer </label>
                                              <br />
                                              <label for="bills">
                                              <input type="checkbox" name="bills" id="bills" />
                                              &nbsp;Purchases </label>
                                              <br />
                                              <label for="credits">
                                              <input type="checkbox" name="credits" id="credits" />
                                              &nbsp;Credits </label>
                                              <br />
                                              <label for="editcustomerpassword">
                                              <input type="checkbox" name="editcustomerpassword" id="editcustomerpassword" />
                                              &nbsp;Reset Password </label>
                                              in Customer Master<br />
                                              <label for="cusattachcard">
                                              <input type="checkbox" name="cusattachcard" id="cusattachcard" />
                                              &nbsp;Attach PIN Number to Customer<br />
                                              </label>
                                              <label for="districtmapping">
                                              <input type="checkbox" name="districtmapping" id="districtmapping" />
                                              &nbsp;District to Dealer</label>
                                              <br />
                                              <label for="scheme">
                                              <input type="checkbox" name="scheme" id="scheme" />
                                              &nbsp;Scheme</label>
                                              <br />
                                              <label for="schemepricing">
                                              <input type="checkbox" name="schemepricing" id="schemepricing" />
                                              &nbsp;Scheme Pricing</label>
                                              <br />
                                              <label for="smscreditstocustomers">
                                              <input type="checkbox" name="smscreditstocustomers" id="smscreditstocustomers" />
                                              &nbsp;SMS Credits to Customers</label>
                                              <br />
                                              <label for="smscreditstodealer">
                                              <input type="checkbox" name="smscreditstodealer" id="smscreditstodealer" />
                                              &nbsp;SMS Credits to Dealer</label>
                                              <br />
                                              <label for="smscreditssummary">
                                              <input type="checkbox" name="smscreditssummary" id="smscreditssummary" />
                                              &nbsp;SMS Summary</label>
                                              <br />
                                              <label for="smsreceiptstodealers">
                                              <input type="checkbox" name="smsreceiptstodealers" id="smsreceiptstodealers" />
                                              &nbsp;SMS Receipts to Dealer<br />
                                              </label>
                                              <label>
                                              <input type="checkbox" name="suggestedmerging" id="suggestedmerging" />
                                              &nbsp;Suggested Merging </label>
                                              <br />
                                              <label for="viewrcidata">
                                              <input type="checkbox" name="viewrcidata" id="viewrcidata" />
                                              &nbsp;View RCI Data</label> <br />
                                              <label for="activitylog">
                                              <input type="checkbox" name="activitylog" id="activitylog" />
                                              &nbsp;Activity Log</label> <br />
                                              <label for="addbills">
                                              <input type="checkbox" name="addbills" id="addbills" />
                                              &nbsp;Add Purchases </label>
                                              <br />
                                              <label>
                                              <input type="checkbox" name="pindetails" id="pindetails" />
                                              &nbsp;Pin Details</label>
                                            </td>
                                            <td valign="top" bgcolor="#F7FAFF"><label for="editcustomercontact">
                                              <input type="checkbox" name="editcustomercontact" id="editcustomercontact" />
                                              &nbsp;Edit Customer Contact</label>
                                              <br />
                                              <label for="products">
                                              <input type="checkbox" name="products" id="products" />
                                              &nbsp;Products</label>
                                              <br />
                                              <label for="mergecustomer">
                                              <input type="checkbox" name="mergecustomer" id="mergecustomer" />
                                              &nbsp;Merge Customer</label>
                                              <br />
                                              <label for="blockcancel">
                                              <input type="checkbox" name="blockcancel" id="blockcancel" />
                                              &nbsp;Block/Cancel PIN Numbers</label>
                                              <br />
                                              <label for="transfercard">
                                              <input type="checkbox" name="transfercard" id="transfercard" />
                                              &nbsp;Transfer </label>
                                              <label for="label">PIN Numbers</label>
                                              <br />
                                              <label for="editdealerpassword">
                                              <input type="checkbox" name="editdealerpassword" id="editdealerpassword" />
                                              &nbsp;Reset Password </label>
                                              in Dealer Master<br />
                                              <label for="hardwarelock">
                                              <input type="checkbox" name="hardwarelock" id="hardwarelock" />
                                              &nbsp;Hardware Lock</label>
                                              <br />
                                              <label for="welcomemail">
                                              <input type="checkbox" name="welcomemail" id="welcomemail" />
                                              &nbsp;Resend Welcome Email</label>
                                              <br />
                                              <label for="producttodealer">
                                              <input type="checkbox" name="producttodealer" id="producttodealer" />
                                              &nbsp;Product to Dealer(Product)</label>
                                              <br />
                                              <label>
                                              <input type="checkbox" name="producttodealers" id="producttodealers" />
&nbsp;Product to Dealer(Dealer)</label>
											  <br />
                                              <label for="schemetodealer">
                                              <input type="checkbox" name="schemetodealer" id="schemetodealer" />
                                              &nbsp;Scheme to Dealer</label>
                                              <br />
                                              <label for="smsaccounttocustomers">
                                              <input type="checkbox" name="smsaccounttocustomers" id="smsaccounttocustomers" />
                                              &nbsp;SMS Account to Customers</label>
                                              <br />
                                              <label for="smsaccounttodealer">
                                              <input type="checkbox" name="smsaccounttodealer" id="smsaccounttodealer" />
                                              &nbsp;SMS Account to Dealer</label>
                                              <br />
                                              <label for="smsreceiptstocustomers">
                                              <input type="checkbox" name="smsreceiptstocustomers" id="smsreceiptstocustomers" />
                                              &nbsp;SMS Receipts to Customer</label>
                                              <br />
                                              <label>
                                              <input type="checkbox" name="viewinvoice" id="viewinvoice" />
                                              &nbsp;Bill Register</label>
                                              <br />
                                              <label>
                                              <input type="checkbox" name="crossproductsales" id="crossproductsales" />
                                              &nbsp;Cross Product Sales</label>
                                              
											</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Registration Permissions:</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#EDF4FF">
                                                  <td width="52%" valign="top"><label></label>
                                                      <label for="registration">
                                                      <input type="checkbox" name="registration" id="registration" />
                                                    &nbsp;Registration</label>
                                                      <br/>
                                                    <label for="reregistration">
                                                      <input type="checkbox" name="reregistration" id="reregistration" />
                                                    &nbsp;Re-Registration</label>
                                                     
<br/>
                                                     </td>
                                                  <td width="48%" valign="top">
                                                  <label for="withoutscratchcard">
                                                      <input type="checkbox" name="withoutscratchcard" id="withoutscratchcard" />
                                                    &nbsp;Without PIN Serial</label>
                                                      <br/>
                                                      <label></label>
                                                    <label for="newtransferpin">
                                                      <input type="checkbox" name="newtransferpin" id="newtransferpin" />
                                                   &nbsp;New Transfer </label>
                                                    PIN
                                                      <br />
                                                      <label for="customerpayment"></label></td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                          <tr bgcolor="#F7FAFF">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">Surrender Permissions :</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#F7FAFF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#F7FAFF">
                                                  <td width="52%" valign="top"><label></label>
                                                     <label for="forcesurrender">
                                                      <input type="checkbox" name="forcesurrender" id="forcesurrender" />
                                                    &nbsp;Force Surrender</label> 
                                                    
                                                     
<br/>
                                                  </td>
                                                  <td width="48%" valign="top"><label></label>
                                                    <label for="surrenderreport">
                                                      <input type="checkbox" name="surrenderreport" id="surrenderreport" />
                                                    Surrender</label> 
                                                    Report</td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Report Permissions:</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#EDF4FF">
                                                  <td width="52%" valign="top"><label for="regreports">
                                                    <input type="checkbox" name="regreports" id="regreports">
                                                    &nbsp;Registration Reports</label>
                                                    <br/>
                                                    <label for="contactdetails">
                                                    <input type="checkbox" name="contactdetails" id="contactdetails">
                                                    &nbsp;Contact Details Reports</label>
                                                    <br/> 
                                                    <label for="invoicedetails">
                                                    <input type="checkbox" name="invoicedetails" id="invoicedetails">
                                                    &nbsp;Invoice Details</label>
                                                    <br/>
                                                    <label for="updationduedetails">
                                                    <input type="checkbox" name="updationduedetails" id="updationduedetails">
                                                    &nbsp;Updation Due Details Reports</label>
                                                    <br/>
                                                    <label for="labelprint">
                                                    <input type="checkbox" name="labelprint" id="labelprint">
                                                    &nbsp;Label Print</label>
                                                    <br />
                                                    <label for="salessummaryreport">
                                                    <input type="checkbox" name="salessummaryreport" id="salessummaryreport" />
                                                    &nbsp;Sales Summary Reports</label>
                                                    <br />
                                                    <label for="updationdetailedreport">
                                                    <input type="checkbox" name="updationdetailedreport" id="updationdetailedreport" />
                                                    &nbsp;Updation Detailed Reports</label><br />
                                                    <label for="notregisteredreport">
                                                    <input type="checkbox" name="notregisteredreport" id="notregisteredreport" />
                                                    &nbsp;Not registered (Ageing)</label><br/>
                                                      <label for="transferredpinsreport">
                                                          <input type="checkbox" name="transferredpinsreport" id="transferredpinsreport" />
                                                          &nbsp;Transferred Pins Report</label>
                                                  </td>
                                                  <td width="48%" valign="top"> <label for="dealerreports">
                                                    <input type="checkbox" name="dealerreports" id="dealerreports" />
                                                    &nbsp;Dealer</label>
                                                    <br/>
                                                    <label for="productshipped">
                                                    <input type="checkbox" name="productshipped" id="productshipped" />
                                                    &nbsp;Product Shipped Reports</label>
                                                    <br/>
                                                    <label for="pinnoattachedreport">
                                                    <input type="checkbox" name="pinnoattachedreport" id="pinnoattachedreport" />
                                                    &nbsp;PIN Number Attached Reports</label>
                                                    <br />
                                                    <label for="updationsummaryreport">
                                                    <input type="checkbox" name="updationsummaryreport" id="updationsummaryreport" />
                                                    &nbsp;Updation Summary Reports</label>
                                                    <br />
                                                    <label for="crossproductreport">
                                                    <input type="checkbox" name="crossproductreport" id="crossproductreport" />
                                                    &nbsp;Cross Product  Reports</label>
                                                    <br />
                                                    <label for="datainaccuracyreport">
                                                    <input type="checkbox" name="datainaccuracyreport" id="datainaccuracyreport" />
                                                    &nbsp;Data Inaccuracy reports<br /></label>
                                                 <label for="categorysummary">   <input type="checkbox" name="categorysummary" id="categorysummary" />
&nbsp;Category Summary</label><br />
<label for="transactionsreport">   <input type="checkbox" name="transactionsreport" id="transactionsreport" />
&nbsp;Transactions Report</label>
</td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr bgcolor="#F7FAFF">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">Invoicing Permissions:</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#F7FAFF" style="padding:0">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#F7FAFF">
                                                  <td width="52%" valign="top"><label></label>
                                                    <label for="invoicing">
                                                    <input type="checkbox" name="invoicing" id="invoicing">
                                                    &nbsp;Invoicing / Receipts</label>
                                                    <br/>
                                                    <label for="dealerreceipts">
                                                    <input type="checkbox" name="dealerreceipts" id="dealerreceipts">
                                                    &nbsp;Dealer Receipts</label>
                                                    <br/>
                                                    <label for="invoice_register">
                                                    <input type="checkbox" name="invoice_register" id="invoice_register">
                                                    &nbsp;Invoice Register</label>
                                                    <br/>
                                                    <label for="matrixinvoice_register">
                                                    <input type="checkbox" name="matrixinvoice_register" id="matrixinvoice_register">
                                                    &nbsp;Matrix Invoice Register</label>
                                                    <br/>
                                                    <label for="manageinvoice">
                                                    <input type="checkbox" name="manageinvoice" id="manageinvoice">
                                                    &nbsp;Manage Invoices</label>
                                                    <br/>
                                                    <label for="managedealerinvoice">
                                                    <input type="checkbox" name="managedealerinvoice" id="managedealerinvoice">
                                                    &nbsp;Manage Dealer Invoices</label>
                                                    <br/>
                                                    <label for="managematrixinvoice">
                                                    <input type="checkbox" name="managematrixinvoice" id="managematrixinvoice">
                                                    &nbsp;Manage Matrix Invoices</label>
                                                    <br/>
                                                    <label for="bulkprintinvoice">
                                                    <input type="checkbox" name="bulkprintinvoice" id="bulkprintinvoice">
                                                    &nbsp;Bulk Print (Invoices)</label>
                                                    <br/>
                                                    <label for="dealerbulkprintinvoice">
                                                    <input type="checkbox" name="dealerbulkprintinvoice" id="dealerbulkprintinvoice">
                                                    &nbsp;Dealer Bulk Print (Invoices)</label>
                                                     <br/>
                                                    <label for="matrixbulkprintinvoice">
                                                    <input type="checkbox" name="matrixbulkprintinvoice" id="matrixbulkprintinvoice">
                                                    &nbsp;Matrix Bulk Print (Invoices)</label>                                                  </td>
                                                  <td  valign="top">
                                                  <label for="matrix_invoicing">
                                                    <input type="checkbox" name="matrix_invoicing" id="matrix_invoicing">
                                                    &nbsp;Matrix Invoicing / Receipts</label>
                                                    <br/>
                                                  <label for="dealerinvoice_register">
                                                    <input type="checkbox" name="dealerinvoice_register" id="dealerinvoice_register">
                                                    &nbsp;Dealer Invoice Register</label>
                                                    <br/>
                                                    <label for="outstanding_register">
                                                    <input type="checkbox" name="outstanding_register" id="outstanding_register">
                                                    &nbsp;Outstanding Register</label>
                                                    <br/>
                                                    <label for="receipt_register">
                                                    <input type="checkbox" name="receipt_register" id="receipt_register" />
                                                    &nbsp;Receipt Register </label>
                                                    <br />
                                                    <label for="customerpayment">
                                                    <input type="checkbox" name="customerpayment" id="customerpayment">
                                                    &nbsp;Customer Payment</label>  
                                                    <br />
                                                    <label for="dealerreceiptreconciliation">
                                                    <input type="checkbox" name="dealerreceiptreconciliation" id="dealerreceiptreconciliation">
                                                    &nbsp;Dealer Receipts Reconciliation</label>
                                                    <br/>
                                                    <label for="receiptreconsilation">
                                                    <input type="checkbox" name="receiptreconsilation" id="receiptreconsilation">
                                                    &nbsp;Receipt Reconciliation</label>
                                                    <br />
                                                    <label for="autoreceiptreconsilation">
                                                    <input type="checkbox" name="autoreceiptreconsilation" id="autoreceiptreconsilation">
                                                    &nbsp;Auto Receipt Reconciliation</label>
                                                    <br/>
                                                    <label for="addinvoice">
                                                    <input type="checkbox" name="addinvoice" id="addinvoice">
                                                    &nbsp;Add Invoices</label>
                                                  </td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td bgcolor="#EDF4FF" align="left" valign="top">Process Pending Permission: </td>
                                            <td width="36%" align="left" valign="top"><label for="dealerpendingrequest">
                                              <input type="checkbox" name="dealerpendingrequest" id="dealerpendingrequest" />
                                              Dealer Requests</label>
                                              <br/>
                                              <label for="customerpendingrequest">
                                              <input type="checkbox" name="customerpendingrequest" id="customerpendingrequest" />
                                              Customer Requests</label></td>
                                            <td width="33%">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#F7FAFF">
                                            <td bgcolor="#F7FAFF" align="left" valign="top">Implementation: </td>
                                            <td width="36%" align="left" valign="top"><label for="masterimplementation">
                                              <input type="checkbox" name="masterimplementation" id="masterimplementation" />
                                              Implementation Master</label>
                                              <br/>
                                              <label for="createimplementation">
                                              <input type="checkbox" name="createimplementation" id="createimplementation" />
                                              Create Implementation</label>                                            </td>
                                            <td width="33%" valign="top"><label for="impsummaryreport">
                                                    <input type="checkbox" name="impsummaryreport" id="impsummaryreport" />
                                              Implementation Summary Report</label><br />
<label for="impstatusreport">
                                                    <input type="checkbox" name="impstatusreport" id="impstatusreport" />
                                              Implementation Status Report</label></td>
                                          </tr>
                                          
                                          
                                        <!--  divya newpermissions-->
                                           <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Import Permissions :</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#EDF4FF">
                                                  <td width="52%" valign="top"><label></label>
                                                   <label for="importreceipt">
                                                      <input type="checkbox" name="importreceipt" id="importreceipt" />
                                                   Import Receipt </label>   
                                                    
<br/>
                                                  </td>
                                                  <td width="48%" valign="top"><label></label>
                                                  
                                                <label for="importinvoices">
                                                      <input type="checkbox" name="importinvoices" id="importinvoices" />
                                                    &nbsp;Import Invoice </label>   
                                                    
                                                    </td>
                                                </tr>
   
   
<tr>
	<td width="48%" valign="top"><label></label>
    <label for="importinvoicesgst">
                 <input type="checkbox" name="importinvoicesgst" id="importinvoicesgst" />&nbsp;Import Invoice GST</label>   
    </td>
</tr>                                                
                                                
                                            </table></td>
                                          </tr>
                                          
                                          <!--  AMC permissions-->
                                           <tr bgcolor="#F7FAFF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Amc Permissions :</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#EDF4FF">
                                                  <td width="52%" valign="top"><label></label>
                                                   <label for="importreceipt">
                                                      <input type="checkbox" name="customuser" id="customuser" />
                                                   Custom user </label>   
                                                    
<br/>
                                                  </td>
                                                 
                                                </tr>
                                            </table></td>
                                          </tr>








<tr bgcolor="#F7FAFF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">More Permissions :</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#EDF4FF">
                                                  <td width="52%" valign="top"><label></label>
                                                   <label for="mailamccustomer">
                                                      <input type="checkbox" name="mailamccustomer" id="mailamccustomer" />
                                                   Mail Amc Customer </label>   
                                                    
<br/>
                                                  </td>
                                                  
                                                </tr>
                                            </table></td>
                                          </tr>
                                          <tr bgcolor="#F7FAFF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Products New Permissions :</td>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr bgcolor="#EDF4FF">
                                                  <td width="52%" valign="top"><label></label>
                                                   <label for="addproductsnew">
                                                      <input type="checkbox" name="addproductsnew" id="addproductsnew" />
                                                   Add Products New </label>   
                                                  <br/>
                                                  </td>
                                                  
                                                </tr>
                                            </table></td>
                                          </tr>
                                          <tr bgcolor="#F7FAFF">
                                            <td bgcolor="#F7FAFF" align="left" valign="top">Disable Login</td>
                                            <td colspan="2" align="left" valign="top"><input type="checkbox" name="disablelogin" id="disablelogin" /></td>
                                          </tr>
                                          <tr>
                                            <td bgcolor="#EDF4FF" align="left" valign="top">Created Date:</td>
                                            <td colspan="2" bgcolor="#EDF4FF" align="left" valign="top"><label id="createddate"> &nbsp;</label></td>
                                            <td width="1%">&nbsp;</td>
                                            <td width="2%">&nbsp;</td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0" >
                                          <tr>
                                            <td width="44%" height="35" align="left" valign="middle"><div id="form-error"></div></td>
                                            <td width="56%" height="35" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onClick="newentry();document.getElementById('form-error').innerHTML = ''; " />
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
                                              &nbsp;
                                              <input name="delete" type="submit" class="swiftchoicebutton" id="delete" value="Delete" disabled="disabled" onClick="formsubmit('delete');"/>
                                              &nbsp;</td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<script>refreshuserarray();
</script>
<? }?>
