<?
if($p_scheme <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/scheme.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>

<? $userid = imaxgetcookie('userid');?>
<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" class="active-leftnav">Scheme Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="schemeselectionprocess" align="left" style="padding:0">&nbsp;</td>
                        <td width="29%" style="padding:0"></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"  onkeyup="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <span style="display:none1">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled"/>
                          </span>
                          <div id="detailloadcustomerlist">
                            <select name="schemelist" size="5" class="swiftselect" id="schemelist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();"  >
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
                <td width="55%" id="totalcount">&nbsp;</td>
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
                            <td width="27%" align="left" class="active-leftnav">Scheme Details</td>
                            <td width="40%"><div align="right">Search By Scheme ID:</div></td>
                            <td width="33%"><div align="right">
                                <input name="searchschemeid" type="text" class="swifttext" id="searchschemeid" onkeyup="searchbyschemeidevent(event);" maxlength="40"  autocomplete="off" style="width:200px"/>
                                <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="schemedetailstoform(document.getElementById('searchschemeid').value);" style="cursor:pointer" /> </div></td>
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
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr>
                                        <td>Scheme Name</td>
                                        <td><input name="schemename" type="text" class="swifttext-mandatory" id="schemename" size="30" autocomplete="off" /><input type="hidden" name="lastslno" id="lastslno" /> </td></tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Description:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><textarea name="schemedescription" cols="27" class="swifttextarea" id="schemedescription"></textarea></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Disable Scheme:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><label>
                                              <input type="checkbox" name="disablescheme" id="disablescheme" />
                                            </label></td>
                                          </tr>
                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#edf4ff">
                                            <td width="25%" align="left" valign="top" bgcolor="#EDF4FF">From Date:</td>
                                            <td width="75%" align="left" valign="top" bgcolor="#EDF4FF"><input name="startdate" type="text" class="swifttext-mandatory" id="DPC_startdate" size="30" maxlength="10"  autocomplete="off" readonly="readonly"/></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td height="19" align="left" valign="top" bgcolor="#F7FAFF">To Date:</td>
                                            <td align="left" valign="top" bgcolor="#F7FAFF" ><input name="enddate" type="text" class="swifttext-mandatory" id="DPC_enddate" size="30" maxlength="10"  autocomplete="off" readonly="readonly"/></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td height="19" align="left" valign="top" bgcolor="#EDF4FF">Entered By:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="enteredby" >&nbsp;</td>
                                          </tr>

                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0" height="70">

                                          <tr>
                                            <td width="53%" height="35" align="left" valign="middle" ><div id="form-error"></div></td>
                                            <td width="47%" height="35" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onclick="newschemeentry(); document.getElementById('form-error').innerHTML = '';" />
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onclick="formsubmit('save');" />
                                              &nbsp;
                                              <input name="delete" type="submit" class="swiftchoicebuttondisabled" id="delete" value="Delete" onclick="formsubmit('delete');" disabled="disabled"/>
&nbsp;                                              </td>
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
                      <td></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td align="left"  style="padding:0"><div id="tabdescription">&nbsp; Scheme Details</div></td>
                                  <td style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                                  <td align="left"  style="padding:0">&nbsp;</td>
                                  <td align="left"  style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="left" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center"></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link"  align="left" style="height:20px; padding:2px;">
</div></td>
  </tr>
</table>
</div><div id="resultgrid" style="overflow:auto; display:none; height:150px;padding:2px;width:704px;" align="center">&nbsp;</div>
                                    
                                    
                                    
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
<script>
refreshschemearray();
</script>
<? } ?>