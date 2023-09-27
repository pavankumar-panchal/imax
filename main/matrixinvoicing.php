<?
if($p_matrixinvoicing <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");

   $gst_tax_date = strtotime('2017-07-01');
   $invoicecreated_date = date('Y-m-d');
   $query2 = "SELECT igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '".$invoicecreated_date."' AND to_date >= '".$invoicecreated_date."'";
   $fetchrate = runmysqlqueryfetch($query2);
   //$gst_rate = $fetchrate['rate'];
   
   $igst_tax_rate = $fetchrate['igst_rate'];
   $cgst_tax_rate = $fetchrate['cgst_rate'];
   $sgst_tax_rate = $fetchrate['sgst_rate'];
   
?> 
<link href="../style/main.css?dummy = <? echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<? echo (rand());?>"  />
<script language="javascript" src="../functions/javascripts.js?dummy = <? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/matrixinvoicing.js?dummy = <? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<? echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/fileupload.js?dummy=<? echo (rand());?>"></script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
  <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="customerselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" border="0" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" ><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onkeyup="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <span style="display:none1">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled" />
                          </span>
                          <div id="detailloadcustomerlist">
                            <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();"  >
                            </select>
                          </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcount" align="left"></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="77%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                          <tr>
                            <td class="header-line" style="padding:0">Matrix Invoicing</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#f7faff">
                                    <tbody>
                                    <tr>
                                      <td  valign="top" style="border-right:#E6F0F9 1px solid; border-bottom:#E6F0F9 1px solid" bgcolor="#FEFFE6"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                        <tr>
                                          <td width="43%"><strong>Customer GSTIN: </strong></td>
                                          <td width="57%"><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaycustomergst" id="displaycustomergst"  ></td>
                                        </tr>
                                        <tr>
                                          <td width="43%"><strong>Customer ID: </strong></td>
                                          <td width="57%" ><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaycustomerid" id="displaycustomerid"  ></td>
                                        </tr>
                                        <tr>
                                          <td height="50px" colspan="2" valign="top"><strong>Address:<br />
                                            <br />
                                            </strong>
                                            <textarea  cols="47" rows="2" autocomplete="off" id="displayaddress" class="swifttext-readonly-border" type="text" name="displayaddress" readonly="readonly"></textarea></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Contact Person: </strong></td>
                                          <td height="35px"><input type="text" value="" size="30" class="swifttext-mandatory"  name="displaycontactperson"  id="displaycontactperson" /></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Email: </strong></td>
                                          <td height="35px" ><input type="text" value="" size="30" class="swifttext-mandatory"  name="displayemail"  id="displayemail" /></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Phone: </strong></td>
                                          <td height="35px;"><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displayphone"  id="displayphone" readonly="readonly"/></td>
                                        </tr>
                                        </table>
                                      </td>
                                      <td width="52%"  valign="top"  style=" border-bottom:#E6F0F9 1px solid" bgcolor="#FEFFE6"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                      <tr>
                                          <td width="43%"><strong>Customer Name: </strong></td>
                                          <td width="57%"><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaycompanyname" id="displaycompanyname"  ></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Cell: </strong></td>
                                          <td  height="35px;"><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displaycell"  id="displaycell" readonly="readonly"/></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Type of Customer: </strong></td>
                                          <td><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaytypeofcustomer" id="displaytypeofcustomer"  ></td>
                                        </tr>
                                        <tr>
                                          <td ><strong>Type of Category: </strong></td>
                                          <td><input type="text" class="swifttext-readonly-border" size="30" readonly="readonly" name="displaytypeofcategory" id="displaytypeofcategory"  ></td>
                                        </tr>
                                        <tr>
                                          <td><strong>PO Date:</strong></td>
                                          <td  ><input type="text" class="swifttext-mandatory" id="DPC_startdate" size="25" autocomplete="off" value=""  /></td>
                                        </tr>
                                        <tr>
                                          <td><strong>PO Reference:</strong></td>
                                          <td ><input type="text" value="" size="30" class="swifttext-mandatory"  name="poreference"  id="poreference" /></td>
                                        </tr>
                                        <tr>
                                          <td><strong>Pan No: </strong></td>
                                          <td ><input type="text" value="" size="30" class="swifttext-mandatory"  name="displaypanno"  id="displaypanno" /></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr><td colspan=2>&nbsp;</td></tr>
                                    <tr>
                                      <td colspan=2><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                        <tr>
                                          <td  width="12%"><div align="left">Sales Person: </div></td>
                                          <td width="30%" bgcolor="#f7faff" >
                                              <div align="left">
                                                <select name="salesperson" class="swiftselect-mandatory" id="salesperson" style="width:180px;" onchange="getdealerdetails(this);">
                                                  <option value="">Make A Selection</option>
                                                    <? include('../inc/matrix-dealer-invoicing.php'); ?>
                                                </select>
                                              </div>
                                          </td> 
                                    </tr>
                                          
                                    <tr>
                                      <td  width="9%" ><strong>Product Details</strong><input type="hidden" name="productgrid" id="productgrid" value="1">
                                      <input type="hidden" name="igstrate" id="igstrate" value="<?php echo $igst_tax_rate; ?>"/>
                                            <input type="hidden" name="cgstrate" id="cgstrate" value="<?php echo $cgst_tax_rate; ?>"/>
                                            <input type="hidden" name="sgstrate" id="sgstrate" value="<?php echo $sgst_tax_rate; ?>"/>
                                            <input type="hidden" name="state_gst_code"  id="state_gst_code" />
                                            <input type="hidden" name="branch_gstin"  id="branch_gstin" />
                                            <input type="hidden" name="branchhidden" id="branchhidden" />
                                            <input type="hidden" name="dealer_state_gst_code"  id="dealer_state_gst_code" />
                                            <input type="text" name="cgst_tax_amount"  id="cgst_tax_amount" />
                                            <input type="text" name="sgst_tax_amount"  id="sgst_tax_amount" />
                                            <input type="text" name="igst_tax_amount"  id="igst_tax_amount" />
                                            <input type="hidden" name="editinvreqno"  id="editinvreqno" />
                                            <input type="hidden" name="address"  id="address" />
                                            <input type="hidden" name="sez_enabled"  id="sez_enabled" />
                                            <input type="hidden" name="lastslno"  id="lastslno" /></td>
                                            <input type="hidden" name="invoicelastslno"  id="invoicelastslno" /></td>
                                      <!-- <td width="19%" align="left"><strong><a  name="additem" id="additem" onclick="additemgrid();" class= "r-text">Add >></a></strong></td> -->
                                    </tr>
                                    <tr>
                                      <td colspan=3 >
                                        <table width="100%" border="0" cellspacing="0" cellpadding="3"  id="seletedproductgrid" >
                                          <tbody>
                                            <tr>
                                              <td width="3%">1</td>
                                              <td width="10%" ><select name="purchasetype[]" class="swiftselect-mandatory" id="purchasetype1" >
                                              <option value="">Select</option>
                                              <option value="new">New</option>
                                              <option value="updation">Updation</option></select>
                                              </td>
                                              <td width="9%"><select name="producttype[]" class="swiftselect-mandatory producttype" id="producttype1" style="width:140px;" onchange="getproductname(this)">
                                                <option value="">Select Product Type</option>
                                                <optgroup label="Hardware">
                                                 <?php 
                                                    $query ="select * from inv_mas_matrixproduct where `group` = 'Hardware';";
                                                    $result = runmysqlquery($query);
                                                    while($fetch = mysqli_fetch_array($result))
                                                    {
                                                        echo "<option value='".$fetch['id']."'>".$fetch['productname']."</option>";
                                                    }
                                                  ?>                                                  
                                                </optgroup>
                                                <optgroup label="Software">
                                                <?php 
                                                  $query ="select * from inv_mas_matrixproduct where `group` = 'Software';";
                                                  $result = runmysqlquery($query);
                                                    while($fetch = mysqli_fetch_array($result))
                                                    {
                                                        echo "<option value='".$fetch['id']."'>".$fetch['productname']."</option>";
                                                    }
                                                  ?>    
                                                </optgroup>
                                                  ?>    
                                                </optgroup>
                                                </select>
                                                </td>
                                                  <td width="2%">
                                                  <input type="number" class="swifttext-mandatory" name="quantity[]" id="quantity1" placeholder="quantity" style="width:60px" min="1" onkeyup="getSerialDiv(this);" onchange="getSerialDiv(this);">
                                                  </td>
                                                  
                                                  <td width="10%">
                                                    <input name="unitamt[]" class="swifttext-mandatory" id="unitamt1" placeholder="Unit amt" size="12"  onkeyup="gettotalamount(this)"/>
                                                  </td>
                                                  <td width="10%">
                                                    <input name="invamount[]" class="swifttext-mandatory" id="invamount1" readonly placeholder="Invoice amt" size="12" />
                                                  </td>
                                                  <td width="10%" id="textboxDiv1"></td>
                                                  <td width="10%" ><input type="hidden" name="productnamehidden[]" id="productnamehidden1"> </td>
                                                  <td width="10%"><strong><a   id="removeitem" onclick="removegrid(this);" class= "r-text"  title="Remove Item!">X</a></strong></td>
                                                </tr>
                                              </tbody>
                                                </table>
                                            </td>
                                          </tr>
                                          <tr><td colspan=2 align="left"><strong><a  name="additem" id="additem" onclick="additemgrid();" class= "r-text">Add More Products >></a></strong></td></tr>
                                          <tr><td >&nbsp;</td></tr>
                                          <tr>
                                          <td valign="top" width="18%"><div align="left">Invoice Remarks:</div></td>
                                          <td valign="top" width="30%" ><div align="left">
                                          <textarea name="invoiceremarks"  class="swifttextarea" id="invoiceremarks" style="width:173px; height: 50px;"></textarea>
                                            <input type="hidden" name="productrate" id="productrate">
                                          </div></td>
                                          </tr>
                                          <tr>
                                          <td valign="top" width="12%"><div align="left">Payment Amount:</div></td>
                                          <td valign="top" width="60%"><div align="left">
                                            <input name="paymentamount" class="swifttext-mandatory" id="paymentamount"  size="24" placeholder="Enter total received amount" value="0" /><span style="color:red"> (Enter Amount to mention in receipt.)</span>
                                          </div></td>
                                          </tr>
                                          <tr>
                                          <td valign="top" width="18%"><div align="left">Payment Remarks:</div></td>
                                          <td valign="top" width="30%" ><div align="left">
                                          <textarea name="paymentremarks"  class="swifttextarea" id="paymentremarks" style="width:173px; height: 50px;"></textarea>                                            <input type="hidden" name="productrate" id="productrate">
                                          </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td><table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                      <tr>
                                                        <td width="30%" >SEZ (special Economic Zone) tax:</td>
                                                        <td width="70%"><input type="checkbox" name="seztax" id="seztax" onclick="sezfunc()"/></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                                <tr>
                                                  <td><div id="sextaxdivdisplay" style="display:none">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                                        <tr >
                                                          <td width="30%" valign="top" >SEZ Attachment File:</td>
                                                          <td width="70%" valign="top" style="border-right:1px solid  #C6E2FF"><input name="seztaxattachment" type="text" class="swifttext" id="seztaxattachment"  style="background:#FEFFE6;" size="30" maxlength="100" readonly="readonly"  autocomplete="off"/>
                                                            <img src="../images/fileattach.jpg" name="myfileuploadimage1" border="0" align="absmiddle"  id="myfileuploadimage1" style="cursor:pointer; " onclick="fileuploaddivid('','seztaxattachment','seztaxuploaddiv','595px','35%','6',document.getElementById('customerlist').value,'file_link','');"/><br />
                                                            <span class="textclass" >(Upload zip or rar file only)</span>
                                                            <input type="hidden" value="" name="file_link" id="file_link" /></td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                          <td valign="top" width="18%"><div align="left">TDS Declaraton:</div></td>
                                          <td valign="top" width="30%" ><div align="left">
                                          <input type="checkbox" name="tdsdeclaration" id="tdsdeclaration" />
                                          </div></td>
                                          </tr>
                                          <tr>
                                            <td valign="top"  colspan=2><div id="form-error"></div></td>
                                            <td valign="top" ><div align="center">
                                                <input name="preview"  value="Preview" type="button" class="swiftchoicebutton" id="preview" onclick="previewuserinvoice();" />
                                              </div></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    </table>
                                      </td>  
                                    </tr>
                                    </tbody>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                      <td colspan="3"> <form action="" method="post" name="submitformgrid" id="submitformgrid" onsubmit="return false;"> <table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="141" align="center" id="tabgroupgridh1" onclick="gridtab4('1','tabgroupgrid','&nbsp;&nbsp; Approve/Reject');" style="cursor:pointer" class="grid-active-tabclassr6"><span style="padding-left:4px;">Approve/Reject</span></td>
                                  <td width="4">&nbsp;</td>
                                  <td width="5" ></td>
                                  <td width="141" align="center" id="tabgroupgridh2" onclick="gridtab4('2','tabgroupgrid','&nbsp;&nbsp; Invoice Details');" style="cursor:pointer" class="grid-tabclassr6">Invoice Details</td>
                                  <td width="408"><div id="gridprocessing" style="display:none;"></div></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                <td width="45%"><div id="tabdescription">&nbsp;&nbsp;</div></td>
                                  <td width="24%"><span id="tabgroupgridwb1" style="text-align:center"></span><span id="tabgroupgridwb2" style="text-align:center;" ></span></td>
                                  <td width="31%"></td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                        <tr>
                                          <td align="center"><div id="invoicedetailsgridc1_1" >
                                              <table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;">
                                                
                                                <tr>
                                                  <td><div align="center">No datas found to display</div></td>
                                                </tr>
                                              </table>
                                            </div></td>
                                        </tr>
                                        <tr>
                                          <td ><div id="invoicedetailsgridc1link" style="height:20px;  padding:2px;" align="left"> </div></td>
                                        </tr>
                                      </table>
                                      <div id="invoicedetailsresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center"></div>
                                    </div></td>
                                </tr>
                              </table>
                              <div id="invoicedetailsresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                              <div id="tabgroupgridc2" style="overflow:auto; height:200px; width:704px; padding:2px; display:none;" align="center">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td><div id="tabgroupgridc1_1" align="center">No datas found to display</div></td>
                                  </tr>
                                  <tr>
                                    <td><div id="tabgroupgridc1link" style="height:20px; padding:2px;" align="left"> </div></td>
                                  </tr>
                                </table>
                                <div id="regresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center"></div>
                              </div></td>
                          </tr>
                        </table></form></td>
                    </tr>
                          
                          <tr>
                            <td><div style="display:none">
                                <div id="invoicepreviewdiv" style="width:600px; height:600px; overflow:auto">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td colspan="2"><div align="center"><font color="#FF0000"><strong>PREVIEW (Please verify and Proceed)</strong></font></div></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><table width="98%" cellspacing="0" cellpadding="4" border="0" class="table-border-grid">
                                          <tbody>
                                            <tr class="tr-grid-header">
                                              <td valign="top" align="left" colspan="2" width="60%"><strong>Customer Details</strong></td>
                                              <td width="40%" valign="top" align="left"><strong>Invoice Details</strong></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Customer GSTIN:</strong> <span id="gstnidpreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Customer ID:</strong> <span id="customeridpreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"><strong>Date:</strong> <span id="invoicedatepreview"> </span></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="left" class="td-border-grid" colspan="2" height="40px" id="companynamepreview" nowrap="nowrap">&nbsp;</td>
                                              <td width="49%" valign="top" align="left" class="td-border-grid"><span style="text-align: left;"><strong>Inv No:</strong> <span id="billnumber" style="text-align: right;">Not Available</span></span></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" align="left" class="td-border-grid" colspan="2" id="addresspreview">&nbsp;</td>
                                              <td width="49%" class="td-border-grid" style="text-align: left;"><strong>Marketing Exe:</strong> <span  id="marketingexepreview"></span><br />
                                              <strong>Region: </strong>**********<span id="branch"></span></td>
                                            </tr>
                                           <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Contact Person:</strong><span id="contactpersonpreview"></span></td>
                                              <td width="49%" class="td-border-grid" style="text-align: left;"><strong>GSTIN:</strong> <span  id="branchgstinpreview"></span></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Email :</strong>&nbsp;<span id="emailpreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"></td>
                                            </tr>
                                            <tr>
                                              <td width="29%" class="td-border-grid" style="text-align: left;"><strong>Phone:</strong>&nbsp;<span id="phonepreview" ></span></td>
                                              <td width="22%" class="td-border-grid" style="text-align: left;"><strong>Cell:</strong>&nbsp;<span id="cellpreview"> </span></td>
                                              <td class="td-border-grid" style="text-align: left;"></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Type of Customer: </strong>&nbsp;<span id="custypepreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"></td>
                                            </tr>
                                            <tr>
                                              <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Category of Customer: </strong>&nbsp; <span id="cuscategorypreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;"><br></td>
                                            </tr>
                                            <tr>
                                            <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>PO Reference:</strong> &nbsp; <span id="poreferencepreview"></span></td>
                                              <td class="td-border-grid" style="text-align: left;" ><strong>PO Date: </strong>&nbsp; <span id="podatepreview"></span></td>
                                              
                                            </tr>
                                            <tr>
                                              <td valign="top" align="left" colspan="3"  class="td-border-grid"><table width="100%" cellspacing="0" cellpadding="0" border="0" >
                                                  <tbody>
                                                    <tr >
                                                      <td width="23%" ><table width="98%" border="0" cellspacing="0" cellpadding="4" id="previewproductgrid" align="center" class="grey-table-border-grid">
                                                        </table>
                                                        <br></td>
                                                    </tr>
                                                  </tbody>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td valign="top" class="td-border-grid" style="text-align: left; " colspan="2"><table width="100%" cellspacing="0" cellpadding="2" border="0">
                                                  <tbody>
                                                    <tr>
                                                      <td><div align="left"><strong>Invoice Remarks: </strong>&nbsp;<span id="invoiceremarkspreview"></span></div></td>
                                                    </tr>
                                                    <tr>
                                                      <td><div align="left"><strong> Payment Remarks: </strong>&nbsp;<span id="paymentremarkspreview"></span></div></td>
                                                    </tr>
                                                  </tbody>
                                                </table></td>
                                              <td class="td-border-grid" style="text-align: left; "><div align="center"><font color="#ff0000">For <strong>RELYON SOFTECH LTD</strong></font> <br>
                                                  <br>
                                                  <br>
                                                  <span id="generatedbypreview">
                                                  <?  echo($dealername); ?>
                                                  </span></div></td>
                                            </tr>
                                          </tbody>
                                        </table></td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    <tr>
                                      <td  valign="top" style="text-align: center; padding-top:10px;"><div id="proceedprocessingdiv"></div></td>
                                    </tr>
                                      <tr id="previewbuttons"></tr>
                                  </table>
                                </div>
                              </div></td>
                                </table>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    </tr>
</table>
<script>
gettotalcustomercount();
</script>
<div id="seztaxuploaddiv" style="display:none;">
  <? include('../inc/seztaxuploaddiv.php'); ?>
</div>
<? } ?>
