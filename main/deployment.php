<?php
if($p_masterimplementation <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/deployment.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictjs.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td class="active-leftnav">Deployer Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="deployerselectionprocess" align="left" style="padding:0">&nbsp;</td>
                        <td width="29%"  style="padding:0"><div class="resendtext"><a onclick="displayfilterdiv()" style="cursor:pointer">Filter>></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2"><div id="displayfilter" style="display:none">
                            <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#f7faff" style=" border:1px solid #ADD8F1">
                              <tr>
                                <td colspan="2" align="left" valign="top"><strong>Co-ordinator:</strong><br />
                                  <label>
                                  <input type="radio" name="coordinator_type" id="coordinator0" value="yes" />
                                  Yes</label>
                                  <label>
                                  <input type="radio" name="coordinator_type" id="coordinator1" value="no" />
                                  No</label>
                                  <label>
                                  <input name="coordinator_type" type="radio" id="coordinator2" value="" checked="checked" />
                                  All </label></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top"><strong>Login:
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
                                <td width="40%" align="left" valign="top"><strong>Region:</strong><br /></td>
                                <td width="60%" align="left" valign="top"><select name="deployerregion" class="swiftselect-mandatory" id="deployerregion">
                                    <option value="">All</option>
                                    <?php 
											include('../inc/region.php');
											?>
                                  </select></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><div align="right" class="resendtext"><a onclick="refreshdeploymentarray();" style="cursor:pointer">Load&gt;&gt;</a></div></td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"  onkeyup="deployersearch(event);" autocomplete="off"  style="width:204px;"/>
                          <div id="detailloaddeployerlist">
                            <select name="deployerlist" size="5" class="swiftselect" id="deployerlist" style="width:210px; height:400px;" onchange="selectfromlist();" onclick="selectfromlist()">
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
                            <td width="27%" class="active-leftnav">Deployer Details</td>
                            <td width="40%"><div align="right">Search By Deployer ID:</div></td>
                            <td width="33%" align="left"><div align="right">
                                <input name="searchbydeployer" type="text" class="swifttext" id="searchbydeployer" style="width:200px" onkeyup="searchbydeployeridevent(event);" autocomplete="off"  />
                                <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="deployerdetailstoform(document.getElementById('searchbydeployer').value);" style="cursor:pointer" /> </div></td>
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
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td colspan="2" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                            <td align="left" bgcolor="#F7FAFF">Business Name [Company]:</td>
                                            <td align="left" bgcolor="#F7FAFF"><input name="businessname" type="text" class="swifttext-mandatory" id="businessname" size="70" maxlength="100" autocomplete="off"  />
                                              <input type="hidden" name="lastslno" id="lastslno" /></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td width="51%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td width="51%" align="left" valign="top">Contact Person:</td>
                                            <td width="60%" align="left" valign="top" bgcolor="#f7faff"><input name="contactperson" type="text" class="swifttext-mandatory" id="contactperson" size="30" maxlength="100" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Address:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><textarea name="address" cols="27" class="swifttextarea" id="address"></textarea></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">Place:</td>
                                            <td align="left" valign="top" bgcolor="#F7FAFF"><input name="place" type="text" class="swifttext-mandatory" id="place" size="30" maxlength="30" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">State:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><select name="state" class="swiftselect-mandatory" id="state" onchange="getdistrict('districtcodedisplay',this.value);" onkeyup="getdistrict('districtcodedisplay',this.value);"  style="width:200px;">
                                                <option value="">Select A State</option>
                                                <?php include('../inc/state.php'); ?>
                                              </select></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">District:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="districtcodedisplay"><select name="district" class="swiftselect-mandatory" id="district"  style="width:200px;">
                                                <option value="">Select A State First</option>
                                              </select></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td align="left" valign="top" bgcolor="#edf4ff">Pin Code:</td>
                                            <td align="left" valign="top" bgcolor="#edf4ff"><input name="pincode" type="text" class="swifttext-mandatory" id="pincode" size="30" maxlength="6" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">STD Code:</td>
                                            <td align="left" valign="top" bgcolor="#F7FAFF"><input name="stdcode" type="text" class="swifttext" id="stdcode" size="30" maxlength="5" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#edf4ff">Phone:</td>
                                            <td align="left" valign="top" bgcolor="#edf4ff"><input name="phone" type="text" class="swifttext-mandatory" id="phone" size="30" maxlength="80"  autocomplete="off" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">Cell:</td>
                                            <td align="left" valign="top" bgcolor="#F7FAFF"><input name="cell" type="text" class="swifttext-mandatory" id="cell" size="30" maxlength="80" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#edf4ff">Relyon Email:</td>
                                            <td align="left" valign="top" bgcolor="#edf4ff"><input name="emailid" type="text" class="swifttext-mandatory" id="emailid" size="30" maxlength="255" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">Personal Email:</td>
                                            <td align="left" valign="top" bgcolor="#F7FAFF"><input name="personalemailid" type="text" class="swifttext" id="personalemailid" size="30" maxlength="255" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td width="51%" align="left" valign="top" bgcolor="#edf4ff">TL Email ID:</td>
                                            <td width="60%" align="left" valign="top" bgcolor="#edf4ff"><input name="tlemailid" type="text" class="swifttext" id="tlemailid" size="30" maxlength="255" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">Manager Email ID:</td>
                                            <td align="left" valign="top" bgcolor="#F7FAFF"><input name="mgremailid" type="text" class="swifttext" id="mgremailid" size="30" maxlength="255" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">HO Email ID:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input name="hoemailid" type="text" class="swifttext" id="hoemailid" size="30" maxlength="255" autocomplete="off"  /></td>
                                          </tr>
                                           <tr bgcolor="#edf4ff">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">Branch :</td>
                                            <td align="left" valign="top" bgcolor="#F7FAFF"><select name="branchid" class="swiftselect" id="branchid">
                                              <option value="all">Not Applicable</option>
                                              <?php 
											include('../inc/branch.php');
											?>
                                            </select></td>
                                          </tr>
                                      </table></td>
                                      <td width="49%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#edf4ff">
                                            <td align="left" valign="top" bgcolor="#F7FAFF">Website:</td>
                                            <td align="left" valign="top" bgcolor="#F7FAFF"><input name="website" type="text" class="swifttext" id="website" size="30" maxlength="80" autocomplete="off"  /></td>
                                          </tr>
                                          <tr>
                                            <td align="left" valign="top" bgcolor="#edf4ff">Region:</td>
                                            <td align="left" valign="top" bgcolor="#edf4ff"><label>
                                              <select name="region" class="swiftselect-mandatory" id="region">
                                                <option value="">Select A Region</option>
                                                <?php 
											include('../inc/region.php');
											?>
                                              </select>
                                              </label></td>
                                          </tr>
                                          <tr>
                                            <td align="left" valign="top" bgcolor="#edf4ff">Type:</td>
                                            <td align="left" valign="top" bgcolor="#edf4ff"><label>
                                              <select name="designtype" class="swiftselect-mandatory" id="designtype">
                                                <option value="">Select A Type</option>
                                                <option value="implementer">Implementer</option>
                                                <option value="customizer">Customiser</option>
                                                <option value="webmodule">Web Module Implementer</option>
                                              </select>
                                              </label></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Co-ordinator:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input type="checkbox" name="coordinator" id="coordinator" /></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Hand Hold:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input type="checkbox" name="handhold" id="handhold" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF" >Remarks:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks"></textarea></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Deployer ID:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input name="deployerid" type="text" class="swiftselect-readonly" id="deployerid" size="30" readonly="readonly" autocomplete="off"  /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Disable Login:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" ><input type="checkbox" name="disablelogin" id="disablelogin" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Not in Use:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" ><input type="checkbox" name="deployernotinuse" id="deployernotinuse" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Created By:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="createdby">Not Available</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Created Date:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff" id="createddate">Not Available</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="2" style=" border:1px solid #ADD8F1" >
                                                <tr>
                                                  <td colspan="2" align="left"><strong>Login Info:</strong></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td width="36%" align="left" valign="top" bgcolor="#f7faff">User Name:</td>
                                                  <td width="64%" align="left" valign="top" bgcolor="#f7faff" ><input name="deployerusername" type="text" class="swifttext-mandatory" id="deployerusername" size="30" maxlength="80"  autocomplete="off" /></td>
                                                </tr>
                                                <tr bgcolor="#F7FAFF">
                                                  <td align="left" valign="middle" bgcolor="#F7FAFF" height="30px" >Password:</td>
                                                  <td  height="30px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td colspan="2" bgcolor="#F7FAFF"  align="left"><div id="displaypassworddfield" style="display:none" bgcolor="#F7FAFF"><span onclick="Displaydiv1()" class="resentfont" >Reset Deployer Password</span></div>
                                                          <div id="resetpwd" style="display:none; ">
                                                            <input name="password" type="text" class="swifttext" id="password" size="23" maxlength="30" autocomplete="off"  />
                                                            &nbsp;&nbsp;<img src="../images/imax-pwdreset-button.jpeg" align="absmiddle" title="Password Update" alt="Password Update"  onclick="validatepwd()" style="cursor:pointer"  />&nbsp;&nbsp;<img src="../images/imax-pwdclose-button.jpeg" align="absmiddle" title="Close" alt="Close" onclick="closefunc()" style="cursor:pointer" /> </div></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td colspan="2" height="25px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td width="36%" height="19" align="left" valign="top" bgcolor="#edf4ff" >Last Password: </td>
                                                        <td width="64%" style="padding-left:2px"  align="left" valign="top" bgcolor="#edf4ff" id="passwordfield"><span id="initialpassworddfield" style="display:none">
                                                          <input name="initialpassword" type="text" class="swifttext" id="initialpassword" size="30" readonly="readonly" style="background:#FEFFE6; color:#000000" autocomplete="off" />
                                                          </span> <span id="displayresetpwd" style="display:none1">
                                                          <input name="resetpassword" type="text" class="swifttext" id="resetpassword" size="30" readonly="readonly" style="background:#FEFFE6; color:#FF0000;" autocomplete="off" />
                                                          </span></td>
                                                      </tr>
                                                    </table></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top" bgcolor="#f7faff">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top" bgcolor="#f7faff">&nbsp;</td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0" height="70">
                                          <tr>
                                            <td height="25" colspan="2" align="left" valign="middle"><div id="form-error"></div></td>
                                          </tr>
                                          <tr>
                                            <td width="43%" height="35" align="left" valign="middle">&nbsp;</td>
                                            <td width="57%" height="35" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onclick="newentry(); makefocus('businessname'); document.getElementById('form-error').innerHTML = '';" />
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onclick="formsubmit('save');" />
                                              &nbsp;
                                              <input name="delete" type="submit" class="swiftchoicebuttondisabled" id="delete" value="Delete" disabled="disabled" onclick="formsubmit('delete');"/>
                                              &nbsp;
                                              <input name="search" type="submit" class="swiftchoicebutton" id="search" value="Search" onclick="document.getElementById('filterdiv').style.display='block';" /></td>
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
                      <td><div id="filterdiv" style="display:none;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                            <tr>
                              <td valign="top"><div>
                                  <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr>
                                        <td width="100%" valign="top" style="border-right:1px solid #d1dceb;">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                            <tr bgcolor="#edf4ff">
                                              <td width="14%" align="left" valign="top">Search Text: </td>
                                              <td colspan="3" align="left" valign="top"><input name="searchcriteria" type="text" id="searchcriteria" size="50" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td colspan="4" align="left" valign="top" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td width="14%" valign="top">In: </td>
                                                    <td width="86%"><label>
                                                      <input type="radio" name="databasefield" id="databasefield0" value="deployerid"/>
                                                      Deployer ID </label>
                                                      <label> &nbsp;
                                                      <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                      Business Name</label>
                                                      &nbsp;
                                                      <label>
                                                      <input type="radio" name="databasefield" value="contactperson" id="databasefield3" />
                                                      Contact Person</label>
                                                      &nbsp;
                                                      <label>
                                                      <input type="radio" name="databasefield" value="phone" id="databasefield4" />
                                                      Phone</label>
                                                      &nbsp;&nbsp;
                                                      <label>
                                                      <input type="radio" name="databasefield" value="emailid" id="databasefield6" />
                                                      Email</label>&nbsp;&nbsp;
                                                      <label>
                                                      <input type="radio" name="databasefield" value="coordinator" id="databasefield7" />
                                                      Co-ordinator</label></td>
                                                  </tr>
                                                </table>
                                                <label></label></td>
                                            </tr>
                                            <tr bgcolor="#edf4ff">
                                              <td align="left" valign="top">In Region:</td>
                                              <td width="39%" align="left" valign="top"><select name="searchregion" class="swiftselect-mandatory" id="searchregion">
                                                  <option value="">Select A Region</option>
                                                  <?php 
											include('../inc/region.php');
											?>
                                                </select></td>
                                              <td width="12%" align="left" valign="top">Order By:</td>
                                              <td width="35%" align="left" valign="top"><select name="orderby" id="orderby" class="swiftselect">
                                                  <option value="deployerid">Deployer ID</option>
                                                  <option value="businessname" selected="selected">Business Name</option>
                                                  <option value="contactperson">Contact Person</option>
                                                  <option value="phone">Phone</option>
                                                  <option value="emailid">Email ID</option>
                                                </select></td>
                                            </tr>
                                          </table></td>
                                      </tr>
                                      <tr>
                                        <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="69%" height="35" align="left" valign="middle"><div id="filter-form-error"></div></td>
                                              <td width="31%" align="right" valign="middle"><input name="filter" type="button" class="swiftchoicebutton" id="filter" value="Search" onclick="searchfilter('');" />
                                                &nbsp;&nbsp;
                                                <input name="close" type="button" class="swiftchoicebutton-red" id="close" value="Close" onclick="document.getElementById('filterdiv').style.display='none';" />
                                              </td>
                                            </tr>
                                          </table>
                                            </td>
                                      </tr>
                                    </table>
                                  </form>
                                </div></td>
                            </tr>
                          </table>
                        </div></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="140px" align="center" id="tabgroupgridh1"  style="cursor:pointer" class="grid-active-tabclass">Search Result</td>
                            <td width="2">&nbsp;</td>
                            <td width="2">&nbsp;</td>
                            <td width="140" align="center" ></td>
                            <td width="140" align="center" ></td>
                            <td width="140" align="center" ></td>
                            <td width="140" align="center" ></td>
                            <td><div id="gridprocessing"></div></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="4"></td>
                                  <td width="140" align="center" ></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #6393df; border-top:none;">
                                <tr>
                                  <td width="15%" align="left" class="header-line" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td width="45%"><div id="tabdescription"></div></td>
                                        <td><span id="tabgroupgridwb1"></span><span id="tabgroupgridwb2"></span><span id="tabgroupgridwb3"></span><span id="tabgroupgridwb4"></span> </td>
                                        <td width="1%"></td>
                                      </tr>
                                    </table></td>
                                </tr>
                                <tr>
                                  <td align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:704px; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1"  align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1linksearch" align="left"></div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="resultgrid" style="overflow:auto; height:150px; width:704px; padding:2px; display:none;" align="center"></div>
                                    </div>
                                  </td>
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
<script>refreshdeploymentarray()</script>
<?php } ?>