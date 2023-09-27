<?
if($p_customerpendingrequest <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
}
 
else 
{	
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/cusprofileupdate.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictjs.php?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictlistjs.php?dummy=<? echo (rand());?>"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="text-align:left" >
  <tr>
    <td valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  >
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3"  >
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">Customer Profile Update Details</td>
                            <td width="40%">&nbsp;</td>
                            <td width="33%" align="left" >&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="3" height="9px"></td>
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
                      <td><form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                  <tr>
                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                        <tr>
                                          <td width="2%" height="25" class="producttabheadnone"></td>
                                          <td width="18%" onclick="tabopen5('1','tabg1')" class="producttabheadactive" id="tabg1h1" style="cursor:pointer;"><div align="center"><strong>General Details</strong></div></td>
                                          <td width="2%" class="producttabheadnone"></td>
                                          <td width="18%" onclick="tabopen5('2','tabg1')" class="producttabhead" id="tabg1h2" style="cursor:pointer;"><div align="center"><strong>Contact Details</strong></div></td>
                                          <td width="2%" class="producttabheadnone">&nbsp;</td>
                                          <td width="18%" class="producttabhead" ></td>
                                          <td width="2%" class="producttabheadnone">&nbsp;</td>
                                          <td width="18%"  class="producttabhead" >&nbsp;</td>
                                          <td width="2%" class="producttabheadnone">&nbsp;</td>
                                          <td width="18%"  class="producttabhead">&nbsp;</td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                  <tr>
                                    <td><div style="display:block; "  align="justify" id="tabg1c1" >
                                        <table width="100%" border="0" cellspacing="0" cellpadding="2" class="productcontent" height="370px" >
                                          <tr bgcolor="#f7faff">
                                            <td width="77%" align="right" valign="top" bgcolor="#f7faff" style="padding-top:5px; padding-left:5px; padding-right:5px"><div align="right">
                                                <input type="checkbox" name="validategeneral" id="validategeneral" />
                                              </div>
                                              </label></td>
                                            <td width="23%" align="left" valign="top" bgcolor="#f7faff" style="padding-top:5px; padding-left:5px; padding-right:5px"><div align="left">General details are verified</div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                                <tr>
                                                  <td  width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-right:1px solid #308ebc">
                                                      <tr>
                                                        <td width="27%" valign="top" bgcolor="#f7faff" align="left">Company:</td>
                                                        <td width="73%" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td align="left"><input name="newbusinessname" type="text" class="swifttext-mandatory" id="newbusinessname" size="45" maxlength="200"  autocomplete="off" disabled="disabled" />
                                                                <input type="hidden" name="lastslno" id="lastslno" />
                                                                <input type="hidden" name="cuslno" id="cuslno" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td align="left" ><div id="display1" style="display:none">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_businessname" style="font-weight:bold;font-size:9px" height="15">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td valign="top" bgcolor="#EDF4FF" align="left">Address:</td>
                                                        <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td ><input type="text" id="newaddress" class="swifttext"  name="newaddress" size="45" maxlength="200" autocomplete="off" disabled="disabled" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td ><div id="display2" style="display:none">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_address" style="font-weight:bold;font-size:9px"  align="left" height="35"></td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#edf4ff">
                                                        <td valign="top" bgcolor="#F7FAFF" align="left">Place:</td>
                                                        <td valign="top" bgcolor="#F7FAFF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td><input name="newplace" type="text" class="swifttext-mandatory" id="newplace" size="45" maxlength="100" autocomplete="off" disabled="disabled" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div style="display:none" id="display3">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_place" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td valign="top" bgcolor="#EDF4FF" align="left">State:</td>
                                                        <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td><select name="newstate" class="swiftselect-mandatory" id="newstate" onchange="getdistrict('districtcodedisplay',this.value);" onkeyup="getdistrict('districtcodedisplay',this.value);"  style="width:200px;" disabled="disabled">
                                                                  <option value="">Select A State</option>
                                                                  <? include('../inc/state.php'); ?>
                                                                </select></td>
                                                            </tr>
                                                            <tr>
                                                              <td ><div style="display:none" id="display4">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_state" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#edf4ff">
                                                        <td valign="top" bgcolor="#F7FAFF" align="left">District:</td>
                                                        <td valign="top" bgcolor="#F7FAFF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td id="districtcodedisplay" align="left"><select name="newdistrict" class="swiftselect-mandatory" id="newdistrict" style="width:200px;" disabled="disabled">
                                                                  <option value="">Select A State First</option>
                                                                </select></td>
                                                            </tr>
                                                            <tr>
                                                              <td ><div style="display:none" id="display5">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_district" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td valign="top" bgcolor="#EDF4FF" align="left">Pincode:</td>
                                                        <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td align="left"><input name="newpincode" type="text" class="swifttext" id="newpincode" size="45" maxlength="6"  autocomplete="off" disabled="disabled"/></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div style="display:none" id="display6">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_pincode" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td align="left" valign="top" bgcolor="#f7faff">STD code:</td>
                                                        <td align="left" valign="top" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td align="left"><input name="newstdcode" type="text" class="swifttext" id="newstdcode" size="45" maxlength="100"  autocomplete="off" disabled="disabled"/></td>
                                                            </tr>
                                                            <tr>
                                                              <td ><div style="display:none" id="display7">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_stdcode" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td align="left" valign="top" bgcolor="#EDF4FF" ><label> Request By:</label></td>
                                                        <td align="left" valign="top" bgcolor="#EDF4FF" ><input name="requestby" type="text" class="swifttext" id="requestby" size="45" autocomplete="off" style="background:#FEFFE6;" readonly="readonly" /></td>
                                                      </tr>
                                                          <tr>
                                                              <td align="left" valign="top" bgcolor="#EDF4FF">Old Business Name[Company]:</td>
                                                              <td align="left" valign="top" bgcolor="#EDF4FF" ><input name="oldbusinessname" type="text" class="swifttext" id="oldbusinessname" size="45" autocomplete="off" style="background:#FEFFE6;" /></td>
                                                          </tr>

                                                      <tr bgcolor="#EDF4FF">
                                                        <td align="left" valign="top" bgcolor="#f7faff" >Request date/time:</td>
                                                        <td height="20" align="left" valign="top" bgcolor="#f7faff" id="createddate">Not Available </td>
                                                      </tr>
                                                    </table></td>
                                                  <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                      <tr bgcolor="#f7faff">
                                                        <td width="26%" align="left" valign="top" bgcolor="#EDF4FF">Fax:</td>
                                                        <td width="74%" align="left" valign="top" bgcolor="#EDF4FF"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td><input name="newfax" type="text" class="swifttext" id="newfax" size="45" maxlength="100" autocomplete="off" disabled="disabled" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div style="display:none" id="display8">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_fax" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td valign="top" bgcolor="#f7faff" align="left">Remarks:</td>
                                                        <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td ><input type="text" id="newremarks" class="swifttext"  name="newremarks" size="45" maxlength="500" autocomplete="off" disabled="disabled" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td ><div style="display:none" id="display9">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_remarks" style="font-weight:bold;font-size:9px" height="12" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td valign="top" bgcolor="#EDF4FF" align="left">Website:</td>
                                                        <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td align="left"><input name="newwebsite" type="text" class="swifttext" id="newwebsite" size="45" maxlength="100" autocomplete="off" disabled="disabled" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div style="display:none" id="display10">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_website" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#edf4ff">
                                                        <td valign="top" bgcolor="#f7faff" align="left">Type:</td>
                                                        <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td align="left"><select name="newtype" class="swiftselect" id="newtype" style="width:200px" disabled="disabled">
                                                                  <option value="" selected="selected">Type Selection</option>
                                                                  <? 
											include('../inc/custype.php');
											?>
                                                                </select></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div style="display:none" id="display11">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_type" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td valign="top" bgcolor="#EDF4FF" align="left">Category:</td>
                                                        <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td align="left"><select name="newcategory" class="swiftselect" id="newcategory"  style="width:200px" disabled="disabled">
                                                                  <option value="">Category Selection</option>
                                                                  <? 
											include('../inc/category.php');
											?>
                                                                </select></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div style="display:none" id="display12">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_category" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#edf4ff">
                                                        <td valign="top" bgcolor="#f7faff" align="left">Company Closed:</td>
                                                        <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td align="left"><select name="newcompanyclosed" class="swiftselect" id="newcompanyclosed" style="width:100px;" disabled="disabled">
                                                                  <option value="yes">Yes</option>
                                                                  <option value="no">No</option>
                                                                </select>
                                                                <input type="hidden" id="requesthiddenfrom" name="requesthiddenfrom" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td><div style="display:none" id="display13">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_companyclosed" style="font-weight:bold;font-size:9px" height="15" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td align="left" valign="top" bgcolor="#EDF4FF" >
                                                        <label for="promotionalsms"> Promotional SMS:</label>
                                                        <input type="checkbox" id="promotionalsms" name="promotionalsms" />
                                                        </td>
                                                        <td align="left" valign="top" bgcolor="#EDF4FF">
                                                        <label for="promotionalemail"> Promotional Email:</label>
                                                        <input type="checkbox" id="promotionalemail" name="promotionalemail" />
                                                        </td>
                                                      </tr>
                                                      <tr bgcolor="#f7faff">
                                                        <td valign="top" bgcolor="#f7faff" align="left">GSTIN:</td>
                                                        <td valign="top" bgcolor="#f7faff" align="left">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td ><input type="text" id="newgst" class="swifttext"  name="newgst" size="45" maxlength="500" autocomplete="off" disabled="disabled" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td ><div style="display:none" id="display16">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_gst" style="font-weight:bold;font-size:9px" height="12" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td valign="top" bgcolor="#EDF4FF" align="left">TAN NO:</td>
                                                        <td valign="top" bgcolor="#EDF4FF" align="left">
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                            <tr>
                                                              <td ><input type="text" id="newtanno" class="swifttext"  name="newtanno" size="45" maxlength="500" autocomplete="off" disabled="disabled" /></td>
                                                            </tr>
                                                            <tr>
                                                              <td ><div style="display:none" id="display17">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td id="m_tanno" style="font-weight:bold;font-size:9px" height="12" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr bgcolor="#EDF4FF">
                                                        <td colspan="2" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:solid 1px #D5EAFF">
                                                            <tr>
                                                              <td colspan="4" align="left" ><label>
                                                                <span style="border-left:1px solid #d1dceb">
                                                                <input type="checkbox" name="processedemail" id="processedemail" checked="checked" onclick="validatecheckbox()"/>
                                                                </span>
                                                                <label for="processedemail">Send Processed Email</label>
                                                                </span></td>
                                                            </tr>
                                                            <tr >
                                                              <td colspan="4" align="left" ><label>
                                                                <span style="border-left:1px solid #d1dceb">
                                                                <input type="checkbox" name="ccemail" id="ccemail" checked="checked"/>
                                                                </span>
                                                                <label for="ccemail" >Send Copy to requested person</label>
                                                                </span></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table>
                                      </div>
                                      <div style="display:none; " align="justify" id="tabg1c2">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="2" class="productcontent" height="370px">
                                          <tr>
                                            <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0"  >
                                                <tr bgcolor="#f7faff" class="productcontent">
                                                  <td width="79%" align="right" valign="top" bgcolor="#f7faff" style="padding-top:5px; padding-left:5px; padding-right:5px"><div align="right">
                                                      <input type="checkbox" name="validatecontact" id="validatecontact" />
                                                    </div>
                                                    </label></td>
                                                  <td width="21%" align="left" valign="top" bgcolor="#f7faff" style="padding-top:5px; padding-left:5px; padding-right:5px"><div align="left">Contact details are verified</div></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                      <tr>
                                                        <td ><table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#646464; font-weight:bold">
                                                            <tr>
                                                              <td width="4%">&nbsp;</td>
                                                              <td width="14%" ><div align="center">Type</div></td>
                                                              <td width="20%"><div align="center">Name</div></td>
                                                              <td width="20%"><div align="center">Phone</div></td>
                                                              <td width="15%"><div align="center">Cell</div></td>
                                                              <td width="23%"><div align="center">Email Id</div></td>
                                                              <td width="4%">&nbsp;</td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr>
                                                        <td ><table width="100%" border="0" cellspacing="0" cellpadding="3"  id="adddescriptionrows">
                                                            <tr id="removedescriptionrow1">
                                                              <td width="5%"><div align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td><strong>1</strong></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                              <td width="11%"><div align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><select name="selectiontype1" id="selectiontype1" style="width:120px" class="swiftselect-mandatory">
                                                                          <option value="" selected="selected" >--Select--</option>
                                                                          <option value="general" >General</option>
                                                                          <option value="gm/director">GM/Director</option>
                                                                          <option value="hrhead">HR Head</option>
                                                                          <option value="ithead/edp">IT-Head/EDP</option>
                                                                          <option value="softwareuser" >Software User</option>
                                                                          <option value="financehead">Finance Head</option>
                                                                          <option value="manager">MANAGER</option>              	
                                                                          <option value="CA" >CA</option>
                                                                          <option value="others" >Others</option>
                                                                        </select>
                                                                      </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td id="m_type" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td id="t_type" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                              <td width="16%"><div align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="name1" type="text" class="swifttext" id="name1"   autocomplete="off" style="width:170px" />
                                                                      </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" id="m_name" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" id="t_name" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                              <td width="18%"><div align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="phone1" type="text" class="swifttext" id="phone1"  autocomplete="off" style="width:170px" />
                                                                      </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" id="m_phone" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" id="t_phone" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                              <td width="15%"><div align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="cell1" type="text" class="swifttext" id="cell1"   autocomplete="off" style="width:120px" />
                                                                      </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" id="m_cell" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" id="t_cell" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                              <td width="27%"><div align="center">
                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="emailid1" type="text" class="swifttext" id="emailid1"  autocomplete="off" style="width:200px" />
                                                                      </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" id="m_emailid" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left" id="t_emailid" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                    </tr>
                                                                  </table>
                                                                </div></td>
                                                              <td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv1" onclick ="removedescriptionrows('removedescriptionrow1','1')" style="cursor:pointer; ">X</a></strong></font>
                                                                <input type="hidden" name="contactslno1" id="contactslno1" /></td>
                                                            </tr>
                                                          </table></td>
                                                      </tr>
                                                      <tr>
                                                        <td><div align="left" id="adddescriptionrowdiv">
                                                            <div align="right"><a onclick="adddescriptionrows();" style="cursor:pointer" class="r-text">Add one More >></a></div>
                                                          </div></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table>
                                      </div></td>
                                  </tr>
                                  <tr>
                                    <td height="10px"></td>
                                  </tr>
                                  <tr>
                                    <td align="right" valign="middle"  style="padding-right:15px;border-bottom:solid 1px #A6D2FF"><table width="100%" border="0" cellspacing="0" cellpadding="0"  >
                                        <tr>
                                          <td width="57%" height="35" align="left" valign="middle"><div id="form-error"></div></td>
                                          <td width="43%" height="35" align="right" valign="middle"><input name="approve" type="button" class= "swiftchoicebuttonbig" id="approve" value="Approve & Update" onclick="update();"/>
                                            &nbsp;&nbsp;&nbsp;
                                            <input name="reject" type="button" class= "swiftchoicebuttonbig" id="reject" value="Reject the Request" onclick="rejectrequest();"/>
                                            &nbsp;&nbsp;&nbsp;
                                            <input name="save" type="button" class="swiftchoicebutton" id="save" value="Clear Screen" onclick="screenclear();"/>
                                            &nbsp; </td>
                                        </tr>
                                        <tr>
                                          <td colspan="2" height="10px"></td>
                                        </tr>
                                      </table></td>
                                  </tr>
                                </table></td>
                            </tr>
                          </table>
                        </form></td>
                    </tr>
                    <tr>
                      <td height="34px" align="left"><div id="productselectionprocess"></div></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="140" align="center" id="tabgroupgridh1" onclick="gridtabcus3('1','tabgroupgrid','&nbsp; &nbsp;Customer Profile');customerprofiledatagrid();" style="cursor:pointer" class="grid-active-tabclass">Customer Module</td>
                            <td width="2"></td>
                            <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtabcus3('2','tabgroupgrid','&nbsp; &nbsp;Dealer Profile');dealererprofiledatagrid(); " style="cursor:pointer" class="grid-tabclass">Dealer Module</td>
                            <td width="2"></td>
                            <td width="140" align="center" id="tabgroupgridh3" onclick="gridtabcus3('3','tabgroupgrid','&nbsp; &nbsp;Web Profile'); webprofiledatagrid();" style="cursor:pointer" class="grid-tabclass">Web Module</td>
                            <td width="2"></td>
                            <td width="140" align="center" id="tabgroupgridh4" onclick="gridtabcus3('4','tabgroupgrid','&nbsp; &nbsp;Support Profile'); supportprofiledatagrid();" style="cursor:pointer" class="grid-tabclass">Support Module</td>
                            <td width="2"></td>
                            <td width="140" ></td>
                            <td width="140" ></td>
                            <td width="80" ></td>
                            <td><div id="gridprocessing"></div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td><div id="gridprocessing"></div></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr>
                                  <td width="37%" class="header-line" style="padding:0"><div id="tabdescription">&nbsp;&nbsp;Customer Module</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span></td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="center" valign="top"></td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="left" style="padding:0px"><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:937px;   display:block" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="left"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" align="left" ></div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="custresultgrid" style="overflow:auto; display:none; height:150px; width:937px; " align="center">&nbsp;</div></td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="left" style="padding:0px"><div id="tabgroupgridc2" style="overflow:auto; height:150px; width:937px;  display:none" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc2_1"  align="left"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="getmoredealerlink"  align="left" ></div></td>
                                        </tr>
                                      </table>
                                    </div></td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="left" style="padding:0px"><div id="tabgroupgridc3" style="overflow:auto; height:150px; width:937px; display:none " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc3_1" align="left" ></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="getmoreweblink" align="left" ></div></td>
                                        </tr>
                                      </table>
                                    </div></td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="left" style="padding:0px"><div id="tabgroupgridc4" style="overflow:auto; height:150px; width:937px; display:none " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc4_1" align="left" ></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="getmoresupportlink" align="left" ></div></td>
                                        </tr>
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
      </table></td>
  </tr>
</table>
<? } ?>
