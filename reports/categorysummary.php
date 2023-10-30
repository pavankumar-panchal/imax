
<?php
if($p_updationsummaryreport <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/categorysummary.js?dummy=<?php echo (rand());?>"></script>
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
                            <td  class="active-leftnav">Report - Category Summary 
                            </td>
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
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Make A Report                            </td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;" width="50%"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td ><table width="100%" border="0" cellspacing="0" cellpadding="3">
      <tr bgcolor="#EDF4FF">
        <td width="23%" align="left" valign="top" bgcolor="#EDF4FF">Region:</td>
        <td width="77%" align="left" valign="top" bgcolor="#EDF4FF"><select name="region" class="swiftselect-mandatory" id="region" style=" width:225px">
            <option value="">ALL</option>
            <?php include('../inc/region.php'); ?>
        </select>
          <input type="hidden" name="flag" id="flag" value="true" /></td>
      </tr>
      
      <tr bgcolor="#EDF4FF">
        <td valign="top" bgcolor="#EDF4FF" align="left">Branch:</td>
        <td valign="top" bgcolor="#EDF4FF" align="left"><select name="branch" class="swiftselect-mandatory" id="branch" style=" width:225px">
          <option value="">ALL</option>
          <?php include('../inc/branch.php'); ?>
        </select></td>
      </tr>
      <tr bgcolor="#EDF4FF">
        <td valign="top" bgcolor="#EDF4FF" align="left">Current Dealer:</td>
        <td valign="top" bgcolor="#EDF4FF" align="left"><select name="dealerid" class="swiftselect-mandatory" id="dealerid" style=" width:225px">
          <option value="">ALL</option>
          <?php include('../inc/firstdealer.php'); ?>
        </select></td>
      </tr>
    </table></td>
    <td valign="top" width="50%" bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr >
    <td><table width="100%" border="0" cellspacing="0" cellpadding="4">
      <tr>
        <td width="21%" valign="top">Product Group: </td>
        <td width="79%" valign="top"><label for"all"><input type="radio" name="productgroup" id="all" value="ALL" /> ALL </label><br/><label for"sto"><input type="radio" name="productgroup" id="sto" value="sto" />
          STO</label><br />
         <label for"svh"> <input type="radio" name="productgroup" id="svh" value="svh" />
          SVH</label><br />
          <label for"svi"><input type="radio" name="productgroup" id="svi" value="svi"/>
          SVI</label><br />
         <label for"tds"> <input type="radio" name="productgroup" id="tds" value="tds"/>
          TDS</label><br />
         <label for"spp"> <input type="radio" name="productgroup" id="spp" value="spp"  checked="checked"/>
          SPP</label><br />
         <label for"xbrl"> <input type="radio" name="productgroup" id="xbrl" value="xbrl"/>
          XBRL</label></td>
      </tr>

    </table></td>
  </tr>
</table>
</td>
  </tr>
</table>
</td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          
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
<?php } ?>
