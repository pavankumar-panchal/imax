<?
if($p_updationdetailsreport <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/updationdue.js?dummy=<? echo (rand());?>"></script>
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
                            <td width="27%" class="active-leftnav">Report - Updation Due Details</td>
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
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr bgcolor="#f7faff">
                                          <td valign="top" bgcolor="#EDF4FF" align="left">Compare With: </td>
                                          <td valign="top" bgcolor="#EDF4FF" align="left"><select name="compareyear" class="swiftselect-mandatory" id="compareyear" >
                                              <option value="alltime" selected="selected">All Time</option>
                                              <option value="alltimecurrent">All Time + Current Year</option>
                                              <option value="previousyear">Only Previous Year</option>
                                          </select></td>
                                        </tr>
                                        <tr bgcolor="#f7faff">
                                          <td valign="top" bgcolor="#f7faff" align="left">Region: </td>
                                          <td valign="top" bgcolor="#f7faff" align="left"><select name="region" class="swiftselect-mandatory" id="region" >
                                              <option value="">ALL</option>
                                              <? include('../inc/region.php'); ?>
                                          </select></td>
                                        </tr>
                                     
                                          
                                          <tr bgcolor="#f7faff">
                                            <td width="22%" valign="top" bgcolor="#EDF4FF" align="left">Branch: </td>
                                            <td width="78%" valign="top" bgcolor="#EDF4FF" align="left"><select name="branch" class="swiftselect-mandatory" id="branch" style=" width:225px">
                                              <option value="">ALL</option>

                                              <? include('../inc/branch.php'); ?>
                                            </select></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#f7faff" align="left">Dealer:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="dealerid" class="swiftselect-mandatory" id="dealerid" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/firstdealer.php'); ?>
                                              </select>
                                                <input type="hidden" name="flag" id="flag" value="true" /></td>
                                          </tr>
                                          <tr>
                                            <td bgcolor="#EDF4FF">State:</td>
                                            <td bgcolor="#EDF4FF"><select name="state2" class="swiftselect-mandatory" id="state2"  style="width:225px;">
                                                <option value="">ALL</option>
                                                <? include('../inc/state.php'); ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td bgcolor="#f7faff">Type:</td>
                                            <td bgcolor="#f7faff"><select name="type2" class="swiftselect-mandatory" id="type2" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/custype.php'); ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td bgcolor="#f7faff">Category:</td>
                                            <td bgcolor="#f7faff"><select name="category2" class="swiftselect-mandatory" id="category2" style=" width:225px">
                                                <option value="">ALL</option>
                                                <? include('../inc/category.php'); ?>
                                            </select></td>
                                          </tr>
                                        
                                          <tr>
                                            <td colspan="2" align="left" valign="top" bgcolor="#EDF4FF"  ><strong>Duplicate Contacts For</strong></td>
                                          </tr>
                                         <tr bgcolor="#f7faff">
                                            <td  valign="top" bgcolor="#EDF4FF" align="left"></td>
                                            <TD align="left" bgcolor="#EDF4FF"><label for="none"><input type="radio" name="contact_type" id="none" value="none" checked="checked" />
None</label></TD>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td  valign="top" bgcolor="#EDF4FF">&nbsp;</td>
                                            <TD align="left" bgcolor="#EDF4FF"><label for="uniquemailid"><input type="radio" name="contact_type" id="uniquemailid" value="uniquemailid" />
Unique Email Id's</label></TD>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td  valign="top" bgcolor="#EDF4FF">&nbsp;</td>
                                            <TD align="left" bgcolor="#EDF4FF"><label for="uniquecellno"><input type="radio" name="contact_type" id="uniquecellno" value="uniquecellno" />
                                              Unique Mobile No's</label></TD>
                                          </tr>
                                         

                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td width="22%" align="left" valign="top" bgcolor="#EDF4FF">Product Group:</td>
                                            <td width="78%" align="left" valign="top" bgcolor="#EDF4FF"><label for="sto"><input type="checkbox" name="productgroup[]" id="sto" value ="sto" />
                                            &nbsp;STO</label><br/>
                                            <label for="svh"><input type="checkbox" name="productgroup[]" id="svh" value ="svh" />
                                            &nbsp;SVH</label><br/>
                                            <label for="svi"><input type="checkbox" name="productgroup[]" id="svi" value ="svi" />
                                            &nbsp;SVI</label><br/>
                                            <label for="gst"><input type="checkbox" name="productgroup[]" id="gst" value ="gst" />
                                            &nbsp;GST</label><br/>
                                            <label for="tds"><input type="checkbox" name="productgroup[]" id="tds" value ="tds" />
                                            &nbsp;TDS<br/></label>
                                            <label for="spp"><input type="checkbox" name="productgroup[]" id="spp" value ="spp" />
                                            &nbsp;SPP<br/></label>  <label for="xbrl"><input type="checkbox" name="productgroup[]" id="xbrl" value ="xbrl" />
                                            &nbsp;XBRL<br/></label>
                                            <!--added for billing and accounts report on 07-11-2017-->
                                             <label for="sac"><input type="checkbox" name="productgroup[]" id="sac" value ="sac" />
                                            &nbsp;SAC<br/></label></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td colspan="2" align="left" valign="top" bgcolor="#f7faff"><fieldset style="border:1px solid #666666; padding:3px;">
                                            <legend>Order By</legend>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr>
                                                <td align="left"><label for="orderby0">
                                                  <input name="orderby" type="radio" id="orderby0" value="customerid" checked="checked" />
                                                  Customer ID</label>
                                                    <label for="orderby1">
                                                    <input type="radio" name="orderby" id="orderby1" value="companyname" />
                                                      Company Name</label>
                                                    <label for="orderby2">
                                                    <input type="radio" name="orderby" id="orderby2" value="state" /> 
                                                    State</label>
                                                    <label for="orderby3">
                                                    <input type="radio" name="orderby" id="orderby3" value="region" /> 
                                                    Region</label>
                                                    <label for="orderby4">
                                                    <input type="radio" name="orderby" id="orderby4" value="branch" />
Branch  </label>
<label for="orderby5">
<input type="radio" name="orderby" id="orderby5" value="dealer" />
Dealer </label></td>
                                              </tr>
                                                                                        </table>
                                            </fieldset></td>
                                          </tr>
                                           <tr>
                                             <td colspan="2" bgcolor="#EDF4FF"><fieldset style="border:1px solid #666666; padding:3px;">
                                             <legend>Split Sheet By</legend>
                                             <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                               <tr>
                                                 <td align="left">
                                                 <label for="splitby4">
                                                       <input type="radio" name="splitby" id="splitby4" value="none"  checked="checked"/>
None </label>
                                                 <label for="splitby0">
                                                     <input type="radio" name="splitby" id="splitby0" value="state" />
                                                       State</label>
                                                     <label for="splitby1">
                                                     <input type="radio" name="splitby" id="splitby1" value="region" />
                                                       Region</label>
                                                     <label for="splitby2">
                                                     <input type="radio" name="splitby" id="splitby2" value="branch" />
                                                       Branch </label>
                                                     <label for="splitby3">
                                                     <input type="radio" name="splitby" id="splitby3" value="dealer" />
                                                       Dealer </label></td>
                                               </tr>
                                                                                           </table>
                                             </fieldset></td>
                                           </tr>
                                       </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="formsubmit('view');" />
                                              &nbsp;
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