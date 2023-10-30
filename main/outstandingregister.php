<?php
if($p_outstandingregister <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link href="../style/global.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/outstandingregister.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictfunction.php?dummy=<?php echo(rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<?php echo (rand());?>" ></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td ><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" class="active-leftnav">Report - Outstanding Register</td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Make A Report</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2"  >
                                    <tr>
                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FBF3DB">
                                          <tr>
                                            <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td width="29%" align="left" valign="top">As On  Date: </td>
                                                  <td width="71%" align="left" valign="top" ><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
                                                    <input type="hidden" name="flag" id="flag" value="true" />
                                                    <input type="hidden" name="category" id="category" value="<?php echo($pagelink) ?>" />
                                                    <input type="hidden" name="hiddentotalinvoices" id="hiddentotalinvoices" value="" />
                                                    <input type="hidden" name="hiddentotaloutstanding" id="hiddentotaloutstanding" value="" /></td>
                                                </tr>
                                                <tr >
                                                  <td align="left" valign="top" >Aged Above (Days):</td>
                                                  <td align="left" valign="top" ><input name="aged" type="text" class="swifttext-mandatory" id="aged" value="0" size="30" maxlength="5" autocomplete="off" /></td>
                                                </tr>
                                              </table></td>
                                            <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr >
                                                  <td colspan="2" valign="top" style="padding:0"></td>
                                                </tr>
                                                <tr >
                                                  <td width="29%" align="left" valign="top" >Sort By:</td>
                                                  <td width="71%" align="left" valign="top" ><select name="sortby" class="swiftselect" id="sortby"  style="width:200px">
                                                      <option value="age" selected="selected">Age</option>
                                                      <option value="outstandingamount">Outstanding Amount</option>
                                                    </select>
                                                    <br/>
                                                    </label></td>
                                                </tr>
                                                <tr >
                                                  <td align="left" valign="top">Sort:</td>
                                                  <td align="left" valign="top" ><select name="sort" class="swiftselect" id="sort"  style="width:200px">
                                                      <option value="asc" selected="selected">Ascending</option>
                                                      <option value="desc">Descending</option>
                                                    </select></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><div align="left" style="display:block;height:20px; padding-top:5px; " id="detailsdiv" >
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="87%" ><div style="border-top:dashed 1px #000000 ;" align="center"></div></td>
                                                    <td width="13%" ><div align="right"  onclick="displayDiv('1','filterdiv')" class="resendtext"><strong style=" padding-right:10 px">Advanced Options</strong></div></td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><div id="filterdiv" style="display:none;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                  <tr>
                                                    <td width="100%" valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" >
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                              <tr>
                                                                <td width="43%"><table width="99%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td width="9%" align="left" valign="top" >Text: </td>
                                                                      <td width="91%" colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="33" maxlength="150" class="swifttext"  autocomplete="off" value=""/>
                                                                        <span style="font-size:9px; color:#939393;">(Leave Empty for all)</span></td>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                  </table></td>
                                                                <td width="12%"><div align="right"><strong>Load Selection</strong>:</div></td>
                                                                <td width="11%" id="displayloadselection"><select name="loadselection" class="swiftselect" id="loadselection" style="width:100px;">
                                                                    <option value="default">DEFAULT</option>
                                                                  </select></td>
                                                                <td width="34%"><a onclick="outreceiptdisplayselection()"><strong class="select-resendtext">Go &#8250;&#8250; </strong></a>&nbsp;/&nbsp;<a onclick="outreceiptsearchsettings()"><strong class="select-resendtext">Save Current Selection &#8250;&#8250; </strong></a>&nbsp;/&nbsp;<a onclick="deleteselectedsettings()"><strong class="select-resendtext">Delete &#8250;&#8250; </strong></a> </span></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="60%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                              <tr valign="top" >
                                                                <td height="2" style="padding:3px">&nbsp;</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td width="42%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                          <tr>
                                                                            <td colspan="2"><table width="99%" height="257" border="0" cellpadding="3" cellspacing="0" style="border:solid 1px #004000">
                                                                                <tr>
                                                                                  <td align="left"><strong>Look in:</strong></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" id="databasefield0" value="slno"/>
                                                                                      Customer ID</label></td>
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
                                                                                  <td height="9px"></td>
                                                                                </tr>
                                                                              </table></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td style="padding:5px"><input name="cancelledinvoice" type="checkbox" id="cancelledinvoice" checked="checked"  /></td>
                                                                            <td ><label for="cancelledinvoice">Do not consider Cancelled  Invoices</label></td>
                                                                          </tr>
                                                                        </table></td>
                                                                      <td width="68%" valign="top" style="padding-left:3px"><table width="102%" border="0" cellspacing="0" cellpadding="6" style="border:solid 1px #cccccc;">
                                                                          <tr>
                                                                            <td colspan="2"><strong>Selections</strong>:</td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td width="39%" height="10" align="left" valign="top">Region:</td>
                                                                            <td width="61%" height="10" align="left" valign="top"><select name="region" class="swiftselect" id="region" style="width:180px;">
                                                                                <option value="">ALL</option>
                                                                                <?php 
											include('../inc/region.php');
											?>
                                                                              </select></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td height="10" align="left"> Branch:</td>
                                                                            <td align="left" valign="top"   height="10" ><select name="branch" class="swiftselect" id="branch" style="width:180px;">
                                                                                <option value="">ALL</option>
                                                                                <?php include('../inc/branch.php');?>
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
                                                                            <td align="left" valign="top"   height="10" ><select name="currentdealer" class="swiftselect" id="currentdealer" style="width:180px;">
                                                                                <option value="">ALL</option>
                                                                                <?php include('../inc/dealer-invoice.php');?>
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
                                                                            <td height="10" align="left">Invoice Series:</td>
                                                                            <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="series" class="swiftselect" name="series">
                                                                                <option value="" selected="selected">ALL</option>
                                                                                <option value="BKG">BKG</option>
                                                                                <option value="BKM">BKM</option>
                                                                                <option value="CSD">CSD</option>
                                                                                <option value="Online">Online</option>
                                                                              </select></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td height="10" align="left">Invoice Status:</td>
                                                                            <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="status" class="swiftselect" name="status">
                                                                                <option value="" selected="selected">ALL</option>
                                                                                <option value="ACTIVE">ACTIVE</option>
                                                                                <option value="EDITED">EDITED</option>
                                                                              </select></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td height="10" align="left">Receipt Status:</td>
                                                                            <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="receiptstatus" class="swiftselect" name="receiptstatus">
                                                                                <option value="" selected="selected">ALL</option>
                                                                                <option value="ACTIVE">ACTIVE</option>
                                                                                <option value="CANCELLED">CANCELLED</option>
                                                                              </select></td>
                                                                          </tr>
                                                                        </table></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                          <td width="40%" valign="top" style="padding-left:3px"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                              <tr>
                                                                <td colspan="4" valign="top" align="left"><strong>Products: </strong></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="4" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:124px; overflow:scroll">
                                                                    <?php include('../inc/productdetails.php'); ?>
                                                                  </div></td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left">Select: </td>
                                                                <td align="left"><strong>
                                                                  <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:75px"   >
                                                                    <option value="ALL"  selected="selected">ALL</option>
                                                                    <option value="NONE">NONE</option>
                                                                    <?php include('../inc/productgroup.php') ?>
                                                                  </select>
                                                                  </strong></td>
                                                                <td align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                                                      <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="3" valign="top" align="left"><strong>Items: </strong></td>
                                                              </tr>
                                                              <tr >
                                                                <td  colspan="3" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div  style="height:95px; overflow:scroll">
                                                                    <?php include('../inc/itemlist.php'); ?>
                                                                  </div></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="3" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="6%"><input type="checkbox" name="selectitem" id="selectitem" checked="checked" onchange="selectdeselectcommon('selectitem','itemarray[]')" /></td>
    <td width="94%"><label for="selectitem">Select All / None</label></td>
  </tr>
</table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2"></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="right" valign="middle" style="padding-right:3px;"></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                          </tr>
                                          <tr>
                                        </table></td>
                                    </tr>
                                    
                                      <td colspan="2" align="right" valign="middle" style="padding-right:5px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="67%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="33%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="searchfilter('');" />
                                              &nbsp;
                                              <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="filtertoexcel('toexcel');"/>
                                              &nbsp;
                                              <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);" /></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default'); getinvoicedetails(''); " style="cursor:pointer" class="grid-active-tabclass">All Time</td>
                                                  <td width="2">&nbsp;</td>
                                                  <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');displayoutstdingtotal()" style="cursor:pointer" class="grid-tabclass">Search Result</td>
                                                  <td width="2">&nbsp;</td>
                                                  <td width="140" align="center" ></td>
                                                  <td width="140" align="center" ></td>
                                                  <td width="140" align="center" ></td>
                                                  <td width="140" align="center" ></td>
                                                  <td><div id="gridprocessing"></div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td colspan="2"><form action="" method="post" name="filterform" id="filterform" onsubmit="return false;">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                        <tr class="header-line" >
                                          <td width="220px"><div id="tabdescription"></div></td>
                                          <td width="216px" style="text-align:center;"><span id="tabgroupgridwb1" ></span><span id="tabgroupgridwb2" ></span></td>
                                          <td width="296px" style="padding:0">&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:270px; width:925px; padding:2px;" align="center">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td><div id="tabgroupgridc1_1" align="center" ></div></td>
                                                </tr>
                                                <tr>
                                                  <td><div id="tabgroupgridc1link" align="left" > </div></td>
                                                </tr>
                                              </table>
                                              <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                            </div>
                                            <div id="tabgroupgridc2" style="overflow:auto;height:270px; width:925px; padding:2px; display:none;" align="center">
                                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td colspan="2"><div id="tabgroupgridc2_1" ></div></td>
                                                </tr>
                                                <tr>
                                                  <td><div id="tabgroupgridc2link" align="left"> </div></td>
                                                  <td><div id="invoicedetails" align="left"> </div></td>
                                                </tr>
                                              </table>
                                              <div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div>
                                            </div></td>
                                        </tr>
                                        <tr>
                                          <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr>
                                                <td width="12%" valign="top"><strong>Total Invoices:</strong></td>
                                                <td width="13%"><input name="totalinvoices" type="text" class="swifttext-readonly" id="totalinvoices" size="15" autocomplete="off" value="" style="text-align:right"/></td>
                                                <td width="15%" valign="top"><strong>Total Outstanding:</strong></td>
                                                <td width="16%"><input name="totaloutstanding" type="text" class="swifttext-readonly" id="totaloutstanding" size="15" autocomplete="off" value="" style="text-align:right"/></td>
                                                <td width="7%" valign="top">&nbsp;</td>
                                                <td width="11%">&nbsp;</td>
                                                <td width="11%" valign="top">&nbsp;</td>
                                                <td width="15%">&nbsp;</td>
                                              </tr>
                                              <tr>
                                                <td colspan="8" valign="top"><div style="display:none">
                                                    <div id="remarks1" style='background:#fff; width:530px'>
                                                      <table width="100%" border="0" cellspacing="4" cellpadding="0" style="border:solid 1px #DDEEFF">
                                                        <tr>
                                                          <td colspan="2" style="font-size:14px; color:#999999"><strong>Enter Outstanding Remarks1
                                                            <input type="hidden" name="invoiceslno1" id="invoiceslno1" />
                                                            </strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2" align="center"><textarea name="outstandingremarks1" id="outstandingremarks1" cols="62" rows="4"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2" align="center">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td width="60%" id="remarks1progress" height="35px">&nbsp;</td>
                                                          <td width="40%" align="right"><input name="update" type="button" class="swiftchoicebutton" id="update" value="Update" onclick="enterremarks('1');"/>
                                                            &nbsp;
                                                            <input name="cancel" type="button" class="swiftchoicebutton" id="cancel" value="Cancel" onclick="closecolorbox();"/></td>
                                                        </tr>
                                                      </table>
                                                    </div>
                                                  </div></td>
                                              </tr>
                                              <tr>
                                                <td colspan="8" valign="top"><div style="display:none">
                                                    <div id="remarks2" style='background:#fff; width:530px'>
                                                      <table width="100%" border="0" cellspacing="4" cellpadding="0" style="border:solid 1px #DDEEFF">
                                                        <tr>
                                                          <td colspan="2" style="font-size:14px; color:#999999"><strong>Enter Outstanding Remarks2</strong>
                                                            <input type="hidden" name="invoiceslno2" id="invoiceslno2" /></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2" align="center"><textarea name="outstandingremarks2" id="outstandingremarks2" cols="62" rows="4"></textarea></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2" align="center">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td width="60%" id="remarks2progress" height="35px">&nbsp;</td>
                                                          <td width="40%" align="right"><input name="update" type="button" class="swiftchoicebutton" id="update" value="Update" onclick="enterremarks('2');"/>
                                                            &nbsp;
                                                            <input name="cancel1" type="button" class="swiftchoicebutton" id="cancel1" value="Cancel" onclick="closecolorbox();"/></td>
                                                        </tr>
                                                      </table>
                                                    </div>
                                                  </div></td>
                                              </tr>
                                            </table></td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                </table>
                              </form></td>
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
<?php
 } ?>
