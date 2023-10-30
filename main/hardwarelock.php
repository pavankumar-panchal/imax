<?php
if($p_hardwarelock <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/hardwarelock.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>

<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="middle" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="customerselectionprocess" align="left" style="padding:0">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="refreshcustomerarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" border="0" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onkeyup="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <span style="display:none1">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled"/>
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
                            <td width="27%" align="left" class="active-leftnav">Customer Hardwarelock Details</td>
                            <td width="40%"><div align="right">Search By Hardware lock Number:</div></td>
                            <td width="33%"><div align="right">
                                <input name="searchhardwareno" type="text" class="swifttext" id="searchhardwareno" onkeyup="searchbycontractidevent(event);"  style="width:200px" maxlength="40"  autocomplete="off"/>
                                <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbycontractid(document.getElementById('searchhardwareno').value);" style="cursor:pointer" /> </div></td>
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
                            <td colspan="2" valign="top">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td valign="top" width="50%"style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                <tr>
                                                  <td align="left" valign="top" bgcolor="#EDF4FF">Business Name</td>
                                                  <td align="left" valign="top" bgcolor="#EDF4FF" id="displaycustomername" >&nbsp;
                                                    <input type="hidden" name="lastslno" id="lastslno"> 
                                                    <input type="hidden" name="cuslastslno" id="cuslastslno" /></td>
                                                </tr>
                                                <tr>
                                                  <td align="left" valign="top" bgcolor="#f7faff">Hardware Lock number:</td>
                                                  <td align="left" valign="top" bgcolor="#f7faff" ><input name="hardwareno" type="text" class="swifttext-mandatory" id="hardwareno"  style="width:200px" maxlength="10"  autocomplete="off"/>
                                                    <input type="hidden" id="hwlrecordno" name="hwlrecordno" />
                                                    <input type="hidden" id="lockno" name="lockno" /></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td align="left" valign="top" bgcolor="#edf4ff">Dealer:</td>
                                                  <td align="left" valign="top" bgcolor="#edf4ff"><select name="dealer" class="swiftselect-mandatory" id="dealer" style="width:200px;">
                                                      <option value="">Make A Selection</option>
                                                      <?php 
											include('../inc/firstdealer.php');
											?>
                                                    </select></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td align="left" valign="top" bgcolor="#f7faff">Date of Issue:</td>
                                                  <td align="left" valign="top" bgcolor="#f7faff"><input name="startdate" type="text" class="swifttext-mandatory"  id="DPC_startdate"  style="width:200px" maxlength="10" readonly="readonly"   autocomplete="off"  value="<?php echo(datetimelocal('d-m-Y')); ?>" />                                                  </td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td align="left" valign="top" bgcolor="#edf4ff">Entered By:</td>
                                                <td align="left" valign="top" bgcolor="#edf4ff" id="displayenteredby"><?php $fetch = runmysqlqueryfetch("SELECT fullname FROM inv_mas_users WHERE slno = '".imaxgetcookie('userid')."'"); echo($fetch['fullname']); ?>                                                </tr>
                                              </table></td>
                                            <td valign="top" width="50%"><table width="100%" height="90" border="0" cellpadding="4" cellspacing="0">
                                                <tr bgcolor="#EDF4FF">
                                                  <td align="left" valign="top">Bill Number:</td>
                                                  <td align="left" valign="top"><input name="billno" type="text" class="swifttext" id="billno"  style="width:200px" maxlength="10"  autocomplete="off"/></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td align="left" valign="top" bgcolor="#f7faff">Bill Total:</td>
                                                  <td align="left" valign="top" bgcolor="#f7faff" ><input name="billamount" type="text" class="swifttext" id="billamount"  style="width:200px" autocomplete="off" /></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td align="left" valign="top" bgcolor="#EDF4FF">Remarks:</td>
                                                  <td align="left" valign="top" bgcolor="#EDF4FF"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks"></textarea></td>
                                                </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td align="left" valign="top" bgcolor="#f7faff">Created Date/Time:</td>
                                                  <td align="left" valign="top" bgcolor="#f7faff"  id="updateddate">Not Available</td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="4" >
                                          <tr>
                                            <td width="39%" rowspan="15" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td valign="top" class="swiftselectfont" style="padding-left:9px" >Add from this List</td>
                                                </tr>
                                                <tr>
                                                  <td valign="top"><!--<input name="productsearchtext" type="text" class="swifttext" id="productsearchtext" size="32" onkeyup="productsearch(event);"  autocomplete="off"/>
                          <span style="display:none1">
                          <input name="productsearchid" type="hidden" id="productsearchid"  disabled="disabled"/>
                          </span-->
                                                  <select name="productlist" size="5" class="swiftselect" id="productlist" style="width:210px; height:200px;" >
                                                      <?php
$query = "SELECT productcode,productname FROM inv_mas_product order by productname;";
$result = runmysqlquery($query);
$productlistoptions = '';
while($fetch = mysqli_fetch_array($result))
{
	$productlistoptions .= '<option value="'.$fetch['productcode'].'"  ondblclick="addentry(\''.$fetch['productcode'].'\')">'.$fetch['productname'].'</option>';
}
echo($productlistoptions);
?>
                                                    </select></td>
                                                </tr>
                                              </table></td>
                                            <td width="22%" >&nbsp;</td>
                                            <td width="39%" rowspan="15" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td style="padding-left:25px"  class="swiftselectfont" >Selected Product</td>
                                                </tr>
                                                <tr>
                                                  <td ><select name="selectedproducts" size="5" class="swiftselect" id="selectedproducts" style="width:210px; height:200px" >
                                                    </select></td>
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
                                          <tr>
                                            <td><div align="left">
                                                <input name="add" type="button" class= "swiftchoicebutton" id="add" value="Add &gt;&gt;" onclick="addentry()" />
                                              </div></td>
                                          </tr>
                                          <tr>
                                            <td>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td><div align="left">
                                                <input name="remove" type="button" class= "swiftchoicebutton" id="remove" value="&lt;&lt; Remove" onclick="deleteentry()" />
                                              </div></td>
                                          </tr>
                                          <tr>
                                            <td>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td>
                                              <div align="left">
                                                <input name="removeall" type="button" class= "swiftchoicebutton" id="removeall" value="Remove All" onclick="deleteallentry()" />
                                              </div></td></tr>
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
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="44%" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onClick="newentry();document.getElementById('form-error').innerHTML='';" />
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
                                              &nbsp;
                                              <input name="delete" type="submit" class="swiftchoicebuttondisabled" id="delete" value="Delete" disabled="disabled" onClick="formsubmit('delete')"/></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="179" align="left"  style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Hardware Lock List</div></td>
                                  <td width="367" style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                                  <td width="186" style="padding:0">&nbsp;</td>
                                </tr>
                               <tr>
                                  <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:704px; padding:2px; " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" align="left" ></div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="custresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>                                   </td>
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
<script>
gettotalcustomercount();
</script>
<?php } ?>