<?
if($p_contactdetailsreport <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/contactdetailsreport.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>
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
                            <td width="27%" class="active-leftnav">Report - Customer Contact Details</td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Make A Report </td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#EDF4FF">
                                            <td colspan="2"></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Dealer:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="dealerid" class="swiftselect-mandatory" id="dealerid" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/firstdealer.php'); ?>
                                              </select>
                                              <input type="hidden" name="flag" id="flag" value="true"/>
                                            </td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#f7faff" align="left">Region:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="region" class="swiftselect-mandatory" id="region" >
                                                <option value="">ALL</option>
                                                <? include('../inc/region.php'); ?>
                                              </select>
                                            </td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Branch:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="branch" class="swiftselect-mandatory" id="branch" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/branch.php'); ?>
                                              </select>
                                            </td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#f7faff" align="left">Type:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="type" class="swiftselect-mandatory" id="type" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/custype.php'); ?>
                                              </select>
                                            </td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Category:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><select name="category" class="swiftselect-mandatory" id="category" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/category.php'); ?>
                                              </select>
                                            </td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td colspan="2"  valign="top" bgcolor="#f7faff" align="left"><strong>Duplicate contacts for</strong></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left"  valign="top" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td width="17%"><input type="radio" name="contact_type" id="none" value="none" checked="checked" />
                                                    <label for="none">None</label></td>
                                                  <td width="34%"><input type="radio" name="contact_type" id="uniquemailid" value="uniquemailid" onclick="selectcheckbox('uniquemailid','emailid_checkvalue')"/>
                                                    <label for="uniquemailid">Unique Email Ids</label></td>
                                                  <td width="49%"><input type="radio" name="contact_type" id="uniquecellno" value="uniquecellno"  onclick="selectcheckbox('uniquecellno','cell_checkvalue')" />
                                                    <label for="uniquecellno">Unique Mobile Numbers</label></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2"  valign="top" bgcolor="#EDF4FF"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td width="24%" align="left">Active Customer:</td>
                                                  <td width="76%" align="left"><label for="activecustome_yes">
                                                    <input type="radio" name="activecustomer_type" id="activecustome_yes" value="yes"  />
                                                    Yes</label>
                                                    <label for="activecustome_no">
                                                    <input type="radio" name="activecustomer_type" id="activecustome_no" value="no"  />
                                                    No</label>
                                                    <label for="activecustome_both">
                                                    <input type="radio" name="activecustomer_type" id="activecustome_both" value="" checked="checked" />
                                                    Both</label></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:solid 1px #379BFF">
                                                <tr>
                                                  <td width="9%" align="left"><label>
                                                    <input type="checkbox" name="reregenable" id="reregenable" value="reregenable"  onclick="enabledisablereregistartion()" />
                                                    </label></td>
                                                  <td width="91%" align="left"><strong> Consider Registration</strong></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                      <tr>
                                                        <td align="left">From Date:</td>
                                                        <td align="left"><input name="fromdate" type="text" class="diabledatefield" id="DPC_fromdate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" disabled="disabled" /></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">To Date:</td>
                                                        <td align="left"><input name="todate" type="text" class="diabledatefield" id="DPC_todate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly" disabled="disabled" /></td>
                                                      </tr>
                                                      <tr>
                                                        <td width="27%" align="left">Usage Type</td>
                                                        <td width="73%" align="left"><select name="usagetype" class="sdiabledatefield" id="usagetype"  style=" width:200px" disabled="disabled" >
                                                            <option value="" selected="selected">ALL</option>
                                                            <option value="singleuser">Single User</option>
                                                            <option value="multiuser">Multi User</option>
                                                            <option value="additionallicense">Additional License</option>
                                                          </select></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Purchase Type</td>
                                                        <td align="left"><select name="purchasetype" class="sdiabledatefield" id="purchasetype"  style=" width:200px" disabled="disabled" >
                                                            <option value="" selected="selected">ALL</option>
                                                            <option value="new">New</option>
                                                            <option value="updation">Updation</option>
                                                          </select></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Scheme</td>
                                                        <td align="left"><select name="scheme" class="sdiabledatefield" id="scheme" style=" width:200px" disabled="disabled" >
                                                            <option value="">ALL</option>
                                                            <? include('../inc/listscheme.php'); ?>
                                                          </select></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Re-registration</td>
                                                        <td align="left"><select name="rereg" class="sdiabledatefield" id="rereg"   style=" width:200px" disabled="disabled" >
                                                            <option value="" selected="selected">ALL</option>
                                                            <option value="yes">Yes</option>
                                                            <option value="no">No</option>
                                                          </select></td>
                                                      </tr>
                                                      <tr>
                                                        <td align="left">Pin Serial Number</td>
                                                        <td align="left"><select name="card" class="sdiabledatefield" id="card"  style=" width:200px" disabled="disabled" >
                                                            <option value="" selected="selected">ALL</option>
                                                            <option value="withcard">With PIN Number</option>
                                                            <option value="withoutcard">Without PIN Number</option>
                                                          </select></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" bgcolor="#EDF4FF" align="left"><strong>Products </strong></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" bgcolor="#f7faff" align="left"><div style="height:362px; overflow:scroll">
                                                <? include('../inc/product-report.php'); ?>
                                              </div></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td width="10%">Select: </td>
                                            <td width="33%" align="left"><strong>
                                              <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:140px" >
                                                <option value="ALL" >ALL</option>
                                                <option value="NONE" selected="selected">NONE</option>
                                                <? include('../inc/productgroup.php') ?>
                                              </select>
                                              </strong></td>
                                            <td width="57%" align="left"><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                            <input type="hidden" name="groupvalue" id="groupvalue"  />
                                          </tr>
                                        </table>
                                        <label></label>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" style="border-top:1px solid #d1dceb;"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                          <tr>
                                            <td align="left"><fieldset style="border:1px solid #8AC5FF; padding:3px;">
                                              <legend><strong>Fields Options</strong> </legend>
                                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                <tr>
                                                  <td width="11%" align="left"><label for="address_checkvalue"><strong>
                                                    <input name="checkvalue[]" type="checkbox" id="address_checkvalue" value="Address"/>
                                                  </strong>Address</label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox" id="place_checkvalue" value="Place" />
                                                  <label for="place_checkvalue"> Place </label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox" id="pincode_checkvalue"  value="Pincode"/>
                                                    <label for="pincode_checkvalue">Pin Code</label> </td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox"  id="district_checkvalue" value="District" checked="checked" />
                                                    <label for="district_checkvalue">District</label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox"  id="state_checkvalue" value="State"/>
                                                     <label for="state_checkvalue">State</label>                                                  </td>
                                                  <td width="12%" align="left"><input name="checkvalue[]" type="checkbox"  id="contactperson_checkvalue" value="Contact Person" checked="checked" />
                                                    <label for="contactperson_checkvalue">Contact Person</label></td>
                                                     <td width="11%" align="left">
                                                    <input name="checkvalue[]" type="checkbox"  id="stdcode_checkvalue" value="Stdcode" /> 
                                                    <label for="stdcode_checkvalue">Stdcode</label>
                                                    </td>
                                                  <td width="11%" align="left">&nbsp;&nbsp;
                                                    <input name="checkvalue[]" type="checkbox"  id="phone_checkvalue" value="Phone" /> 
                                                    <label for="phone_checkvalue">Phone</label>
                                                    </td>
                                                </tr>
                                                <tr>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox"  id="cell_checkvalue" value="Cell" checked="checked"  />
                                                    <label for="cell_checkvalue">Cell</label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox" id="emailid_checkvalue" value="Emailid" checked="checked"/>
                                                    <label for="emailid_checkvalue">Email Id</label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox" id="region_checkvalue" value="Region" />
                                                    <label for="region_checkvalue">Region </label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox" id="branch_checkvalue" value="Branch" />
                                                    <label for="branch_checkvalue">Branch</label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox"  id="type_checkvalue" value="Type" />
                                                    <label for="type_checkvalue">Type</label></td>
                                                  <td width="12%" align="left"><input name="checkvalue[]" type="checkbox"  id="category_checkvalue" value="Category"/>
                                                    <label for="category_checkvalue">Category </label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox"  id="website_checkvalue" value="Website" />
                                                    <label for="website_checkvalue">Website</label></td>
                                                  <td width="11%" align="left">&nbsp;&nbsp;
                                                    <input name="checkvalue[]" type="checkbox"  id="dealer_checkvalue" value="Dealer" />
                                                    <label for="dealer_checkvalue">Dealer</label></td>
                                                </tr>
                                                <tr>
                                                  <td width="11%" align="left"><input type="checkbox" name="checkvalue[]" id="dealeremail_checkvalue" value="Dealer Email">
                                                  <label for="dealeremail_checkvalue">Dealer Email</label></td>
                                                  <td width="13%" align="left"><input type="checkbox" name="checkvalue[]" id="relyonexecutive_checkvalue" value="Relyon Executive">
                                                  <label for="relyonexecutive_checkvalue">Relyon Executive</label></td>
                                                  <td width="11%" align="left"><input name="checkvalue[]" type="checkbox"  id="scheme_checkvalue" value="Scheme" />
                                                    <label for="scheme_checkvalue"> Scheme</label></td>
                                                  <td width="13%" align="left">
                                                  <input name="checkvalue[]" type="checkbox" id="purchasetype_checkvalue" value="Purchase Type"/>
                                                    <label for="purchasetype_checkvalue">Purchase Type</label></td>
                                                    <td width="11%" align="left">
                                                    <input name="checkvalue[]" type="checkbox" id="usagetype_checkvalue" value="Usage Type" />
                                                    <label for="usagetype_checkvalue">Usage Type </label></td>
                                                  
                                                  <td width="11%" align="left">&nbsp;</td>
                                                  <td width="11%" align="left">&nbsp;</td>
                                                </tr>
                                              </table>
                                              </fieldset></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; "><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="formsubmit('view');" />
                                              &nbsp;
                                              <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="formsubmit('toexcel');"/>
                                              &nbsp;
                                              <input name="resetform" type="submit" class="swiftchoicebutton" id="resetform" value="Reset" onclick="resetfunc()"/>
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
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<? } ?>
