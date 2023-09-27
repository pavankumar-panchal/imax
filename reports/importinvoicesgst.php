<?php
if($p_importinvoices <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/invoiceimportgst.js?dummy=<?php echo (rand());?>"></script>

<SCRIPT language="javascript">
 $(document).ready(function () {
  $("#frominvoicetype").change(function (e) {
    if($('#frominvoicetype').val()!= 'R' && $('#frominvoicetype').val()!= 'D')
    {
      $('#fromstatetype').val('');
      $('#fromstatetype').attr("disabled","disabled");
      $('#tostatetype').val('');
      $('#tostatetype').attr("disabled","disabled");
    }
    else
    {
      $('#fromstatetype').removeAttr('disabled');
      $('#tostatetype').removeAttr('disabled');
    }
  });
 });
	function invoicetype_cat()
	{
		document.getElementById("toinvoicetype").value=document.getElementById("frominvoicetype").value;
	}
	function state_cat()
	{
		document.getElementById("tostatetype").value=document.getElementById("fromstatetype").value;
	}

</script>

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
                            <td width="27%" class="active-leftnav"> Invoice Import GST</td>
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
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                    <tr>
                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FBF3DB" >
                                          <tr>
                                            <td valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                             
                                                <td width="24%" align="left" valign="middle">From Invoice No: </td>
                                                <td width="4%" align="left" valign="middle"><strong>RSL</strong></td>
                                                <td width="4%" align="left" valign="middle"><strong>2022</strong></td>
                                             
                                                  <td width="8%" align="left" valign="top"><select class="swiftselect-mandatory" style="width:70px;" id="frominvoicetype" name="frominvoicetype" onchange="invoicetype_cat()" onblur="invoicetype_cat()">
                                                <option selected="selected" value=""> SELECT </option>
                                                <option value="R">R</option>
                                                <option value="D">D</option>
                                                <option value="GU">GU</option>
                                                <option value="UP">UP</option>
                                                <option value="TL">TL</option>
                                                <option value="RJ">RJ</option>
                                                <option value="WB">WB</option>
                                                <option value="MH">MH</option>
                                                <option value="TN">TN</option>
                                                <option value="ML">ML</option>
                                              </select></td>
                                                    <td width="8%" align="left" valign="top"><select class="swiftselect-mandatory" style="width:70px;" id="fromstatetype" name="fromstatetype" onchange="state_cat()" onblur="state_cat()">
                                                <option selected="selected" value=""> SELECT </option>
                                                <option value="L">L</option>
                                                <option value="I">I</option>
                                                </select></td>
                                                <td width="30%" align="left" valign="top"><input name="frominvoicenonew" type="text" class="swifttext-mandatory" id="frominvoicenonew" size="15" autocomplete="off" value=""  />
                                                  <input type="hidden" name="flag" id="flag" value="true"/></td>
                                                </tr>
                                            </table></td>
                                           
                                            <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr >
                                                  <td colspan="4" valign="top" style="padding:0"></td>
                                                </tr>
                                               
                                                
                                                <tr >
                                                  <td width="24%" align="left" valign="middle" >To Invoice No:</td>
                                                  <td width="4%" align="left" valign="middle"><strong>RSL</strong></td>
                                                  <td width="4%" align="left" valign="middle"><strong>2022</strong></td>
                                                  <!--<td width="17%" align="left" valign="top">
                                                  <input type="text" id="toregiontype" readonly="readonly" name="toregiontype" size="10"/>
                                                  </td>-->
                                                  <td width="2%" align="left" valign="top">
                                                  <input type="text" id="toinvoicetype" readonly="readonly" name="toinvoicetype" size="3"/>
                                                  </td>
                                                   <td width="2%" align="left" valign="top">
                                                  <input type="text" id="tostatetype" readonly="readonly" name="tostatetype" size="10"/>
                                                  </td>
                                                  <td width="30%" align="left" valign="top"><input name="toinvoicenonew" type="text" class="swifttext-mandatory" id="toinvoicenonew" size="15" autocomplete="off" value=""  /></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:5px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="67%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="33%" align="right" valign="middle">&nbsp;
                                              <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Text" onclick="formsubmit('toexcel');"/>
                                              &nbsp;
                                              <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetfunc();" /></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td colspan="2"></td>
                          </tr>
                          <tr>
                            <td colspan="2"></td>
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