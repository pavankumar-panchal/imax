<?php
if($p_suggestedmerging <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/mergecustomerlist.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictjsmerge.php?dummy=<?php echo (rand());?>"></script>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">List of Merge Records</td>
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
                      <td id="headerline"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td align="left" class="header-line" style="padding:0">&nbsp;Select a   Criteria</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form id="submitform" name="submitform" method="post" action="" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                    <tr>
                                      <td><div style="display:block" id="tabc1">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                            <tr>
                                              <td bgcolor="#f7faff"><fieldset style="border:1px solid #666666; padding:3px;">
                                                <legend><strong>Common Criteria:</strong> </legend>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td width="1%">&nbsp;</td>
                                                    <td width="19%"><label for="databasefield0">
                                                      <input type="radio" name="databasefield" id="databasefield0" value="Company" checked="checked"/>
                                                      Same Company Name </label></td>
                                                    <td width="11%"><label for="databasefield1">
                                                      <input type="radio" name="databasefield" id="databasefield1" value="Emailid"/>
                                                      Same Email </label></td>
                                                    <td width="14%"><label for="databasefield2">
                                                      <input type="radio" name="databasefield" id="databasefield2" value="Phone" />
                                                      Same Phone</label></td>
                                                    <td width="12%"><label for="databasefield3">
                                                      <input type="radio" name="databasefield" value="Cell" id="databasefield3" />
                                                      Same Cell</label></td>
                                                    <td width="14%"><label for="databasefield4">
                                                      <input type="radio" name="databasefield" value="Website" id="databasefield4"/>
                                                      Same Website</label></td>
                                                    <td width="29%">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td height="4px"></td>
                                                  </tr>
                                                  <tr >
                                                    <td colspan="7"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="padding-left:7px">
                                                        <tr>
                                                          <td colspan="2">&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                                </fieldset></td>
                                            </tr>
                                            <tr>
                                              <td align="right" valign="middle" ><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="69%" height="35" align="left" valign="middle"><div id="form-error"></div></td>
                                                    <td width="31%" align="right" valign="middle"><input name="next" type="button" class="swiftchoicebutton" id="next" value="Next" onclick="mergearray();" />
                                                      &nbsp;&nbsp; </td>
                                                  </tr>
                                                </table></td>
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
                  </table></td>
              </tr>
              <tr>
                <td><div id="tabc2" style="display:none" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #d1dceb;">
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td width="10%">Total Records: </td>
                              <td width="11%" id="displayrecords" >&nbsp;</td>
                              <td width="42%" id="mergeform-error" height='23px'>&nbsp;</td>
                              <td width="17%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="28%"><div align="center"><a href="javascript:imageclick('leftarrow');"><img src="../images/leftarrow.gif" alt="LeftArrow" name="leftarrow" border="0" id="leftarrow"/></a></div></td>
                                    <td width="38%" id ="records" align="center"></td>
                                    <td width="34%"><div align="center"><a href="javascript:imageclick('rightarrow');"><img src="../images/rightarrow.gif" alt="RightArrow" border="0" id="rightarrow"/></a>
                                        <input type="hidden" name="hiddenField" id="hiddenField" />
                                        <input type="hidden" name="hiddenpagenumber" id="hiddenpagenumber" />
                                      </div></td>
                                  </tr>
                                </table></td>
                              <td width="6%"><strong>Criteria:</strong></td>
                              <td width="14%" id="criteria">&nbsp;</td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                            <tr class="header-line">
                              <td width="130px" align="left">Merging Information</td>
                              <td width="190px" align="left" id="numofcust" ></td>
                              <td  align="left" >&nbsp;</td>
                              <td align="left" >&nbsp;</td>
                            </tr>
                            
                          </table></td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td><div id="displaydata"></div></td>
  </tr>
</table>
</td>
                      </tr>
                      <tr>
                        <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td><div style="width:935px;">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="8" style="border:1px solid #308ebc; border-top:none; border-bottom:1px solid #308ebc">
                                                      <tr><td align="left"  class="header-line" style="padding:0">Merging Information</td></tr>
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="productcontent" style="height:280px">
                                          <tr>
                                            <td valign="top"><form action="" method="post" name="mergeform" id="mergeform">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td  width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-right:1px solid #308ebc">
                                                              <tr bgcolor="#f7faff">
                                                                <td width="24%" valign="top" bgcolor="#f7faff" align="left">Customer ID:</td>
                                                                <td width="76%" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr height="30px">
                                                                      <td align="left"><input name="customerid" type="text" class="swifttext-readonly" id="customerid" size="45"  autocomplete="off" readonly="readonly"/>
                                                                        <input type="hidden" name="srchiddencustomerid" id="srchiddencustomerid" value=""/>
                                                                        <input type="hidden" name="destcustomerid" id="destcustomerid" value=""/></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr  height="30px">
                                                                <td width="24%" valign="top" bgcolor="#EDF4FF">Company:</td>
                                                                <td width="76%" bgcolor="#EDF4FF"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="businessname" type="text" class="swifttext" id="businessname" size="45"  autocomplete="off" /></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              
                                                              <tr bgcolor="#F7FAFF" height="30px">
                                                                <td valign="top" bgcolor="#F7FAFF" align="left">Address:</td>
                                                                <td valign="top" bgcolor="#F7FAFF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left" ><textarea name="address" cols="45" class="swifttextarea" id="address">
                                                                     </textarea></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#F7FAFF"  height="30px">
                                                                <td valign="top" bgcolor="#EDF4FF" align="left">Place:</td>
                                                                <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="place" type="text" class="swifttext" id="place" size="45" autocomplete="off" /></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#F7FAFF"  height="30px">
                                                                <td valign="top" bgcolor="#F7FAFF" align="left">State:</td>
                                                                <td valign="top" bgcolor="#F7FAFF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><select name="state" class="swiftselect" id="state" onchange="getdistrictmerge('mergedistrictcodedisplay',this.value);" onkeyup="getdistrictmerge('mergedistrictcodedisplay',this.value);"  style="width:200px;" >
                                                                          <option value="">Select A State</option>
                                                                          <?php include('../inc/state.php'); ?>
                                                                        </select></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#edf4ff"  height="30px">
                                                                <td valign="top" bgcolor="#EDF4FF" align="left">District:</td>
                                                                <td valign="top" bgcolor="#EDF4FF" align="left" ><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td id="mergedistrictcodedisplay" align="left"><select name="district" class="swiftselect" id="district" style="width:200px;">
                                                                          <option value="">Select A State First</option>
                                                                        </select></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#f7faff"  height="30px">
                                                                <td valign="top" bgcolor="#f7faff" align="left">Pincode:</td>
                                                                <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="pincode" type="text" class="swifttext" id="pincode" size="45" maxlength="6"  autocomplete="off"/></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              
                                                              <tr bgcolor="#f7faff"  height="30px">
                                                                <td valign="top" bgcolor="#f7faff" align="left">Remarks:</td>
                                                                <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="remarks" type="text" class="swifttext" id="remarks" size="45" maxlength="200"  autocomplete="off"/></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              
                                                            </table></td>
                                                          <td width="50%" valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                              <tr bgcolor="#EDF4FF"  height="30px">
                                                                <td valign="top" bgcolor="#EDF4FF" align="left">STD code:</td>
                                                                <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="stdcode" type="text" class="swifttext" id="stdcode" size="45"  autocomplete="off"/></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#f7faff"  height="30px">
                                                                <td valign="top" align="left">Fax:</td>
                                                                <td valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="fax" type="text" class="swifttext" id="fax" size="45" autocomplete="off" /></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#edf4ff"  height="30px">
                                                                <td valign="top" bgcolor="#edf4ff" align="left">Website:</td>
                                                                <td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><input name="website" type="text" class="swifttext" id="website" size="45" autocomplete="off" /></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#f7faff"  height="30px">
                                                                <td valign="top" bgcolor="#f7faff" align="left">Type:</td>
                                                                <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><select name="type" class="swiftselect" id="type" style="width:200px">
                                                                          <option value="" selected="selected">Type Selection</option>
                                                                          <?php 
											include('../inc/custype.php');
											?>
                                                                        </select></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#edf4ff"  height="30px">
                                                                <td valign="top" bgcolor="#edf4ff" align="left">Category:</td>
                                                                <td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><select name="category" class="swiftselect" id="category"  style="width:200px">
                                                                          <option value="">Category Selection</option>
                                                                          <?php 
											include('../inc/category.php');
											?>
                                                                        </select></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#f7faff"  height="30px">
                                                                <td valign="top" bgcolor="#f7faff" align="left">Region:</td>
                                                                <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><select name="region" class="swiftselect-mandatory" id="region" style="width:200px">
                                                                          <option value="">Select A Region</option>
                                                                          <?php 
											include('../inc/region.php');
											?>
                                                                        </select></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#edf4ff"  height="30px">
                                                                <td width="24%"  valign="top" align="left">Current Dealer:</td>
                                                                <td width="76%" valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><select name="dealer" class="swiftselect" id="dealer" style="width:180px;">
                                                                          <option value="">Make A Selection</option>
                                                                          <?php 
											include('../inc/firstdealer.php');
											?>
                                                                        </select></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr bgcolor="#f7faff"  height="30px">
                                                                <td valign="top" bgcolor="#f7faff" align="left">Branch:</td>
                                                                <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                    <tr>
                                                                      <td align="left"><select name="branch" class="swiftselect-mandatory" id="branch" style="width:200px">
                                                                          <option value="">Select A Branch</option>
                                                                          <?php 
											include('../inc/branch.php');
											?>
                                                                        </select></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="right"></td>
                                                  </tr>
                                                </table>
                                              </form></td>
                                          </tr>
                                        </table>
                                                    </div>
                                                    <div style="display:none; " align="justify" id="tabg1c2">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="0" class="productcontent" style="height:280px">
                                                        <tr>
                                                          <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"  >
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
    <td width="5%"><div align="center"><table width="100%" border="0" cellspacing="0" cellpadding="2">
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
        <option value="others" >Others</option>
      </select>
                                                                              <input type="hidden" name="fromtype" id="fromtype" />
                                                                              <input type="hidden" name="totype" id="totype" />
                                                                              <input type="hidden" name="fromtypename" id="fromtypename" />
                                                                              <input type="hidden" name="totypename" id="totypename" /></td>
                                                                          </tr>
                                                                          <tr>
                                                                            <td id="m_type" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
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
                                                                      </table>
    </div></td>
    <td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv1" onclick ="removedescriptionrows('removedescriptionrow1','1')" style="cursor:pointer;">X</a></strong></font>
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
                                              </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                        <tr height="35px">
                                                          <td width="75%" align="left" ><div id="form-error1" style="padding:0px"></div></td>
                                                          <td width="25%" align="right" ><input name="merge" type="button" class= "swiftchoicebutton" id="merge" value="Merge" onclick="javascript:mergecustomers();" />
                                                            &nbsp;&nbsp;&nbsp;
                                                            <input name="clear" type="reset" class= "swiftchoicebutton" id="clear" value="Cancel" onclick="clearform()" /></td>
                                                        </tr>
                                                      </table></td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                              </table></td>
                      </tr>
                    </table>
                  </div></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<?php }?>
