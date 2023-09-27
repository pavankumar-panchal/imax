<?
if($p_updationdetailedreport <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/updationdetailedreport.js?dummy=<? echo (rand());?>"></script>
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
                            <td width="27%" class="active-leftnav">Report - Customer Stats</td>
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
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr bgcolor="#EDF4FF">
                                          <td width="49%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                            <tr>
                                              <td width="30%">Product Group:</td>
                                              <td width="70%"><select name="group" class="swiftselect-mandatory" id="group" style=" width:225px">
                                                <option value="">Select A Group</option>
                                                <option value="sto">STO</option>
                                                <option value="svh">SVH</option>
                                                <option value="svi">SVI</option>
                                                <option value="tds">TDS</option>
                                                <option value="spp">SPP</option>
                                                 <option value="xbrl">XBRL</option>
                                                 <option value="gst">GST</option>
                                              </select>                                              </td>
                                            </tr>
                                            <tr>
                                              <td>Dealer:</td>
                                              <td><select name="dealerid" class="swiftselect-mandatory" id="dealerid" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/firstdealer.php'); ?>
                                              </select>
                                                <input type="hidden" name="flag" id="flag" value="true" /></td>
                                            </tr>
                                            <tr>
                                              <td>Region:</td>
                                              <td><select name="region" class="swiftselect-mandatory" id="region" >
                                                <option value="">ALL</option>
                                                <? include('../inc/region.php'); ?>
                                              </select></td>
                                            </tr>
                                                                          <tr>
                                              <td>Branch:</td>
                                              <td><select name="branch" class="swiftselect-mandatory" id="branch" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/branch.php'); ?>
                                              </select></td>
                                            </tr>
                                            <tr>
                                              <td>Type:</td>
                                              <td><select name="type" class="swiftselect-mandatory" id="type" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/custype.php'); ?>
                                              </select></td>
                                            </tr>
                                            <tr>
                                              <td>Category:</td>
                                              <td><select name="category" class="swiftselect-mandatory" id="category" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/category.php'); ?>
                                              </select></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td width="30%" align="left">Active Customer:</td>
                                                  <td width="70%" align="left"><label for="activecustome_yes">
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
                                          </table></td>
                                          <td width="51%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                               <tr><td width="17%" valign="top">Summarize: </td>
                                                 <td width="83%" valign="top"><input type="checkbox" name="summarize[]" id="regionwise" value="regionwise" checked="checked" />
Region Wise<br />
<input type="checkbox" name="summarize[]" id="branchwise" value="branchwise"  checked="checked"/>
Branch Wise<br />
<input type="checkbox" name="summarize[]" id="statewise" value="statewise"  checked="checked"/>
State Wise<br />
<input type="checkbox" name="summarize[]" id="dealerwise" value="dealerwise"  checked="checked"/>
Dealer Wise<br />
<input type="checkbox" name="summarize[]" id="customertypewise" value="customertypewise" checked="checked" />
Customer Type Wise<br />
<input type="checkbox" name="summarize[]" id="customercategorywise" value="customercategorywise" checked="checked"/>
Customer Category Wise</td>
                                               </tr>
                                               <tr>
                                                 <td colspan="2"><input type="checkbox" name="includemainsheet" id="includemainsheet" value="yes" />
                                                     <label for="includemainsheet">Include Customer Main Sheet (Takes time)</label></td>
                                               </tr>
                                              <td colspan="2" valign="top"><br /></td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                      </table>                                        
                                      <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td width="100%" valign="top" style="padding:0"></td>
                                          </tr>
                                          
                                          <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle">&nbsp;
                                            <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="formsubmit('toexcel');"/></td>
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