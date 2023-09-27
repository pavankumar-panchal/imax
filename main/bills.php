<?
if($p_bills <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
$enabledid = array('146','138','112');
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/bills.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/getschemejs.php?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/getproductjs.php?dummy=<? echo (rand());?>"></script>
<script language="javascript">
 $(document).ready(function () {
  //called when key is pressed in textbox
  $("#paymentamount").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
        alert('Digits only allowed!');
               return false;
    }
   });

  });
</script>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td valign="top" width="23%" style="border-bottom:#1f4f66 1px solid;border-right:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="middle" class="active-leftnav">Dealer Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="dealerselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="29%" id="dealerselectionprocess" style="padding:0"><div class="resendtext"><a onclick="displayfilterdiv()" style="cursor:pointer">Filter>></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2"><div id="displayfilter" style="display:none">
                            <table width="100%" border="0" cellspacing="0" cellpadding="3" bgcolor="#f7faff" style=" border:1px solid #ADD8F1">
                              <tr>
                                <td colspan="2" align="left" valign="top"><strong>Relyon Exe:</strong><br />
                                  <label>
                                    <input type="radio" name="relyonexcecutive_type" id="relyonexcecutive0" value="yes" />
                                    Yes</label>
                                  <label>
                                    <input type="radio" name="relyonexcecutive_type" id="relyonexcecutive1" value="no" />
                                    No</label>
                                  <label>
                                    <input name="relyonexcecutive_type" type="radio" id="relyonexcecutive2" value="" checked="checked" />
                                    All </label></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="left" valign="top"><strong>Login:
                                  <label> </label>
                                  </strong>
                                  <label><br />
                                    <input name="login_type" type="radio" id="logintype0" value="no" checked="checked" />
                                    Enabled</label>
                                  <label>
                                    <input type="radio" name="login_type" id="logintype1" value="yes" />
                                    Disabled</label>
                                  <label>
                                    <input type="radio" name="login_type" id="logintype2" value="" />
                                    All</label></td>
                              </tr>
                              <tr>
                                <td width="40%" align="left" valign="top"><strong>Region:</strong><br /></td>
                                <td width="60%" align="left" valign="top"><select name="dealerregion" class="swiftselect-mandatory" id="dealerregion">
                                    <option value="">All</option>
                                    <? 
											include('../inc/region.php');
											?>
                                  </select></td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                                <td><div align="right" class="resendtext"><a onclick="refreshdealerarray()" style="cursor:pointer">Load&gt;&gt;</a></div></td>
                              </tr>
                            </table>
                          </div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"onkeyup="dealersearch(event);" autocomplete="off" style="width:204px" />
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
                <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                    <tr>
                      <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                      <td width="55%" id="totalcount" align="left">&nbsp;</td>
                    </tr>
                  </table></td>
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
                            <td width="41%" align="left" valign="middle" class="active-leftnav"> Credit Amount: Rs. <span id="creditamountdisplay"></span><input type="hidden" name="creditamountfield" class="swifttext" id="creditamountfield" readonly="readonly"  />
                           </td>
                            <td width="25%"><div align="right">Search purchase:</div> </td>
                            <td width="34%" align="left"><input name="searchbill" type="text" class="swifttext" id="searchbill" onkeyup="searchbybillsevent(event);"  autocomplete="off" style="width:200px;"/>
                              <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbills(document.getElementById('searchbill').value);" style="cursor:pointer" /></td>
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
                      <td><div id="maindiv">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Bill Entry'); " style="cursor:pointer" class="grid-active-tabclass">Purchase Entry</td>
                                    <td width="2">&nbsp;</td>
                                    <td width="2">&nbsp;</td>
                                    <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;View Bills');" style="cursor:pointer" class="grid-tabclass">View Purchases</td>
                                    <td width="2">&nbsp;</td>
                                    <td width="140px" align="center" ></td>
                                    <td width="140px" align="center" ></td>
                                    <td width="140px" align="center" ></td>
                                    <td><div id="gridprocessing"></div></td>
                                  </tr>
                                </table></td>
                            </tr>
                            <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                  <tr>
                                    <td width="15%" align="left" class="header-line" style="padding:0"><div id="tabdescription">Purchase Entry</div></td>
                                  </tr>
                                  <tr>
                                    <td align="center" valign="top"><div id="tabgroupgridc1" align="center" style="padding:2px; height:auto; width:auto;">
                                        <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                            <tr>
                                              <td width="100%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td width="17%" align="left" valign="top" bgcolor="#F7FAFF">Bill Number:</td>
                                                    <td width="35%" align="left" valign="top" bgcolor="#F7FAFF"><input name="cusbillnumber" type="text" class="swiftselect-readonly" id="cusbillnumber" size="30" autocomplete="off" readonly="readonly" value="New" />
                                                      <input type="hidden" name="lastslno" id="lastslno" />
                                                      <input type="hidden" name="billlastslno" id="billlastslno" />
                                                      <input type="hidden" name="taxratehidden" id="taxratehidden" /></td>
                                                    <td width="14%" align="left" valign="top" bgcolor="#F7FAFF">Date:</td>
                                                    <td width="34%" align="left" valign="top" bgcolor="#F7FAFF"><input name="billdate" type="text" class="swiftselect-readonly" id="billdate" size="30"  autocomplete="off" value="<? echo(datetimelocal('d-m-Y')." (".datetimelocal('H:i').")"); ?>" readonly="readonly"/></td>
                                                  </tr>
                                                  <tr>
                                                    <td align="left" valign="top" bgcolor="#F7FAFF">Dealer:</td>
                                                    <td align="left" valign="top" bgcolor="#F7FAFF" id="dealerdisplay">&nbsp;</td>
                                                    <td align="left" valign="top" bgcolor="#F7FAFF">Entered By:</td>
                                                    <td align="left" valign="top" bgcolor="#F7FAFF" id="userid">Not Available</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" align="left" valign="top" bgcolor="#F7FAFF">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" valign="top" bgcolor="#F7FAFF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #333333;">
                                                        <tr>
                                                          <td align="left">Scheme:</td>
                                                          <td id="displayschemecode" align="left"><select name="scheme" class="swiftselect-mandatory" id="scheme" style="width:200px" onclick="getproduct('displayproductcode',this.value);" >
                                                              <option value="">Select A scheme</option>
                                                            </select></td>
                                                          <td align="left">Purchase Type: </td>
                                                          <td align="left"><select name="purchasetype" class="swiftselect" id="purchasetype"  onchange="getamount();">
                                                              <option value="" selected="selected">Select A Type</option>
                                                              <option value="new">New</option>
                                                              <option value="updation">Updation</option>
                                                            </select></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="15%" align="left">Product Name:<input type="hidden" name="productgroup" id="productgroup"></td>
                                                          <td align="left" id="displayproductcode"><select name="productcode" class="swiftselect-mandatory" id="productcode" style="width:200px;" onchange="getamount(); getproductgroup();">
                                                              <option value="">Select A Product</option>
                                                            </select></td>
                                                          <td align="left">Usage Type:</td>
                                                          <td align="left"><select name="usagetype" class="swiftselect" id="usagetype"  onchange="getamount();">
                                                              <option value="" selected="selected">Select A Type</option>
                                                              <option value="singleuser">Single User</option>
                                                              <option value="multiuser">Multi User</option>
                                                              <option value="additionallicense">Additonal License</option>
                                                            </select></td>
                                                          <td width="1%" colspan="2" align="left" valign="top" style="padding:0px;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                              <tr>
                                                                <td></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left">Quantity:</td>
                                                          <td align="left" valign="top"><input name="productquantity" type="text" class=" swifttext-mandatory" id="productquantity" onkeyup="getamount(); " size="10" maxlength="4"  autocomplete="off"/>
                                                            <input type="hidden" name="productlastslno" id="productlastslno" /></td>
                                                          <td width="17%" align="left">Amount:</td>
                                                          <td width="33%" align="left"><input name="productamount" type="text" class=" swifttext-mandatory" id="productamount" size="30" maxlength="12" placeholder="Amount should not be zero"  autocomplete="off" /></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="17%" align="left">Unit Cost:</td>
                                                          <td width="33%" align="left"><input name="productrate" type="text" class=" swifttext-mandatory" id="productrate" placeholder="Amount should not be zero" size="30" maxlength="12"  autocomplete="off" onkeyup="amountChange();"/></td>
                                                        </tr>
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                          <td>&nbsp;</td>
                                                          <td>&nbsp;</td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="4"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="57%" height="35" align="left" valign="middle"><div id="prd-form-error"></div></td>
                                                                <td width="43%" height="35" align="right" valign="middle"><input name="productnew" type="button" class= "swiftchoicebutton" id="productnew" value="New Product" onclick="productnewentry();"/>
                                                                  &nbsp;
                                                                  <input name="productsave" type="button" class="swiftchoicebutton" id="productsave" value="Save Product" onclick="productformsubmit('save');" />
                                                                  &nbsp;
                                                                  <input name="productdelete" type="button" class="swiftchoicebutton" id="productdelete" value="Del. Product"  onclick="productformsubmit('delete');"/>
                                                                  &nbsp;</td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" bgcolor="#F7FAFF" >&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" bgcolor="#F7FAFF" align="left"><div id="resendmail" style="display:none"><a   onclick="resendpurchaseemail();"  class="resendtext">Resend Purchase Email </a></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" bgcolor="#F7FAFF"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                                                        <tr>
                                                          <td width="15%" style="padding:0"><div id="displayproductresult" style="overflow:auto; height:150px;  width:670px; padding:2px;" align="center">No datas found to be displayed.</div></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" bgcolor="#F7FAFF" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td rowspan="3" align="left" valign="top">Remarks:</td>
                                                          <td rowspan="3" align="left" valign="top"><textarea name="remarks" cols="27" rows="4" class="swifttextarea" id="remarks"></textarea></td>
                                                          <td align="left" valign="top">Total Amount:</td>
                                                          <td align="left" valign="top"><input name="total" type="text" class="swifttext-readonly" id="total" size="30"  autocomplete="off"   readonly="readonly"/></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left" valign="top" id="taxratedisplay">Tax Amount:</td>
                                                          <td align="left" valign="top"><input name="taxamount" type="text" class="swifttext-readonly" id="taxamount" size="30"  autocomplete="off" /></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left" valign="top">Net Amount:</td>
                                                          <td align="left" valign="top"><input name="netamount" type="text" class="swifttext-readonly" id="netamount" size="30"  autocomplete="off" readonly="readonly"/></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="4" bgcolor="#F7FAFF"><table width="98%" border="0" cellspacing="0" cellpadding="0" >
                                                        <tr>
                                                          <td width="39%" height="35" align="left" valign="middle"><div id="form-error"></div>
                                                            <div id="save-form-error"></div></td>
                                                          <td width="61%" height="35" align="right" valign="middle"><div align="left">
                                                              <input name="viewcards" type="button" class="swiftchoicebuttonbig" id="viewcards" value="Attach(ed) Cards" onclick="viewattachedcards();" />
                                                              &nbsp;
                                                              <input name="new" type="reset" class= "swiftchoicebutton" id="new" value="New"  onclick="newentry();"/>
                                                              &nbsp;
                                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onclick="formsubmit('save');" />
                                                              &nbsp;
                                                              <input name="delete" type="button" class="swiftchoicebutton" id="delete" value="Delete"  onclick="formsubmit('delete');"/>
                                                            </div></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td valign="middle"><div id="viewcardsdisplaydiv" style="display:none; overflow:auto;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                                    <tr>
                                                      <td width="93%" class="header-line" style="padding:0">View Cards</td>
                                                      <td width="7%" class="header-line" style="padding:0"><div align="right"><a  style="cursor:pointer;" onclick="document.getElementById('viewcardsdisplaydiv').style.display = 'none';"><img src="../images/cancel5.jpg" width="10" height="10" align="absmiddle" /></a>&nbsp;&nbsp;</div></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2" style="padding:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td><div id="displayviewcards" style="overflow:auto; height:150px; width:704px; padding:2px;" align="center"> /No datas found to be displayed.</div></td>
                                                          </tr>
                                                        </table></td>
                                                    </tr>
                                                  </table>
                                                </div></td>
                                            </tr>
                                            <tr><?php if(in_array($userid, $enabledid, true) ) { ?>
                                                    <td colspan="4" valign="top" bgcolor="#F7FAFF" style="padding:0">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #333333;">
                                                      <tbody id="showdealeritems"> <tr>
                                                          <td width="9%" align="left">Sales Person :</td>
                                                          <td align="left"><select name="dealer" class="swiftselect-mandatory" id="dealer" style="width:220px;">
                                                              <option value="">Select A dealer</option>
                                                              <? include('../inc/dealer-invoicing.php'); ?>
                                                            </select></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left" width="18%">Payment Amount:</td>
                                                          <td align="left" valign="top"><input name="paymentamount" type="text" class=" swifttext-mandatory" id="paymentamount" size="30" placeholder="Enter total received amount" maxlength="12"  autocomplete="off"/>
                                                          </tr>
                                                          <tr>
                                                          <td width="18%" align="left">Payment Details:</td>
                                                          <td  align="left" valign="top"><input name="paymentremarks" type="text" class=" swifttext-mandatory" id="paymentremarks"  size="30" maxlength="100" autocomplete="off" /></td>
                                                        </tr></tbody>
                                                        
                                                        <tr><td width="8%"><div align="left">Item Others: </div><input type="hidden" value="" name="getitemid" id="getitemid"></td>
                                                        <td align="center"><strong><a  name="add" id="additem" onclick="additemgrid();" class= "r-text">Add Item Others>></a></strong></td></tr>
                                                        <tr>
                                                          <td colspan = 2>
                                                          <table width="100%" border="0" cellspacing="0" cellpadding="1"  id="seletedproductgrid">
                                                            <tbody> 
                                                              <tr>
                                                                <td width="2%">1</td>
                                                                <td width="3%" ><select name="product2[]" class="swiftselect-mandatory" class="select_continent" id="product21" style="width:220px;" disabled >
                                                                    <option value="">Select Item Others</option>
                                                                    <? include('../inc/services.php'); ?>
                                                                  </select>
                                                                </td>
                                                                <td width="2%" ><input name="itemamount[]" type="text" class=" swifttext-mandatory" id="itemamount1"  size="30" maxlength="12"  autocomplete="off" disabled placeholder="Item amount"></td>
                                                                <td width="2%"><strong><a   id="removeitem" onclick="removegrid(this);" class= "r-text" title="Remove Item!">X</a></strong></td>
                                                                <td width="15%" >&nbsp;</td>
                                                              </tr>
                                                            </tbody>
                                                          </table>
                                                          </td>
                                                          </tr>
                                                        <tr>
                                                          <td align="left" width="18%">Invoice Remarks:</td>
                                                          <td align="left" valign="top"><input name="invoiceremarks" type="text" class=" swifttext-mandatory" id="invoiceremarks" size="30" maxlength="300"  autocomplete="off"/>
                                                          </tr>
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="4"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="57%" height="35" align="left" valign="middle"><div id="dlr-form-error"></div></td>
                                                                <td width="43%" height="35" align="right" valign="middle"><input name="generateinvoice" type="button" class= "swiftchoicebuttondisabledbig" id="generateinvoice" value="Generate Invoice" onclick="generateinv();"/>
                                                                 <!--<input name="generateinvoice" type="button"  id="generateinvoice" value="Generate Invoice" onclick="alert('Temporarily Unavailable.Kindly try after sometime.'); return false;"/> -->
                                                                 </td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                      <?php } ?></tr> 
                                                <tr>
                                              <td >
                                                <input type="hidden" name="invoicelastslno" id="invoicelastslno"  value=" "/>
                                                <div id="viewinvoicedisplaydiv"  overflow:auto;">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                                    <tr>
                                                      <td width="93%" class="header-line" style="padding:0">Invoice details</td>
                                                      <!-- <td width="7%" class="header-line" style="padding:0"><div align="right"><a  style="cursor:pointer;" onclick="document.getElementById('viewinvoicedisplaydiv').style.display = 'none';"><img src="../images/cancel5.jpg" width="10" height="10" align="absmiddle" /></a>&nbsp;&nbsp;</div></td> -->
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2" style="padding:0;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td><div id="displayinvoices" style="overflow:auto; height:150px; width:704px; padding:2px;" align="center"> No datas found to be displayed.</div></td>
                                                          </tr>
                                                  </table></td>
                                                    </tr>
                                                  </table>
                                                </div></td>
                                            </tr>
                                          </table>
                                        </form>
                                      </div>
                                      <div id="tabgroupgridc2" style="overflow:auto;height:410px; width:704px; padding:2px; display:none;" align="center" >
                                        <div id="resultgrid" style="overflow:auto; height:410px; width:704px; padding:2px; display:none;" align="center"></div>
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td><div id="tabgroupgridc2_1"  align="center" ></div></td>
                                          </tr>
                                          <tr>
                                            <td><div id="getmorerecordslink" align="left"></div></td>
                                          </tr>
                                        </table>
                                      </div></td>
                                  </tr>
                                </table></td>
                            </tr>
                          </table>
                        </div></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<script>refreshdealerarray();
</script>
<? } ?>