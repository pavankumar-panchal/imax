<?php
if($p_notregisteredreport <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/notregistered.js?dummy=<?php echo (rand());?>"></script>
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
                            <td  class="active-leftnav1">Report - Not registered (Ageing)</td>
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
                                  
                                    <tr><td colspan="2" height="10px" ></td></tr>     
                                     <tr>
                                       <td colspan="2" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                <tr>
                                                  <td width="6%" valign="top" ><label for="alltime">All Time</label></td>
                                                  <td width="10%" valign="top" style="border-right:1px solid #d1dceb;" ><input name="alltime" type="checkbox" id="alltime" onclick="disablethedates()" /></td>
                                                  <td width="9%" valign="top" style="border-right:1px solid #d1dceb;" >From Date:</td>
                                                  <td width="25%" valign="top" style="border-right:1px solid #d1dceb;" ><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" /></td>
                                                  <td width="7%" valign="top" >To Date:</td>
                                                  <td width="43%" valign="top" ><input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/></td>
                                                </tr>
                                              </table></td>
                                    </tr>       
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          
                                          <tr bgcolor="#EDF4FF">
                                            <td valign="top" bgcolor="#edf4ff" align="left">Branch:</td>
                                            <td valign="top" bgcolor="#edf4ff" align="left"><select name="branch" class="swiftselect" id="branch" style=" width:225px">
                                                <option value="">ALL</option>
                                                <?php include('../inc/branch.php'); ?>
                                              </select><input type="hidden" name="flag" id="flag" value="true" />                                            </td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#f7faff" align="left">Region:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><select name="region" class="swiftselect" id="region"  style=" width:225px">
                                                <option value="">ALL</option>
                                                <?php include('../inc/region.php'); ?>
                                              </select>                                            </td>
                                          </tr>
                                            <tr bgcolor="#edf4ff">
                                            <td valign="top" bgcolor="#edf4ff" align="left">Dealer:</td>
                                            <td valign="top" bgcolor="#edf4ff" align="left">
                                              <select name="dealerid" class="swiftselect" id="dealerid" style=" width:225px;">
                                                <option value="">ALL</option>
                                                <?php include('../inc/firstdealer.php'); ?>
                                              </select>                                            </td>
                                          </tr>
                                           <tr>
                                                                            <td bgcolor="#f7faff" align="left"> Generated by:</td>
                                                                            <td bgcolor="#f7faff" align="left" valign="top"   height="10" ><select name="generatedby" class="swiftselect" id="generatedby" style="width:225px;">
                                                                                <option value="">ALL</option>
                                                                                <?php include('../inc/generatedby.php');?>
                                                                              </select></td>
                                                                          </tr>
                                          <tr><td colspan="2"><fieldset style="border:1px solid #8AC5FF; padding:3px;">
                                          <legend><strong>Worksheets</strong> </legend>
                                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                <tr>
                                                 <td  align="left"><label for="customerwise"><strong>
                                                    <input checked="checked" name="summarize[]" type="checkbox" id="customerwise" value="customerwise"/>
                                                  </strong>Customer Wise</label></td>
                                                  <td align="left"><label for="dealerwise"><strong>
                                                    <input checked="checked" name="summarize[]" type="checkbox" id="dealerwise" value="dealerwise"/>
                                                  </strong>Dealer Wise</label></td>
                                                  <td  align="left"><label for="regionwise"><strong>
                                                    <input checked="checked" name="summarize[]" type="checkbox" id="regionwise" value="regionwise"/>
                                                  </strong>Region Wise</label></td>
                                                  <td align="left"><label for="branchwise"><strong>
                                                    <input checked="checked" name="summarize[]" type="checkbox" id="branchwise" value="branchwise"/>
                                                  </strong>Branch Wise</label></td>
                                                </tr>
                                              </table>
                                              </fieldset></td></tr>
                                        </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" bgcolor="#EDF4FF" align="left"><strong>Products </strong></td>
                                          </tr >
                                          <tr bgcolor="#f7faff" >
                                            <td colspan="4" valign="top" bgcolor="#f7faff" align="left"><div style="height:227px; overflow:scroll">
                                            <?php include('../inc/productdetails.php'); ?>
                                          </div></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                          <td width="12%">Select: </td>
                                          <td width="31%" align="left"><strong>
                                            <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:140px" >
                                              <option value="ALL" selected="selected">ALL</option>
                                              <option value="NONE" >NONE</option>
                                              <?php include('../inc/productgroup.php') ?>
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
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle"><input name="toexcel" type="button" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="formsubmit();" />
                                              &nbsp;&nbsp;<input name="clear" type="button" class="swiftchoicebutton" id="clear" value="Reset" onclick="resetDefaultValues(this.form)" /></td>
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
<?php } ?>