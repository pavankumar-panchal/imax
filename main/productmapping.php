<?
if($p_producttodealer <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/productmapping.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>

<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" valign="middle" class="active-leftnav">Dealer Selection</td>
              </tr>
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onSubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="dealerselectionprocess" align="left" style="padding:0">&nbsp;</td>
                        <td width="29%"><div class="resendtext"><a onclick="displayfilterdiv()" style="cursor:pointer">Filter>></a></div></td>
                      </tr>
                      <tr><td colspan="2"><div id="displayfilter" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#f7faff" style=" border:1px solid #ADD8F1">
  <tr>
    <td colspan="2 "  align="left" valign="top"><strong>Relyon Exe:</strong><br />
      <label>
      <input type="radio" name="relyonexcecutive_type" id="relyonexcecutive0" value="yes" />
    Yes</label>
      <label>  <input type="radio" name="relyonexcecutive_type" id="relyonexcecutive1" value="no" />
    No</label>
      <label> <input name="relyonexcecutive_type" type="radio" id="relyonexcecutive2" value="" checked="checked" />
    All      </label></td>
    </tr>
  <tr>
    <td colspan="2"  align="left" valign="top"><strong>Login:
        <label>  </label>
    </strong>
      <label><br />
    <input name="login_type" type="radio" id="logintype0" value="no" checked="checked" />
      Enabled</label>
      <label>  <input type="radio" name="login_type" id="logintype1" value="yes" />
        Disabled</label>
      <label>  <input type="radio" name="login_type" id="logintype2" value="" />
        All</label></td>
    </tr>
  <tr>
    <td width="40%"  align="left" valign="top"><strong>Region:</strong><br /></td>
    <td width="60%"  align="left" valign="top"><select name="dealerregion" class="swiftselect-mandatory" id="dealerregion">
      <option value="">All</option>
      <? 
											include('../inc/region.php');
											?>
    </select></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><div align="right" class="resendtext"><a onclick="refreshdealerarray();" style="cursor:pointer">Load&gt;&gt;</a></div></td>
  </tr>
</table>
            </div></td></tr>
                      <tr>
              <td colspan="3" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onkeyup="dealersearch(event);" autocomplete="off" style="width:204px" />
                  <div id="detailloaddealerlist">
                    <select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:400px;" onchange="selectfromlist();" onclick="selectfromlist()">
                      <option></option>
                    </select>
                </div></td>
            </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td width="45%" style="padding-left:10px;">Total Count:</td>
                <td width="55%" id="totalcount">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
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
                          <td width="27%" align="left" class="active-leftnav">Product Mapping</td>
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
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#EDF4FF">
                                            <td width="38%" align="left" valign="top" bgcolor="#EDF4FF">Business Name:</td>
                                            <td width="62%" align="left" valign="top" bgcolor="#EDF4FF" id="dealerdisplay">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Product:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff"><select name="productcode" class="swiftselect-mandatory" id="productcode" style="width:198px;" >
                                                                  <option value="">Select a Product</option>
                                                                  <? include('../inc/firstproduct.php'); ?>
                                                                </select><input type="hidden" name="lastslno" id="lastslno"></td>
                                          </tr>
                                          
                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Date:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input name="creditdate" type="text" class="swiftselect-readonly" id="creditdate" size="30"  autocomplete="off" value="<? echo(datetimelocal('d-m-Y')." (".datetimelocal('H:i').")"); ?>" readonly="readonly"/></td>
                                          </tr>
                                          <? $query = " Select * from inv_mas_users where slno = '".$userid."'";
										  $fetch = runmysqlqueryfetch($query);
										  $enteredby = $fetch['fullname']; ?>
                                             <tr bgcolor="#f7faff">
                                               <td align="left" valign="top" bgcolor="#f7faff">Entered By:</td>
                                               <td align="left" valign="top" bgcolor="#f7faff" id="enteredby" ><? echo($enteredby); ?></td>
                                             </tr>
                                          
                                          <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>   <td colspan="2" align="left" valign="middle" height="25"  ><div id="form-error"></div></td></tr>
                                          <tr>
                                          
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-meg"></div></td>
                                            <td width="44%" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onClick="newentry(); document.getElementById('form-error').innerHTML = '';document.getElementById('form-meg').innerHTML = '';" />
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
                                              &nbsp;
                                              <input name="delete" type="submit" class="swiftchoicebuttondisabled" id="delete" value="Delete" onClick="formsubmit('delete');"/></td>
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
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="205" align="left"  style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Product Mapping Details</div></td>
                                  <td width="341"  style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                                  <td width="186" align="left" class="header-line" style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:250px; padding:2px;" align="center"><div id="districtresultgrid" style="overflow:auto; height:150px; width:704px; padding:2px; display:none;" align="center"></div><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1"  align="left"></div></td>
  </tr>
  <tr>
    <td><div id="getmoredistrictlink" align="left"></div></td>
  </tr>
</table></div>                                 </td>
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
refreshdealerarray();
</script>
<? } ?>