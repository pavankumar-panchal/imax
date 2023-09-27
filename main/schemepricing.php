<?
if($p_schemepricing <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/schemepricing.js?dummy=<? echo (rand());?>"></script>
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
                        <td width="29%" style="padding:0"></td><input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled"/>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"  onkeyup="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <span style="display:none1">
                          
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
                                <input name="searchschemeid" type="text" class="swifttext" id="searchschemeid" onkeyup="searchbyschemeidevent(event);"  maxlength="40"  autocomplete="off" style="width:200px"/>
                                <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbyschemeid(document.getElementById('searchschemeid').value);" style="cursor:pointer" /> </div></td>
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
                                      <td width="60%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr>
                                        <td width="23%" align="left">Scheme Name:</td>
                                        <td width="77%" id="schemename"  align="left"></td>
                                      </tr>
                                      <tr bgcolor="#F7FAFF">
                                        <td  align="left">Product:</td>
<td  align="left"><select name="productcode" class="swiftselect-mandatory" id="productcode" style="width:198px;" >
                                                                  <option value="">Select a Product</option>
                                                                  <? include('../inc/firstproduct.php'); ?>
                                                                </select> <input type="hidden" name="lastslno" id="lastslno" /><input type="hidden" name="schemelastslno" id="schemelastslno" /></td></tr>
                                      
                                      </table></td>
                                      <td width="40%"  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <? $query = "select * from inv_mas_users where slno = '".$userid."'";
									  	 $resultfetch = runmysqlqueryfetch($query);
										 $enteredby = $resultfetch['fullname'];
									?>
                                          <tr bgcolor="#EDF4FF">
                                            <td width="36%" height="27" align="left" valign="top" bgcolor="#EDF4FF">Entered By:</td>
                                            <td width="64%" align="left" valign="top" bgcolor="#EDF4FF" id="enteredby" ><? echo($enteredby); ?></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td height="19%" align="left" valign="top" bgcolor="#EDF4FF">&nbsp;</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF" id="enteredby2" >&nbsp;</td>
                                          </tr>

                                      </table></td>
                                    </tr>
                                   
                                    <tr><td colspan="2"><table width="100%" border="0" align="center" cellpadding="4" cellspacing="0"   class="table-border-grid">
                                      <tr bgcolor="#EDF4FF">
                                        <td width="15%" class="td-border-grid">Single User New</td>
                                        <td width="20%" class="td-border-grid">Single User Updation</td>
                                        <td width="13%" class="td-border-grid">Multi User New</td>
                                        <td width="19%" class="td-border-grid">Multi User Updation</td>
                                        <td width="13%" class="td-border-grid">Additional  New</td>
                                        <td width="18%" class="td-border-grid">Additional Updation</td>
                                      </tr>
                                      <tr bgcolor="#F7FAFF">
                                        <td class="td-border-grid"><div align="center">
                                          <input type="checkbox" name="usagetype[]" id="singleusernew" onclick="enabledisableamountfields();" />
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input type="checkbox" name="usagetype[]" id="singleuserupdation" onclick="enabledisableamountfields();" />
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input type="checkbox" name="usagetype[]" id="multiusernew" onclick="enabledisableamountfields();" />
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input type="checkbox" name="usagetype[]" id="multiuserupdation" onclick="enabledisableamountfields()"  />
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input type="checkbox" name="usagetype[]" id="additionalnew" onclick="enabledisableamountfields();" />
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input type="checkbox" name="usagetype[]" id="additionalupdation" onclick="enabledisableamountfields();" />
                                        </div></td>
                                      </tr>
                                      <tr bgcolor="#F7FAFF">
                                        <td class="td-border-grid"><div align="center">
                                          <input name="singleusernewamt" type="text" class=" swifttext-mandatory" id="singleusernewamt" size="12" maxlength="12"  autocomplete="off"  value="NA" style="text-align:center" disabled="disabled"/>
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input name="singleuserupdationamt" type="text" class=" swifttext-mandatory" id="singleuserupdationamt" size="12" maxlength="12"  autocomplete="off" value="NA"  style="text-align:center" disabled="disabled"/>
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input name="multiusernewamt" type="text" class=" swifttext-mandatory" id="multiusernewamt" size="12" maxlength="12"  autocomplete="off" value="NA" style="text-align:center" disabled="disabled"/>
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input name="multiuserupdationamt" type="text" class=" swifttext-mandatory" id="multiuserupdationamt" size="12" maxlength="12"  autocomplete="off" value="NA" style="text-align:center" disabled="disabled"/>
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input name="additionalnewamt" type="text" class=" swifttext-mandatory" id="additionalnewamt" size="12" maxlength="12"  autocomplete="off" value="NA" style="text-align:center" disabled="disabled"/>
                                        </div></td>
                                        <td class="td-border-grid"><div align="center">
                                          <input name="additionalupdationamt" type="text" class=" swifttext-mandatory" id="additionalupdationamt" size="12" maxlength="12"  autocomplete="off" value="NA" style="text-align:center" disabled="disabled"/>
                                        </div></td>
                                      </tr>
                                    </table></td></tr>
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
                                  <td width="117" align="left"  style="padding:0"><div id="tabdescription">&nbsp; Scheme Details</div></td>
                                  <td width="448" style="padding:0; text-align:center;"><span id="tabgroupgridwb1">&nbsp;</span></td>
                                  <td width="56" align="left" style="padding:0">&nbsp;</td>
                                  <td width="111" align="left"  style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center"></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link" style="height:20px; padding:2px;" align="left">
</div></td>
  </tr>
</table>
</div><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                    
                                    
                                    
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