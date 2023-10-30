<?php
if($p_smsreceipttodealer <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/smsreceiptsdealers.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>

<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="middle" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onSubmit="return false;">
                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                    <tr>
                      <td width="71%" height="34"  id="dealerselectionprocess" align="left"  style="padding:0">&nbsp;</td>
                      <td width="29%" style="padding:0"><div align="right"><a onclick="refreshcustomerarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onkeyup="dealersearch(event);"  autocomplete="off" style="width:204px"/>
                          <div id="detailloadcustomerlist">
                            <select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();">
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
                <td width="55%" id="totalcountdealer" align="left">&nbsp;</td>
              </tr>
</table></td>
              </tr>
              <tr><td>&nbsp;</td></tr>
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
                            <td width="30%" align="left" valign="middle" class="active-leftnav">SMS Receipt</td>
                            <td width="37%"><div align="right"></div></td>
                            <td width="33%"><div align="right"></div></td>
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
                            <td width="66%" align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                          <td width="34%" align="right" class="header-line" style="padding-right:7px">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                    <tr><td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                          <tr bgcolor="#f7faff">
                                            <td width="19%" align="left" valign="top">Customer Name:</td>
                                            <td width="81%" align="left" valign="top" bgcolor="#f7faff" id="displaydealername">&nbsp;</td>
                                          </tr>
                                           </table></td></tr>
                                    <tr>
                                      <td width="52%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" height="71" border="0" cellpadding="3" cellspacing="0">
                                        <tr bgcolor="#f7faff">
                                          <td width="36%" align="left" valign="top">Invoice No:</td>
                                          <td width="64%" align="left" valign="top" bgcolor="#f7faff" >
<div id="smsaccountlist"><select name="invoivcelist" class="swiftselect-mandatory" id="invoivcelist" style="width:200px;" >
                                    <option value="">Select a Invoice</option></select></div>                                  </td>
                                        </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#f7faff"><label>
                                              Receipt Amount:
                                                <div align="right"></div>
                                              </label></td>
                                            <td align="left" valign="top" bgcolor="#f7faff" ><input name="receiptamount" type="text" class="swifttext" id="receiptamount" size="30" maxlength="12" autocomplete="off" onkeyup="gettotalamount();"/>
                                            <input name="lastslno" id="lastslno" type="hidden" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td  align="left" valign="top" bgcolor="#f7faff">Remarks:<br/></td>
                                             <td align="left">   <div align="right" style="padding-right:30px;">
                                                  <textarea name="remarks" cols="27" rows="4" class="swifttextarea" id="remarks"></textarea>
                                              </div></td>
                                          </tr>
                                                                                                             
                                          
                                          
                                      </table></td>
                                      <td width="48%" valign="top"><table width="100%" border="0" cellpadding="4" cellspacing="0">
                                        <tr bgcolor="#f7faff">
                                          <td width="34%" align="left" valign="top">Invoice Amount:</td>
                                          <td width="66%" align="left" valign="top" bgcolor="#f7faff" id="invoiceamount">&nbsp;</td>
                                        </tr>
                                      <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Payment Mode:</td>
                                            <td align="left" valign="top"  bgcolor="#f7faff"><label>
                                              <input type="radio" name="paymentmode" id="cash" value="cash"  />
                                            Cash <br/></label>
                                           <label> <input type="radio" name="paymentmode" id="cheque" value="cheque" checked="checked"/>
                                            Cheque <br/></label>
                                            <label><input type="radio" name="paymentmode" id="creditordebit" value="creditordebit" />
                                            Credit/Debit Card <br/></label>
                                            <label><input type="radio" name="paymentmode" id="neft" value="neft" />
                                            NEFT<br/> </label>
                                           <label> <input type="radio" name="paymentmode" id="onlinepayment" value="onlinepayment" />
                                            Online Payment                                            </label></td>
                                      </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">Private Note:<br/></td>
                                           <td align="left"><div align="right" style="padding-right:20px;"> <textarea name="privatenote" cols="27" rows="4" class="swifttextarea" id="privatenote"></textarea>
                                           </div></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#f7faff">&nbsp;</td>
                                            <td align="left" valign="top" bgcolor="#f7faff">&nbsp;</td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="54%" align="left" valign="middle">&nbsp;&nbsp;</td>
                                            <td width="15%" align="left" valign="middle">Total Amount</td>
                                            <td width="31%" align="left" valign="middle"><input name="totalamount" type="text" class="swifttext" id="totalamount" size="30" maxlength="12" autocomplete="off"  style="background:#FFFFCC;" readonly="readonly"/></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div><div id="productselectionprocess"></div></td>
                                            <td width="44%" align="right" valign="middle">&nbsp;&nbsp;
                                              <div align="center">
                                                <input name="new" type="button" class="swiftchoicebutton" id="new" value="New" onclick="newcreditentry();document.getElementById('form-error').innerHTML ='';"/> &nbsp;&nbsp;
  <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit();" />

  &nbsp;&nbsp;</div></td>
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
                                  <td width="136" align="left"  style="padding:0"><div id="tabdescription">&nbsp; Receipt Details</div></td>
                                  <td width="446" style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                                  <td width="25" align="left"  style="padding:0">&nbsp;</td>
                                  <td width="125" align="left"  style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center"></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link"  align="left" style="height:20px; padding:2px;">
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
refreshdealerarray();
</script>

<?php } ?>