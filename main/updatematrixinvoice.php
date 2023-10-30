<?php
if($p_managematrixinvoice <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
$query = "select min(left(inv_matrixinvoicenumbers.createddate,10)) as fromdate from inv_matrixinvoicenumbers";
$resultfetch = runmysqlqueryfetch($query);

$fromdate = changedateformat($resultfetch['fromdate']);
$enabledid = array('138','99','112');
$enabledcnl = array('146','56','192','123');
$enablededit = array('146','56','192','138','99','112');

?>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo(rand());?>"  />
<link href="../style/main.css?dummy=<?php echo(rand());?>" rel=stylesheet >
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/updatematrixinvoice.js?dummy=<?php echo(rand());?>"></script>
<script language="javascript" src="../functions/getdistrictfunction.php?dummy=<?php echo(rand());?>"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<?php echo(rand());?>" ></script>
<input type="hidden" value="<?php echo $_GET['invoiceno'];?>" id="invoicevalue"/>
<?php
   $invoicecreated_date = date('Y-m-d');
   $query2 = "SELECT igst_rate,cgst_rate,sgst_rate from gst_rates where from_date <= '".$invoicecreated_date."' AND to_date >= '".$invoicecreated_date."'";
   $fetchrate = runmysqlqueryfetch($query2);
   //$gst_rate = $fetchrate['rate'];
   
   $igst_tax_rate = $fetchrate['igst_rate'];
   $cgst_tax_rate = $fetchrate['cgst_rate'];
   $sgst_tax_rate = $fetchrate['sgst_rate'];
?>
<input type="hidden" value="<?php echo $igst_tax_rate;?>" id="igstrate" name="igstrate"/>
<input type="hidden" value="<?php echo $cgst_tax_rate;?>" id="cgstrate" name="cgstrate"/>
<input type="hidden" value="<?php echo $sgst_tax_rate;?>" id="sgstrate" name="sgstrate"/>

<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="text-align:left">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" class="active-leftnav">All Invoicing</td>
              </tr>
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="87%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="customerselectionprocess" align="left" style="padding:0"></td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="refreshinvoicenoarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg"   alt="Refresh customer" border="0" align="middle" title="Refresh customer Data"  /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"onkeyup="customersearch(event);"  autocomplete="off"  style="width:187px;"/>
                          <span style="display:none">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled"/>
                          </span>
                          <div id="detailloadcustomerlist">
                            <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:192px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();"  >
                            </select>
                          </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td colspan="2"><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                      <td width="55%" id="totalcount" align="left">&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
              <tr>
                <td colspan="2" >&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="25%" align="top" class="active-leftnav">Manage Dealer Invoices</td>
                           <td width="34%" align="top"><div align="right"> <font color="#FF6B24"></font></div></td>
                            <td width="23%" valign="top"><div align="left" style="padding:2px"></td> 
                             <td width="2%" valign="top" >&nbsp;
                              <input name="search" type="submit" class="swiftchoicebuttonbig" id="search" value="Advanced Search"  onclick="displayDiv('1','filterdiv')"  />
                            </td> 
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td height="5%">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="padding-top:3px"><div id="filterdiv" style="display:none;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                            <tr>
                              <td valign="top"><div>
                                  <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td width="100%" align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Search Option</td>
                                      </tr>
                                      <tr>
                                        <td valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" 
                                        bgcolor="#FFD3A8" style="border:dashed 1px #545429">
                                            <tr>
                                              <td width="63%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4" style=" border-right:1px solid #CCCCCC">
                                                  <tr>
                                                    <td colspan="4" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="9%" align="left" valign="middle" >Text: </td>
                                                          <td width="91%" colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="32" maxlength="60" class="swifttext"  autocomplete="off" value=""/>
                                                            <span style="font-size:6px; color:#999999; padding:1px">(Leave Empty for all)</span></td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr valign="top" >
                                                    <td height="2" colspan="2" style="padding:1px">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2" align="left" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="31%" valign="top"><table width="99%" border="0" cellspacing="0" cellpadding="3" style="border:solid 1px #004000">
                                                              <tr>
                                                                <td align="left"><strong>Look in:</strong></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                                    Business Name</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="contactperson" id="databasefield3" />
                                                                    Contact Person</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield5" value="place" />
                                                                    Place</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="phone" id="databasefield4" />
                                                                    Phone</label>
                                                                  / Cell</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="emailid" id="databasefield6" />
                                                                    Email</label></td>
                                                              </tr>
                                                              <tr >
                                                                <td style="border-top:solid 1px #999999"  height="1" align="left"></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="cardid" id="databasefield9" />
                                                                    PIN Serial Number</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="scratchnumber" id="databasefield7" />
                                                                    PIN Number</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><label>
                                                                    <input type="radio" name="databasefield" value="invoiceno" id="databasefield11" />
                                                                    Invoice No</label></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="9px"></td>
                                                              </tr>
                                                            </table></td>
                                                          <td width="69%" valign="top" style="padding-left:3px"><table width="102%" border="0" cellspacing="0" cellpadding="6" style="border-left:solid 1px #cccccc; border-bottom:solid 1px #cccccc; border-top:solid 1px #cccccc ">
                                                              <tr>
                                                                <td colspan="2"><strong>Selections</strong>:</td>
                                                              </tr>
                                                              <tr>
                                                                <td width="39%" height="10" align="left" valign="top">Region:</td>
                                                                <td width="61%" height="10" align="left" valign="top"><select name="region2" class="swiftselect" id="region2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php 
											include('../inc/region.php');
											?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" valign="top" height="10" >State:</td>
                                                                <td align="left" valign="top" height="10"><select name="state2" class="swiftselect" id="state2" onchange="getdistrictfilter('districtcodedisplaysearch',this.value);" onkeyup="getdistrictfilter('districtcodedisplaysearch',this.value);" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php include('../inc/state.php'); ?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> District:</td>
                                                                <td  valign="top"  id="districtcodedisplaysearch" height="10" align="left"><select name="district2" class="swiftselect" id="district2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Dealer:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="currentdealer2" class="swiftselect" id="currentdealer2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php include('../inc/firstdealer.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Branch:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="branch2" class="swiftselect" id="branch2" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php include('../inc/branch.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left"> Generated by:</td>
                                                                <td align="left" valign="top"   height="10" ><select name="generatedby" class="swiftselect" id="generatedby" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <?php include('../inc/generatedby.php');?>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left">Series:</td>
                                                                <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="series" class="swiftselect" name="series">
                                                                    <option value="" selected="selected">ALL</option>
                                                                    <option value="BKG">BKG</option>
                                                                    <option value="BKM">BKM</option>
                                                                    <option value="CSD">CSD</option>
                                                                    <option value="Online">Online</option>
                                                                  </select></td>
                                                              </tr>
                                                              <tr>
                                                                <td height="10" align="left">Status:</td>
                                                                <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="status" class="swiftselect" name="status">
                                                                    <option value="" selected="selected">ALL</option>
                                                                    <option value="ACTIVE">ACTIVE</option>
                                                                    <option value="EDITED">EDITED</option>
                                                                    <option value="CANCELLED">CANCELLED</option>
                                                                  </select></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr valign="top" >
                                                    <td width="22%" align="left" ><strong>From Date</strong>: </td>
                                                    <td width="78%" align="left"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo($fromdate); ?>" readonly="readonly" /></td>
                                                  </tr>
                                                  <tr valign="top" >
                                                    <td align="left" ><strong>To Date</strong>:</td>
                                                    <td align="left"><label for="sto"></label>
                                                      <label for="spp">
                                                        <input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
                                                        <br/>
                                                      </label></td>
                                                  </tr>
                                                </table></td>
                                              <td width="37%" valign="top" style="padding-left:3px"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                  <tr>
                                                    <td colspan="4" valign="top" style="padding:0"></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" valign="top" align="left"><strong>Products: </strong></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:230px; overflow:scroll">
                                                        <?php include('../inc/productdetails.php'); ?>
                                                      </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td width="20%" align="left">Select: </td>
                                                    <td width="50%" align="left"><strong>
                                                      <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:140px" >
                                                        <option value="ALL"  selected="selected">ALL</option>
                                                        <option value="NONE">NONE</option>
                                                       <?php include('../inc/productgroup.php') ?>
                                                      </select>
                                                      </strong></td>
                                                    <td width="30%" align="left"></td>
                                                  </tr>
                                                  <tr >
                                                    <td  colspan="3" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td width="21%">&nbsp;</td>
                                                          <td width="79%"><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                                          <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                  <tr>
                                                    <td width="57%" height="35"><div id="filter-form-error"></div></td>
                                                    <td width="43%"><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value="Search" onclick="searchdealerarray();" />
                                                      &nbsp;
                                                      <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);">
                                                      &nbsp;
                                                      <input name="close" type="button" class="swiftchoicebutton" id="close" value="Close" onclick="document.getElementById('filterdiv').style.display='none';" /></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table>
                                  </form>
                                </div></td>
                            </tr>
                          </table>
                        </div></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td align="left" class="header-line" style="padding:0"> Edit Invoice</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td width="100%" valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td ><div id="displaydetails1" style="display:none">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                  <tr bgcolor="#f7faff">
                                                    <td width="18%" align="left" valign="top"><strong>Invoice No:</strong></td>
                                                    <td width="32%"  align="left" valign="top" bgcolor="#f7faff" id="invoicenumber" style="text-align:left" ></td>
                                                    <td width="13%"  align="left" valign="top" bgcolor="#f7faff"  style="text-align:left" ><font color="#FF0000"><strong>STATUS:</strong></font></td>
                                                    <td width="37%"  align="left" valign="top" bgcolor="#f7faff" id="statusresult" style="text-align:left" ></td>
                                                  </tr>
                                                  <tr bgcolor="#f7faff">
                                                    <td width="18%" align="left" valign="top"><strong>Company Name:</strong></td>
                                                    <td width="32%"  align="left" valign="top" bgcolor="#f7faff" id="displaycompanyname" style="text-align:left" ></td>
                                                    <td width="13%"  align="left" valign="top" bgcolor="#f7faff"  style="text-align:left" ><strong>Remarks:</strong></td>
                                                    <td width="37%"  align="left" valign="top" bgcolor="#f7faff" id="remarksdisplay" style="text-align:left" ></td>
                                                  </tr>
                                                </table>
                                              </div>
                                              <div id="displaydetails2" style="display:block">
                                                <table width="100%" border="0"  cellpadding="0" cellspacing="0">
                                                  <tr height="280px">
                                                    <td height="200px" class="textmsg" align="center">Select A Invoice </td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                          </tr>
                                          <tr>
                                            <td height="35px"><div id="form-error"></div></td>
                                          </tr>
                                          <tr>
                                            <td><div id="displaydiv" style="display:none">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                        <tr>
                                                          <td width="17%"><div align="right"><a   onclick="resendmatrixinvoice(document.getElementById('lastslno').value);"  class="r-text" >Resend Invoice &gt;&gt;</a></div></td>
                                                          <td width="15%"><div align="right"><a   onclick="viewmatrixinvoice(document.getElementById('lastslno').value)"  class="r-text" >View Invoice &gt;&gt;</a></div></td>
                                                          <td width="15%">
                                                            <select name="cnlrsn" id="cnlrsn" class="swiftselect">
                                                              <option value="">Select Cancel Reason</option>
                                                              <option value="1">Duplicate</option>
                                                              <option value="2">Data entry mistake</option>
                                                              <option value="3">Order Cancelled</option>
                                                              <option value="4">Others</option>
                                                            </select></td>
                                                          <td width="22%"><div align="center">
                                                          <?php if(in_array($userid, $enabledcnl, true) ) { ?>
                                                              <input name="cancelinvoice" type="button" class= "swiftchoicebuttonbignew" id="cancelinvoice" value="Invoice Cancellation" onclick="validaterequest('einvcancel')" />
                                                            <?php } elseif(in_array($userid, $enabledid, true) ) { ?>
                                                              <input name="cancelinvoice" type="button" class= "swiftchoicebuttonbignew" id="cancelinvoice" value="Invoice Cancellation" onclick="validaterequest('cancel')" /><?php } ?>
                                                            </div></td>
                                                          <td width="24%">
                                                            <div align="left">
                                                            <?php if(in_array($userid, $enablededit, true) ) { ?>
                                                              <input name="edit" type="button" class= "swiftchoicebuttonbig" id="edit" value="Invoice Editing" onclick="editform()" />
                                                              <?php } ?>
                                                              <input type="hidden" name="lastslno" id="lastslno" value=""  />
                                                              <input type="hidden" name="productcodevalues" id="productcodevalues" value=""  />
                                                              <input type="hidden" name="productquantityvalues" id="productquantityvalues" value=""  />
                                                              <input type="hidden" name="dealervalue" id="dealervalue" value=""  />
                                                              <input type="hidden" name="irnstatus" id="irnstatus" value=""  />
                                                            </div></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td height="10px" ></td>
                                                  </tr>
                                                  <tr>
                                                    <td ><span style="color:red; font-size:12px">Cancel Reason option is only for B2B /SEZ customers.</span></td>
                                                  </tr>
                                                  <tr>
                                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-top:solid 1px #C4E1FF">
                                                        <tr>
                                                          <td colspan="3"><div >
                                                              <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                <tr >
                                                                  <td width="17%" height="21px" valign="middle" ><strong>Invoice Details:&nbsp;</strong></td>
                                                                  <td  colspan="2" valign="middle" ><div id="displaybutton" style="display:none" >
                                                                      <input name="update" type="button" class= "swiftchoicebuttonbig" id="update" value="Finish Editing" onclick="validaterequest('edit')"  />
                                                                      &nbsp;&nbsp;
                                                                      <input name="delete" type="button" class= "swiftchoicebuttonbig" id="delete" value="Cancel Editing" onclick="canceleditform();enablebuttontype();" />
                                                                    </div></td>
                                                                  <td width="15%">&nbsp;</td>
                                                                </tr>
                                                              </table>
                                                            </div></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="3"  id="invoicedetailsdisplay">&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td><div style="display:none">
                                <form action="" method="post" name="colorform1" id="colorform1" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div  id='inline_example1' style='background:#fff; width:550px'>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #DDEEFF">
                                            <tr>
                                              <td colspan="2"></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td valign="top"><strong>Reason for Cancellation :</strong></td>
                                              <td><textarea name="cancelremarks" cols="60" class="swifttextareanew" id="cancelremarks" rows="4" style="resize: none;"></textarea></td>
                                            </tr>
                                            <tr>
                                              <td align="left" id="form-error1" height="35px"></td>
                                              <td align="right">
                                              <?php if(in_array($userid, $enabledcnl, true) ) { ?>
                                                <input type="button" name="cancelbutton" class="swiftchoicebuttonbignew" value="Confirm Cancellation" id="cancelbutton" onclick="formsubmit('einvcancel')"/>
                                              <?php } elseif(in_array($userid, $enabledid, true) ) { ?>
                                                <input type="button" name="cancelbutton" class="swiftchoicebuttonbignew" value="Confirm Cancellation" id="cancelbutton" onclick="formsubmit('cancel')"/><?php } ?>
                                                &nbsp;&nbsp;
                                                <input type="button" value="Close" id="closepreviewbutton2"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td><div style="display:none">
                                <form action="" method="post" name="colorform2" id="colorform2" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div  id='inline_example2' style='background:#fff; width:550px'>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #DDEEFF">
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td valign="top"><strong>Reason for Editing :</strong></td>
                                              <td><textarea name="editremarks" cols="60" class="swifttextareanew" id="editremarks" rows="4" style="resize: none;"></textarea></td>
                                            </tr>
                                            <tr>
                                              <td align="left" id="form-error2" height="35px"></td>
                                              <td align="right"><input type="button" name="editbutton" class="swiftchoicebuttonbig" value="Confirm Editing" id="editbutton" onclick="formsubmit('edit')"/>
                                                &nbsp;&nbsp;
                                                <input type="button" value="Close" id="closepreviewbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td></td>
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
<script>
refreshinvoicenoarray();
</script>
<?php }?>

