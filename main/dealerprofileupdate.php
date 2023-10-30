<?php
if($p_dealerpendingrequest <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
}
 
else 
{
include("../inc/eventloginsert.php");
?>

<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/dealerprofileupdate.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictjs.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictlistjs.php?dummy=<?php echo (rand());?>"></script>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF" style="text-align:left">
  <tr>
        <td valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">Dealer  Profile Update Details</td>
                            <td width="40%">&nbsp;</td>
                            <td width="33%" align="left">&nbsp;</td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Enter /View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr></tr>
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                        
                                        <tr>
                                          <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                          <td style="border-right:1px solid #d1dceb;" align="left"><strong>Field Name</strong></td>
                                            <td align="left"><strong>Existing Data</strong></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                                  <td width="40%" align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;"><label> Business Name[Company] :</label></td>
                                                  <td width="60%" align="left" valign="top" bgcolor="#EDF4FF"><input name="extbusinessname" type="text" class="swifttext" id="extbusinessname" size="30" autocomplete="off"  style="background:#FEFFE6;" readonly="readonly" />
                                                    <input type="hidden" name="lastslno" id="lastslno" />
                                                    <input type="hidden" name="lastupdateslno" id="lastupdateslno" /></td>
                                                </tr>
                                              <tr bgcolor="#f7faff" >
                                                <td width="40%" align="left" valign="top" style="border-right:1px solid #d1dceb;"><label>                                                  Contact Person:</label></td>
                                                <td width="60%" align="left" valign="top" bgcolor="#f7faff"><input name="extcontactperson" type="text" class="swifttext" style="background:#FEFFE6;"  id="extcontactperson" size="30" autocomplete="off" readonly="readonly" /></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;"><label>                                                  Address:</label></td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><textarea name="extaddress" cols="27" class="swifttextarea" id="extaddress" style="background:#FEFFE6;" readonly="readonly" ></textarea>
                                                    <br /></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF" style="border-right:1px solid #d1dceb;"><label>                                                  Place:</label></td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><input name="extplace" type="text" class="swifttext" id="extplace" size="30" autocomplete="off" style="background:#FEFFE6;" readonly="readonly"  /></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;"><label>                                                  State:</label></td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="extstate" type="text" class="swifttext" id="extstate" size="30" autocomplete="off" style="background:#FEFFE6;" readonly="readonly"  />
                                                <input type="hidden" id="exstatecode" name="extstatecode" /></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF" style="border-right:1px solid #d1dceb;"><label>District:</label></td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF" ><input name="extdistrict" type="text" class="swifttext" id="extdistrict" size="30" autocomplete="off" style="background:#FEFFE6;" readonly="readonly"  />
                                                  <input type="hidden" id="extdistrictcode" name="extdistrictcode" /></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;"><label>                                                  Pin Code:</label></td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="extpincode" type="text" class="swifttext" id="extpincode" size="30" autocomplete="off" style="background:#FEFFE6;" readonly="readonly" /></td>
                                              </tr>
<tr bgcolor="#f7faff">
                                                <td align="left" valign="top" style="border-right:1px solid #d1dceb;"><label>                                                  STD Code:</label></td>
                                                <td align="left" valign="top"><input name="extstdcode" type="text" class="swifttext" id="extstdcode" size="30" autocomplete="off" style="background:#FEFFE6;"  readonly="readonly"/></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;"><label>                                                  Phone:</label></td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="extphone" type="text" class="swifttext" id="extphone" size="30" maxlength="80" autocomplete="off" style="background:#FEFFE6;" readonly="readonly"/></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF" style="border-right:1px solid #d1dceb;"><label>                                                  Cell:</label></td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><input name="extcell" type="text" class="swifttext" id="extcell" size="30" maxlength="80" autocomplete="off" style="background:#FEFFE6;" readonly="readonly" />
                                                    <br /></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;" ><label>                                                  Email:</label></td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="extemailid" type="text" class="swifttext" id="extemailid" size="30" maxlength="300" autocomplete="off" style="background:#FEFFE6;" readonly="readonly" /></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF" style="border-right:1px solid #d1dceb;" ><label>                                                  Personal Email:</label></td>
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><input name="extpersemailid" type="text" class="swifttext" id="extpersemailid" size="30" maxlength="300" autocomplete="off" style="background:#FEFFE6;" readonly="readonly" /></td>
                                              </tr>
                                              <tr bgcolor="#f7faff"> 
                                                <td align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;"><label>                                                  Website:</label></td>
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="extwebsite" type="text" class="swifttext" id="extwebsite" size="30" autocomplete="off" style="background:#FEFFE6;" readonly="readonly" />
                                                  <br /></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#f7faff" style="border-right:1px solid #d1dceb;"><label>                                                  Region:</label></td>
                                                <td align="left" valign="top" bgcolor="#f7faff" ><input name="extregion" type="text" class="swifttext" id="extregion" size="30" autocomplete="off" style="background:#FEFFE6;" readonly="readonly" /></td>
                                              </tr>
                                                 <tr bgcolor="#EDF4FF">
                                                  <td align="left" valign="top" bgcolor="#EDF4FF" style="border-right:1px solid #d1dceb;"><label>Request By:</label></td>
                                                  <td align="left" valign="top" bgcolor="#EDF4FF" ><input name="extdealerid" type="text" class="swifttext" id="extdealerid" size="30" autocomplete="off" style="background:#FEFFE6;" readonly="readonly" /></td>
                                                      </tr>
                                                 <tr bgcolor="#f7faff">
                                                  <td align="left" valign="top" bgcolor="#f7faff" style="border-right:1px solid #d1dceb;">Request date/time:</td>
                                                  <td align="left" valign="top" bgcolor="#f7faff" id="createddate">Not Available
                                                  </td>
                                                </tr>
                                          </table></td>
                                          <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                           <tr>
                                            <td width="54%" align="left"><strong>New Data</strong></td>
                                            <td colspan="3" style="border-left:1px solid #d1dceb" align="left"><strong>Update</strong></td>
                                           </tr><tr bgcolor="#f7faff">
                                                  <td width="55%" align="left" valign="top" bgcolor="#EDF4FF"><input name="newbusinessname" type="text" disabled="disabled" class="swifttext" id="newbusinessname" style="background:#F1F1F1;" size="30" maxlength="100" autocomplete="off"  /></td>
                                                  <td colspan="3" bgcolor="#EDF4FF" style="border-left:1px solid #d1dceb"><label for="dealerbusiness_none">
                                                    <input type="radio" name="dealerbusiness_type" id='dealerbusiness_none'  value="none" checked="checked" />
                                                    None</label>
                                                    <label for="dealerbusiness_approve">
                                                    <input type="radio"name="dealerbusiness_type" id='dealerbusiness_approve'  value="approve" />
                                                    Approve</label>
                                                    <label for="dealerbusiness_reject">
                                                    <input type="radio" name="dealerbusiness_type" id='dealerbusiness_reject' value="reject" />
                                                    Reject</label></td>
                                                </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" style="border-left:1px solid #d1dceb"><input name="newcontactperson" type="text" class="swifttext" id="newcontactperson" size="30" autocomplete="off" style="background:#F1F1F1" disabled="disabled" /></td>
                                                <td colspan="3" style="border-left:1px solid #d1dceb"><label for="dealercontact_none">
                                                  <input type="radio" name="dealercontact_type" id='dealercontact_none'  value="none" checked="checked" />
                                                  None</label>
                                                    <label for="dealercontact_approve">
                                                    <input type="radio"name="dealercontact_type" id='dealercontact_approve'  value="approve"  />
                                                      Approve</label>
                                                    <label for="dealercontact_reject">
                                                    <input type="radio" name="dealercontact_type" id='dealercontact_reject' value="reject"  />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><textarea name="newaddress" cols="27" class="swifttextarea" id="newaddress" style="background:#FFFFFF" disabled="disabled"  ></textarea></td>
                                                <td colspan="3" bgcolor="#EDF4FF" style="border-left:1px solid #d1dceb"><label for="dealeraddress_none">
                                                  <input type="radio" name="dealeraddress_type" id='dealeraddress_none'  value="none"  checked="checked"/>
                                                  None</label>
                                                    <label for="dealeraddress_approve">
                                                    <input type="radio"name="dealeraddress_type" id='dealeraddress_approve'  value="approve"  />
                                                      Approve</label>
                                                    <label for="dealeraddress_reject">
                                                    <input type="radio" name="dealeraddress_type" id='dealeraddress_reject' value="reject" />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><input name="newplace" type="text" disabled="disabled" class="swifttext" id="newplace" style="background:#F1F1F1" size="30" maxlength="80" autocomplete="off"  /></td>
                                                <td colspan="3" bgcolor="#F7FAFF" style="border-left:1px solid #d1dceb"><label for="dealerplace_none">
                                                  <input type="radio" name="dealerplace_type" id='dealerplace_none'  value="none" checked="checked"  />
                                                  None</label>
                                                    <label for="dealerplace_approve">
                                                    <input type="radio"name="dealerplace_type" id='dealerplace_approve'  value="approve"  />
                                                      Approve</label>
                                                    <label for="dealerplace_reject">
                                                    <input type="radio" name="dealerplace_type" id='dealerplace_reject' value="reject"  />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#f7faff" style="border-left:1px solid #d1dceb">
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><select name="newstate" class="swiftselect-mandatory" id="newstate" onchange="dealerdistrictcodeFunction();"  style="width: 200px;background:#F1F1F1" disabled="disabled" >
                                                  <option value="">Select A State</option>
                                                  <?php include('../inc/state.php'); ?>
                                                </select></td>
                                                <td colspan="3" bgcolor="#EDF4FF" style="border-left:1px solid #d1dceb"><label for="dealerstate_none">
                                                  <input type="radio" name="dealerstate_type" id='dealerstate_none'  value="none" checked="checked"  />
                                                  None</label>
                                                    <label for="dealerstate_approve">
                                                    <input type="radio"name="dealerstate_type" id='dealerstate_approve'  value="approve" />
                                                      Approve</label>
                                                    <label for="dealerstate_reject">
                                                    <input type="radio" name="dealerstate_type" id='dealerstate_reject' value="reject"  />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff" style="border-left:1px solid #d1dceb">
                                                <td align="left" valign="top" bgcolor="#F7FAFF" id="districtcodedisplay"><select name="newdistrict" class="swiftselect-mandatory"  id="newdistrict" style="width:200px;background:#F1F1F1; " disabled="disabled">
                                                      <option value="">Select A State First</option>
                                                    </select></td>
                                                <td colspan="3" bgcolor="#F7FAFF" style="border-left:1px solid #d1dceb"><label for="dealerdistrict_none">
                                                  <input type="radio" name="dealerdistrict_type" id='dealerdistrict_none'  value="none" checked="checked" />
                                                  None</label>
                                                    <label for="dealerdistrict_approve">
                                                    <input type="radio"name="dealerdistrict_type" id='dealerdistrict_approve'  value="approve"  />
                                                      Approve</label>
                                                    <label for="dealerdistrict_reject">
                                                    <input type="radio" name="dealerdistrict_type" id='dealerdistrict_reject' value="reject"  />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#f7faff" style="border-left:1px solid #d1dceb">
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="newpincode" type="text" disabled="disabled" class="swifttext" id="newpincode"  style="background: #FFFFFF" size="30" maxlength="30" autocomplete="off"/></td>
                                                <td colspan="3" bgcolor="#EDF4FF" style="border-left:1px solid #d1dceb"><label for="dealerpincode_none">
                                                  <input type="radio" name="dealerpincode_type" id='dealerpincode_none'  value="none" checked="checked"  />
                                                  None</label>
                                                    <label for="dealerpincode_approve">
                                                    <input type="radio"name="dealerpincode_type" id='dealerpincode_approve'  value="approve" onchange="" />
                                                      Approve</label>
                                                    <label for="dealerpincode_reject">
                                                    <input type="radio" name="dealerpincode_type" id='dealerpincode_reject' value="reject" />
                                                      Reject</label></td>
                                              </tr>
<tr bgcolor="#f7faff">
                                                <td align="left" valign="top"><input name="newstdcode" type="text" class="swifttext" id="newstdcode" size="30" autocomplete="off"  style="background:#FFFFFF" disabled="disabled" /></td>
                                                <td colspan="3" bgcolor="#F7FAFF" style="border-left:1px solid #d1dceb"><label for="dealerstd_none">
                                                  <input type="radio" name="dealerstd_type" id='dealerstd_none'  value="none" checked="checked"  />
                                                  None</label>
                                                    <label for="dealerstd_approve">
                                                   <input type="radio"name="dealerstd_type" id='dealerstd_approve'  value="approve"  />
                                                      Approve</label>
                                                    <label for="dealerstd_reject">
                                                    <input type="radio" name="dealerstd_type" id='dealerstd_reject' value="reject"  />
                                                      Reject</label></td>
</tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="newphone" type="text" class="swifttext" id="newphone" size="30" maxlength="80" autocomplete="off"  style="background:#F1F1F1" disabled="disabled"  /></td>
                                                <td colspan="3" bgcolor="#EDF4FF" style="border-left:1px solid #d1dceb"><label for="dealerphone_none">
                                                  <input type="radio" name="dealerphone_type" id='dealerphone_none'  value="none" checked="checked"  />
                                                  None</label>
                                                    <label for="dealerphone_approve">
                                                    <input type="radio"name="dealerphone_type" id='dealerphone_approve'  value="approve"  />
                                                      Approve</label>
                                                    <label for="dealerphone_reject">
                                                    <input type="radio" name="dealerphone_type" id='dealerphone_reject' value="reject"  />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><input name="newcell" type="text" class="swifttext" id="newcell" size="30" maxlength="80" autocomplete="off"  style="background:#F1F1F1" disabled="disabled"  /></td>
                                                <td colspan="3" bgcolor="#F7FAFF" style="border-left:1px solid #d1dceb"><label for="dealercell_none">
                                                  <input type="radio" name="dealercell_type" id='dealercell_none'  value="none" checked="checked" />
                                                  None</label>
                                                    <label for="dealercell_approve">
                                                 <input type="radio"name="dealercell_type" id='dealercell_approve'  value="approve"  />
                                                      Approve</label>
                                                    <label for="dealercell_reject">
                                                  <input type="radio" name="dealercell_type" id='dealercell_reject' value="reject"  />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="newemailid" type="text" class="swifttext" id="newemailid" size="30" maxlength="300" autocomplete="off"  style="background:#FFFFFF" disabled="disabled" /></td>
                                                <td colspan="3" bgcolor="#EDF4FF" style="border-left:1px solid #d1dceb"><label for="customeremail_none">
                                                  <input type="radio" name="dealeremail_type" id='dealeremail_none'  value="none" checked="checked"  />
                                                  None</label>
                                                    <label for="dealeremail_approve">
                                                <input type="radio"name="dealeremail_type" id='dealeremail_approve'  value="approve" />
                                                      Approve</label>
                                                    <label for="dealeremail_reject">
                                                  <input type="radio" name="dealeremail_type" id='dealeremail_reject' value="reject" />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#F7FAFF"><input name="newpersemailid" type="text" class="swifttext" id="newpersemailid" size="30" autocomplete="off" style="background:#FFFFFF" disabled="disabled" /></td>
                                                <td colspan="3" bgcolor="#F7FAFF" style="border-left:1px solid #d1dceb"><label for="dealerpersemailid_none">
                                                  <input type="radio" name="dealerpersemailid_type" id='dealerpersemailid_none'  value="none" checked="checked" />
                                                  None</label>
                                                    <label for="dealerpersemailid_approve">
                                                    <input type="radio"name="dealerpersemailid_type" id='dealerpersemailid_approve'  value="approve" />
                                                      Approve</label>
                                                    <label for="dealerpersemailid_reject">
                                                    <input type="radio" name="dealerpersemailid_type" id='dealerpersemailid_reject' value="reject" />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td align="left" valign="top" bgcolor="#EDF4FF"><input name="newwebsite" type="text" class="swifttext" id="newwebsite" size="30" autocomplete="off" style="background:#FFFFFF" disabled="disabled" /></td>
                                                <td colspan="3" bgcolor="#EDF4FF" style="border-left:1px solid #d1dceb"><label for="dealerwebsite_none">
                                                  <input type="radio" name="dealerwebsite_type" id='dealerwebsite_none'  value="none" checked="checked" />
                                                  None</label>
                                                    <label for="dealerwebsite_approve">
                                                    <input type="radio"name="dealerwebsite_type" id='dealerwebsite_approve'  value="approve" />
                                                      Approve</label>
                                                    <label for="dealerwebsite_reject">
                                                    <input type="radio" name="dealerwebsite_type" id='dealerwebsite_reject' value="reject" />
                                                      Reject</label></td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top" bgcolor="#f7faff"> <select name="newregion" class="swiftselect" id="newregion" style="width:200px;background:#F1F1F1" disabled="disabled" >
                                                      <option value="">Select A Region</option>
                                                      <?php 
											include('../inc/region.php');
											?>
                                                    </select></td>
                                                <td colspan="3" bgcolor="#EDF4FF" style="border-left:1px solid #d1dceb"><label for="dealerregion_none">
                                                  <input type="radio" name="dealerregion_type" id='dealerregion_none'  value="none" checked="checked"  />
                                                  None</label>
                                                    <label for="dealerregion_approve">
                                                    <input type="radio"name="dealerregion_type" id='dealerregion_approve'  value="approve" />
                                                      Approve</label>
                                                    <label for="dealerregion_reject">
                                                    <input type="radio" name="dealerregion_type" id='dealerregion_reject' value="reject" />
                                                      Reject</label></td>
                                              </tr>
<tr bgcolor="#f7faff"><td bgcolor="#f7faff">&nbsp;</td><td width="11%" bgcolor="#f7faff" style="border-left:1px solid #d1dceb"><label for="dealerselectall_none">
                                                    <input type="radio" name="dealerselectall_type" id='dealerselectall_none'  value="none" checked="checked" onclick="allnone()"  />
                                                    <br />
                                                  NoneAll</label></td>
  <td width="15%" bgcolor="#f7faff"><label for="dealerselectall_approve"><input type="radio" name="dealerselectall_type" id='dealerselectall_approve'  value="approve" onclick="selectall()"  />
                                                    <br />
                                                  ApproveAll</label></td>
  <td width="20%" bgcolor="#f7faff"><label for="dealerselectall_reject">
                                                    <input type="radio" name="dealerselectall_type" id='dealerselectall_reject'  value="reject" onclick="combineMenus()" />
                                                    <br />
                                                  RejectAll</label></td>
</tr>
<tr bgcolor="#f7faff"><td bgcolor="#f7faff">&nbsp;</td><td colspan="3" bgcolor="#f7faff" style="border-left:1px solid #d1dceb">&nbsp;</td></tr>
                                              <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                          </table></td>
                                        </tr>
                                        
                                        <tr>
                                          <td colspan="2"  valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;">&nbsp;</td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                                         <tr>
                                            <td width="65%" height="35" align="left" valign="middle"><div id="form-error"></div></td>
                                            <td width="35%" height="35" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebuttonbig" id="new" value="Process Update" onclick="update();"/>
                                              &nbsp;&nbsp;&nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Clear Screen" onclick="screenclear()"/>&nbsp;
                                             </td>
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
                      <td height="34px"><div id="productselectionprocess"></div></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                            <td width="140" align="center"  onclick="dealerprofiledatagrid();" style="cursor:pointer" class="grid-active-tabclass">Dealer Module</td>
                            <td width="2">&nbsp;</td>
                            <td width="140" align="center" >&nbsp;</td>
                            <td width="2"></td>
                            <td width="140" align="center">&nbsp;</td>
                           <td width="140" align="center">&nbsp;</td>
                           <td width="140" align="center">&nbsp;</td>
                           <td width="140" align="center">&nbsp;</td>
                            
                            <td><div id="gridprocessing"></div></td>
                          </tr>
                            </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;" >
                                <tr>
                                  <td width="37%" class="header-line" style="padding-left:15px"><div id="tabdescription">Dealer Profile Request</div></td>
                                  <td width="51%" align="left" class="header-line" style="padding:0"><span id="tabgroupgridwb1"></span></td>
                                </tr>
                                <tr >
                                  <td colspan="2" align="center" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" align="left" ><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:937px; display:block">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="left" style="overflow:auto"></div></td>
                                        </tr>
                                        <tr>
                                          <td>
                                            <div id="tabgroupgridc1link" align="left" ></div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:937px; " >&nbsp;</div></td>
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
      </table></td>
  </tr>
</table>
<?php } ?>
