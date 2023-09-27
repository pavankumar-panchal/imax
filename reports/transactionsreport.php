<?php
if($p_transactionsreport <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/transactionsreport.js?dummy=<?php echo (rand());?>"></script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0 `" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" class="active-leftnav">Report - Transactions  Details</td>
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
                                          <tr>
                                            <td colspan="2"><strong>Surrendered Date</strong></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" align="left">From Date:</td>
                                            <td valign="top" bgcolor="#f7faff"  align="left"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_surrenderfromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly"/>
                                              <input type="hidden" name="flag" id="flag" value="true" />
                                              <br /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td valign="top" bgcolor="#EDF4FF"  align="left">To Date:</td>
                                            <td valign="top" bgcolor="#EDF4FF"  align="left"><input name="todate" type="text" class="swifttext-mandatory" id="DPC_surrendertodate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" /></td>
                                          </tr>
                                          <tr bgcolor="#edf4ff">
                                            <td colspan="2" valign="top" bgcolor="#F7FAFF" height="12px"></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><fieldset style="border:1px solid #666666; padding:2px;">
                                                <legend><strong>Options</strong></legend>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr bgcolor="#f7faff">
                                                    <td colspan="2" valign="top" style="padding:0"></td>
                                                  </tr>
                                                  <tr bgcolor="#EDF4FF">
                                                    <td valign="top" align="left">Response Message:</td>
                                                    <td valign="top" align="left"><select name="responsemessage" class="swiftselect" id="responsemessage"  style="width:225px;">
                                                    <option value="0">Both</option>
                                                        <option value="1">Success</option>
                                                        <option value="2">Unsuccess</option>
                                                       </select>
                                                        <!--<?php/*
														$query = "SELECT distinct(responsemessage) as msg from transactions order by responsemessage;";
														$result = runicicidbquery($query);
														while($fetch = mysqli_fetch_array($result))
														{
															if($fetch['msg'] <> '')
															{ 
																echo('<option value="'.$fetch['msg'].'">'.$fetch['msg'].'</option>');
															}
														}*/
														?>-->
                                                      </td>
                                                  </tr>
                                                  <tr bgcolor="#f7faff">
                                                    <td valign="top" align="left">Payment Mode:</td>
                                                    <td valign="top" align="left"><select name="paymentmode" class="swiftselect" id="paymentmode"  style=" width:225px" >
                                                        <option value="both">All</option>
                                                        <option value="card">Credit/Debit</option>
														 <option value="citrus">Citrus</option>
                                                         <option value="razorpay">Razorpay</option>
                                                      </select></td>
                                                  </tr>
                                                </table>
                                              </fieldset></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"  bgcolor="#EDF4FF">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" valign="top" bgcolor="#EDF4FF"><fieldset style="border:1px solid #666666; padding:2px;">
                                                <legend><strong>Serach </strong></legend>
                                                <table width="100%" border="0" cellspacing="0" cellpadding="3" height="100">
                                                  <tr align="center">
                                                    <td valign="top"  align="left"><label for="searchref1">
                                                        <input type="radio" name="searchref" id="searchref1" value="all"  checked="checked" onclick="enablesearchref();" disabled="disabled" />
                                                        All </label>
                                                      <label for="searchref0">
                                                        <input name="searchref" type="radio" id="searchref0" value="refslno" onclick="enablesearchref();" disabled="disabled" />
                                                        Record No.</label>
                                                      <input type="hidden" name="searchrefresult" id="searchrefresult" value="all" /></td>
                                                  </tr>
                                                  <tr>
                                                    <td valign="top"  align="left"><div id="searchdiv" style="display:none;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                          <tr>
                                                            <td width="29%" align="left"><span id="searchtextheader">Enter Text Here:&nbsp;&nbsp;</span></td>
                                                            <td width="71%" align="left"><label for="searchinput">
                                                                <input type="text" name="searchinput" id="searchinput" class="swifttext-mandatory" size="30" autocomplete="off" />
                                                              </label></td>
                                                          </tr>
                                                        </table>
                                                      </div>
                                                      <div id="maxcountdiv" style="display:none;">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                          <tr>
                                                            <td width="29%" align="left"><span id="maxcounttextheader">Enter Text Here:&nbsp;&nbsp;</span></td>
                                                            <td width="71%" align="left"><label for="maxcount">
                                                                <input type="text" name="maxcount" id="maxcount" class="swifttext-mandatory" size="30" autocomplete="off" />
                                                              </label></td>
                                                          </tr>
                                                        </table>
                                                      </div>
                                                  </tr>
                                                </table>
                                              </fieldset></td>
                                          </tr>
                                        </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" bgcolor="#EDF4FF" align="left"><strong>Products </strong></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="4" valign="top" bgcolor="#f7faff" align="left"><div style="height:327px; overflow:scroll">
                                                <?php include('../inc/product-report.php'); ?>
                                              </div></td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td width="10%">Select: </td>
                                            <td width="34%" align="left"><strong>
                                              <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:140px" >
                                                <option value="ALL" >ALL</option>
                                                <option value="NONE" selected="selected">NONE</option>
                                                <?php include('../inc/productgroup.php') ?>
                                              </select>
                                              </strong></td>
                                            <td width="56%" align="left"><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                            <input type="hidden" name="groupvalue" id="groupvalue"  />
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
<?php } ?>
