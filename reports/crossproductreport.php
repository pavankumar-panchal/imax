<?php
if($p_crossproductreport <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/crossproductreport.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
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
                            <td width="27%" class="active-leftnav">Report - Cross Product Sales</td>
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
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr valign="top">
                                            <td width="56%"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border-right:1px solid #d1dceb;">
                                                <tr>
                                                  <td bgcolor="#EDF4FF">Dealer:</td>
                                                  <td bgcolor="#EDF4FF"><select name="dealerid" class="swiftselect-mandatory" id="dealerid" style=" width:225px">
                                                      <option value="">ALL</option>
                                                      <?php include('../inc/firstdealer.php'); ?>
                                                    </select>
                                                      <input type="hidden" name="flag" id="flag" value="true" /></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#f7faff">State:</td>
                                                  <td bgcolor="#f7faff"><select name="state" class="swiftselect-mandatory" id="state"  style="width:225px;">
                                                <option value="">ALL</option>
                                                <?php include('../inc/state.php'); ?>
                                              </select></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#EDF4FF">Region:</td>
                                                  <td bgcolor="#EDF4FF"><select name="region" class="swiftselect-mandatory" id="region" >
                                                      <option value="">ALL</option>
                                                      <?php include('../inc/region.php'); ?>
                                                  </select></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#f7faff">Active Customer:</td>
                                                  <td bgcolor="#f7faff"><label for="activecustome_yes">
                                                    <input type="radio" name="activecustomer_type" id="activecustome_yes" value="yes"  />
                                                    Yes</label>
                                                      <label for="activecustome_no">
                                                      <input type="radio" name="activecustomer_type" id="activecustome_no" value="no"  />
                                                        No</label>
                                                      <label for="activecustome_both">
                                                      <input type="radio" name="activecustomer_type" id="activecustome_both" value="" checked="checked" />
                                                        Both</label></td>
                                                </tr>
                                                <tr>
                                                  <td valign="top" bgcolor="#EDF4FF">Report Type:</td>
                                                  <td bgcolor="#EDF4FF"><label for="report_nop">
                                                    <input type="radio" name="report_type" id="report_nop" value="report_nop" checked="checked" />
                                                    Number of Products used</label><br />
                                                      <label for="report_fp">
                                                      <input type="radio" name="report_type" id="report_fp" value="report_fp"  />
                                                        First Product Based</label><br />
                                                      <label for="report_matrix">
                                                      <input type="radio" name="report_type" id="report_matrix" value="report_matrix" />
                                                  Product to Product Matrix</label><br />
                                                      <label for="report_matrix_productwise">
                                                      <input type="radio" name="report_type" id="report_matrix_productwise" value="report_matrix_productwise" />
                                                  Matrix (Product wise sheets)</label><br />
                                                   <label for="report_yearwise">
                                                      <input type="radio" name="report_type" id="report_yearwise" value="report_yearwise" />
                                                      Year wise Comparism
                                                   </label></td>
                                                </tr>
                                            </table></td>
                                            <td width="44%"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                <tr>
                                                  <td bgcolor="#EDF4FF">Branch:</td>
                                                  <td bgcolor="#EDF4FF"><select name="branch" class="swiftselect-mandatory" id="branch" style=" width:225px">
                                                      <option value="">ALL</option>
                                                      <?php include('../inc/branch.php'); ?>
                                                  </select></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#f7faff">Type:</td>
                                                  <td bgcolor="#f7faff"><select name="type" class="swiftselect-mandatory" id="type" style=" width:225px">
                                                      <option value="">ALL</option>
                                                      <?php include('../inc/custype.php'); ?>
                                                  </select></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#EDF4FF">Category:</td>
                                                  <td bgcolor="#EDF4FF"><select name="category" class="swiftselect-mandatory" id="category" style=" width:225px">
                                                      <option value="">ALL</option>
                                                      <?php include('../inc/category.php'); ?>
                                                  </select></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2" bgcolor="#f7faff"><input type="checkbox" name="includemainsheet" id="includemainsheet" value="yes" />
                                                <label for="includemainsheet">Include Customer Main Sheet (Takes time)</label></td>
                                                </tr>
                                            </table></td>
                                          </tr>
                                        </table>
                                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="43%" align="right" valign="middle">&nbsp;
                                                <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="formsubmit('toexcel');"/>
                                              &nbsp;</td>
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
