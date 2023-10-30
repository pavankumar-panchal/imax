<?php
if($p_matrixbulkprintinvoice <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/matrixbulkprinting.js?dummy=<?php echo (rand());?>"></script>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" class="active-leftnav">Report - Bulk Print (Invoices)</td>
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
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                    <tr>
                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FBF3DB" >
                                          <tr>
                                            <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                <tr >
                                                  <td width="22%" align="left" valign="top">From Date: </td>
                                                  <td width="78%" align="left" valign="top"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" />
                                                    <input type="hidden" name="onlineslno" id="onlineslno" value="" />
                                                    <input type="hidden" name="category" id="category" value="<?php echo($pagelink) ?>" />
                                                    <input type="hidden" name="tabvalue" id="tabvalue" value="" /></td>
                                                </tr>
                                              </table></td>
                                            <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr >
                                                  <td colspan="2" valign="top" style="padding:0"></td>
                                                </tr>
                                                <tr >
                                                  <td width="22%" align="left" valign="top" >To Date:</td>
                                                  <td width="78%" align="left" valign="top" ><label for="sto"></label>
                                                    <label for="spp">
                                                      <input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
                                                      <br/>
                                                    </label></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><div align="left" style="display:block;height:20px; padding-top:5px; " id="detailsdiv" >
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="87%" ><div style="border-top:dashed 1px #000000;" align="center"></div></td>
                                                    <td width="13%" ><div align="right"  onclick="displayDiv('1','filterdiv')" class="resendtext"><strong style=" padding-right:10 px">Advanced Options</strong></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2">&nbsp;</td>
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
                                                                        <span style="font-size:8px; color:#939393;">(Leave Empty for all)</span></td>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                  </table></td>
                                                                <td width="12%"><div align="right"><strong>Load Selection</strong>:</div></td>
                                                                <td width="11%" id="displayloadselection"><select name="loadselection" class="swiftselect" id="loadselection" style="width:100px;">
                                                                    <option value="default">DEFAULT</option>
                                                                  </select></td>
                                                                <td width="34%"><a onclick="displayselection()"><strong class="select-resendtext">Go &#8250;&#8250; </strong></a>&nbsp;/&nbsp;<a onclick="searchsettings()"><strong class="select-resendtext">Save Current Selection &#8250;&#8250; </strong></a>&nbsp;/&nbsp;<a onclick="deleteselectedsettings()"><strong class="select-resendtext">Delete &#8250;&#8250; </strong></a> </span></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="60%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                              <tr valign="top" >
                                                                <td width="100%" height="2" style="padding:1px">&nbsp;</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td width="42%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                          <tr>
                                                                            <td colspan="2"><table width="99%" height="319" border="0" cellpadding="3" cellspacing="0" style="border:solid 1px #004000">
                                                                                <tr>
                                                                                  <td align="left"><strong>Look in:</strong></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" id="slno" value="slno"/>
                                                                                      Customer ID</label></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" id="businessname" value="businessname" checked="checked"/>
                                                                                      Business Name</label></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" value="contactperson" id="contactperson" />
                                                                                      Contact Person</label></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" id="place" value="place" />
                                                                                      Place</label></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" value="phone" id="phone" />
                                                                                      Phone</label>
                                                                                    / Cell</td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" value="emailid" id="emailid" />
                                                                                      Email</label></td>
                                                                                </tr>
                                                                                <tr >
                                                                                  <td style="border-top:solid 1px #999999"  height="1" align="left"></td>
                                                                                </tr>
                                                                                <!-- <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" value="cardid" id="cardid" />
                                                                                      PIN Serial Number</label></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" value="scratchnumber" id="scratchnumber" />
                                                                                      PIN Number</label></td>
                                                                                </tr> -->
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" value="invoiceno" id="invoiceno" />
                                                                                      Invoice No</label></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td align="left"><label>
                                                                                      <input type="radio" name="databasefield" value="invoiceamt" id="invoiceamt" />
                                                                                      Invoice Amount</label></td>
                                                                                </tr>
                                                                                <tr>
                                                                                  <td height="9px"></td>
                                                                                </tr>
                                                                              </table></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td style="padding:5px"><input name="cancelledinvoice" type="checkbox" id="cancelledinvoice" checked="checked" /></td>
                                                                            <td ><label for="cancelledinvoice">Do not consider Cancelled  Invoices</label></td>
                                                                          </tr>
                                                                           <tr>
                                                                            <td style="padding:5px"><input name="letterhead" type="checkbox" id="letterhead" /></td>
                                                                            <td ><label for="letterhead">Letterhead Print</label></td>
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
                                                                            <td height="10" align="left">Invoice Region:</td>
                                                                            <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="series" class="swiftselect" name="series">
                                                                                <option value="" selected="selected">ALL</option>
                                                                                <option value="BKG">BKG</option>
                                                                                <option value="BKM">BKM</option>
                                                                                <option value="CSD">CSD</option>
                                                                              </select></td>
                                                                          </tr>
                                                                          
                                                                           <tr>
                                                                            <td height="10" align="left">Invoice Series:</td>
                                                                            <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="seriesnew" class="swiftselect" name="seriesnew">
                                                                                <option value="" selected="selected">ALL</option>
                                                                                <option value="GU">GU</option>
                                                                                <option value="UP">UP</option>
                                                                                <option value="TL">TL</option>
                                                                                <option value="RJ">RJ</option>
                                                                                <option value="WB">WB</option>
                                                                                <option value="MH">MH</option>
                                                                                <option value="TN">TN</option>
                                                                                <option value="ML">ML</option>
                                                                              </select></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td height="10" align="left">Invoice Status:</td>
                                                                            <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="status" class="swiftselect" name="status">
                                                                                <option value="" selected="selected">ALL</option>
                                                                                <option value="ACTIVE">ACTIVE</option>
                                                                                <option value="EDITED">EDITED</option>
                                                                                <option value="CANCELLED">CANCELLED</option>
                                                                              </select></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td height="10" align="left">Purchase Type:</td>
                                                                            <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="purchasetype" class="swiftselect" name="purchasetype">
                                                                                <option value="" selected="selected">ALL</option>
                                                                                <option value="new">NEW</option>
                                                                                <option value="updation">UPDATION</option>
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
                                                                <td colspan="3" valign="top" align="left"><strong>Products: </strong></td>
                                                              </tr>
                                                              <tr >
                                                                <td  colspan="3" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:200px; overflow:scroll" >
                                                                    <?php include('../inc/matrixitemlist.php'); ?>
                                                                  </div></td>
                                                              </tr>
                                                              <tr>
                                                                <td colspan="3" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                <tr>
                                                                  <td width="6%"><input type="checkbox" name="selectmatrixitem" id="selectmatrixitem" checked="checked" onchange="selectdeselectcommon('selectmatrixitem','matrixarray[]')" /></td>
                                                                  <td width="94%"><label for="selectmatrixitem">Select All / None</label></td>
                                                                </tr>
                                                              </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="right" valign="middle" style="padding-right:3px;"></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:5px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="67%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="33%" align="right" valign="middle"><span style="padding:10px">
                                              <input name="hiddenslno" type="hidden" id="hiddenslno" value="" />
                                              </span>
                                              <input name="searchcount" id="searchcount" type="hidden" value="" />
                                              <input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="searchfilter();" />
                                              &nbsp;&nbsp;
                                              <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);" /></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td colspan="4" align="right" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="2">&nbsp;</td>
                                              <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab6('1','tabgroupgrid','&nbsp; &nbsp;Search Results'); " style="cursor:pointer" class="grid-tabclass">Search Result</td>
                                              <td width="2">&nbsp;</td>
                                              <td width="2">&nbsp;</td>
                                              <td width="2">&nbsp;</td>
                                              <td width="2">&nbsp;</td>
                                              <td><div id="gridprocessing"> </div></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                      <tr>
                                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                            <tr class="header-line" >
                                              <td width="220px"><div id="tabdescription">&nbsp;</div></td>
                                              <td width="216px" style="text-align:center;"><span id="tabgroupgridwb1" ></span><span id="tabgroupgridwb2" ></span><span id="tabgroupgridwb3" ></span><span id="tabgroupgridwb4" ></span><span id="tabgroupgridwb5" ></span><span id="tabgroupgridwb6" ></span></td>
                                              <td width="296px" style="padding:0">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td colspan="3" align="center" valign="top"><div id="tabgroupgridc6" style="overflow:auto;height:270px; width:925px; padding:2px; display:block;" align="center">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td colspan="2"><div id="tabgroupgridc6_1" ></div></td>
                                                    </tr>
                                                    <tr>
                                                      <td><div id="tabgroupgridc6link" align="left"> </div></td>
                                                      <td>&nbsp;</td>
                                                    </tr>
                                                  </table>
                                                  <div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div>
                                                </div></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td colspan="4" height="10px"></td>
                                </tr>
                                <tr>
                                  <td width="14%" style="padding:10px" ><label for="selectall">
                                      <input type="checkbox" name="selectall" id="selectall" onclick="selectanddeselect('all')"/>
                                      Select All</label></td>
                                  <td width="12%" style="padding:10px" ><div align="right">Search Count :</div></td>
                                  <td width="51%" style="padding:10px" ><input name="searchcountvalue" type="text" class="swifttext-readonly" id="searchcountvalue" size="15" autocomplete="off" value="0" readonly="readonly" style="text-align:right"/></td>
                                  <td width="23%" style="padding:10px" ><div align="center">
                                      <input name="view2" type="button" class="swiftchoicebuttonbig" id="view2" value="Generate PDF" onclick="formsubmit();" />
                                      <br><br>
                                      <input name="view3" type="button" class="swiftchoicebuttonbig" id="view3" value="Duplicate Copy" onclick="formsubmitduplicate();" />
                                    </div></td>
                                </tr>
                                <tr>
                                  <td colspan="4" height="10px"></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td colspan="2"><div style="display:none">
                                <form id="detailsform" name="detailsform" method="post" action="" onsubmit="return false">
                                  <input type="hidden" name="productslno" id="productslno"  value=" "/>
                                  <div style="display:none">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><div id="productdetailsgrid" style='background:#fff;width:909px;'>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC">
                                              <tr class="header-line">
                                                <td width="45%"><span style="padding-left:4px;">Invoice Details</span></td>
                                                <td width="24%"><span id="productdetailsgridwb1" style="text-align:center">&nbsp;</span></td>
                                                <td width="31%"><div align="right"></div></td>
                                              </tr>
                                              <tr>
                                                <td colspan="3" align="center"><div style="overflow:auto;padding:0px; height:290px; width:909px; ">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td align="center"><div id="productdetailsgridc1_1" > </div></td>
                                                      </tr>
                                                      <tr>
                                                        <td ><div id="productdetailsgridc1link" style="height:20px;  padding:2px;" align="centre"> </div></td>
                                                      </tr>
                                                    </table>
                                                  </div></td>
                                              </tr>
                                            </table>
                                            <div id="productdetailsresultgrid" style="overflow:auto; display:none; height:290px; width:909px; padding:2px;" align="center">&nbsp;</div>
                                            <div align="right" style="padding-top:15px; padding-right:25px"><input type="button" value="Close" id="closecolorboxbutton"  onclick="$().colorbox.close();" class="swiftchoicebutton"/> </div>
                                          </div></td>
                                      </tr>
                                    </table>
                                  </div>
                                </form>
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
<?php } ?>
