<?php
if($p_productshippedreports <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/productshipped.js?dummy=<?php echo (rand());?>"></script>
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
                            <td width="27%" class="active-leftnav">Product Shipped Details</td>
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
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" align="left">From Date:</td>
                                            <td valign="top" bgcolor="#f7faff" align="left"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" />                                              <input type="hidden" name="flag" id="flag" value="true" />
                                            <br /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">To Date:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left"><input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" />                                           </td>
                                          </tr>
                                          
                                          <tr bgcolor="#F7FAFF">
                                            <td colspan="2" valign="top"><fieldset style="border:1px solid #666666; padding:2px;">
                                            <legend>Geography</legend>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="3" height="100">
                                              <tr>
                                                <td valign="top" align="left"><label for="geography1">
                                                    <input type="radio" name="geography" id="geography1" value="all"  checked="checked" onclick="enablegeography();" />
                                                      All </label><label for="geography2">
                                                  <input name="geography" type="radio" id="geography2" value="region" onclick="enablegeography();" />
                                                  Region </label>
                                                    <label for="geography3">
                                                    <input type="radio" name="geography" id="geography3" value="state" onclick="enablegeography();" />
                                                      State </label><label for="geography4">
                                                    <input type="radio" name="geography" id="geography4" value="district" onclick="enablegeography();" />
                                                      District </label></td>
                                              </tr>
                                              <tr>
                                                <td valign="top"><div id="regiondiv" style="display:none;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    <tr>
                                                      <td width="29%" align="left">Select A Region:&nbsp;&nbsp;</td>
                                                      <td width="71%" align="left"><select name="region" class="swiftselect-mandatory" id="region" disabled="disabled">
                                                        <option value="">Select A Region</option>
                                                        <?php include('../inc/region.php'); ?>
                                                      </select></td>
                                                    </tr>
                                                  </table></div>
                                                <div id="statediv" style="display:none;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                      <tr>
                                                        <td width="29%" align="left">Select A State: </td>
                                                        <td width="71%" align="left"><select name="state" class="swiftselect-mandatory" id="state" onchange="districtcodeFunction();" disabled="disabled">
                                                          <option value="">Select A State</option>
                                                          <?php include('../inc/state.php'); ?>
                                                        </select></td>
                                                      </tr>
                                                    </table>
                                                    <div id="districtdiv" style="display:none;">
                                                      
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td width="29%" align="left">Select A District:</td>
                                                          <td width="71%" align="left" id="districtcodedisplay"><select name="district" class="swiftselect-mandatory" id="district" disabled="disabled">
                                                            <option value="">Select A State First</option>
                                                          </select></td>
                                                        </tr>
                                                      </table>
                                                    </div>
                                                </div></td>
                                              </tr>
                                            </table>
                                            </fieldset></td>
                                          </tr>
                                         

                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr>
                                                <td width="49%" valign="top"><fieldset style="border:1px solid #666666; padding:2px;">
                                                  <legend>Group on</legend>
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    <tr>
                                                      <td align="left"><label for="groupon0">
                                                        <input name="groupon" type="radio" id="groupon0" value="product" checked="checked" />
                                                        Product </label>
                                                          <br />
                                                          <label></label>
                                                          <label for="groupon2">
                                                          <input type="radio" name="groupon" id="groupon2" value="dealer" />
                                                            Dealer</label>
                                                        <br />
                                                          <label></label></td>
                                                    </tr>
                                                  </table>
                                                </fieldset></td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Product:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left">
                                              <select name="productcode" class="swiftselect-mandatory" id="productcode">
                                                <option value="">ALL</option>
                                                <?php include('../inc/firstproduct.php'); ?>
                                              </select>                                            </td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td valign="top" bgcolor="#F7FAFF" align="left">Dealer:</td>
                                            <td valign="top" bgcolor="#F7FAFF" align="left">
                                              <select name="dealerid" class="swiftselect-mandatory" id="dealerid" style=" width:225px">
                                                <option value="">ALL</option>
                                                <?php include('../inc/firstdealer.php'); ?>
                                              </select>                                            </td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF" align="left">Bill Number:</td>
                                            <td valign="top" bgcolor="#EDF4FF" align="left">
                                              
                                             <input name="billnumberfrom" type="text" class="swifttext" id="billnumberfrom" size="15"  autocomplete="off"/>
                                             &nbsp;
                                             To&nbsp;
                                             <input name="billnumberto" type="text" class="swifttext" id="billnumberto" size="15"  autocomplete="off"/></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#F7FAFF" align="left">Multi Login:</td>
                                            <td valign="top" bgcolor="#F7FAFF" align="left"><input type="checkbox" name="multilogin" id="multilogin" /></td>
                                          </tr>

                                          <!--<tr bgcolor="#edf4ff">
                                            <td height="19" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                            <td valign="top" bgcolor="#F7FAFF" id="passwordfield2">&nbsp;</td>
                                          </tr>-->
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          
                                          <tr>
                                            <td width="72%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="28%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="formsubmit('view');" />
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
<?php } ?>