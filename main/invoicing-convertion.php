<?php
if($p_invoicing <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{

?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo (rand());?>"  />

<script language="javascript" src="../functions/javascript.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/invoicing.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<script language="javascript" src="../functions/colorbox.js?dummy=<?php echo (rand());?>" ></script>

<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="text-align:left">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="customerselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="29%" style="padding:0"><div align="right"><a onclick="refreshcustomerarray();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" border="0" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left" ><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onkeyup="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <span style="display:none1">
                          <input name="searchtextid" type="hidden" id="searchtextid"  disabled="disabled" />
                          </span>
                          <div id="detailloadcustomerlist">
                            <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px" onclick ="selectfromlist();" onchange="selectfromlist();"  >
                            </select>
                          </div></td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcount" align="left"></td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td width="14%" align="top" class="active-leftnav">Tax Invoice</td>
                            <td width="26%" align="top"><div align="right"><font color="#FF6B24">Invoice No?</font></div></td>
                            <td width="25%" align="top"><div align="center">
                              <input name="searchinvoiceno" type="text" class="swifttext" id="searchinvoiceno" onkeyup="searchbyinvoicenoevent(event);"  maxlength="20"  autocomplete="off" style="width:125px"/>
                              <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbyinvoiceno(document.getElementById('searchinvoiceno').value);" style="cursor:pointer" /> </div></td>
                            <td width="12%" align="top"><div align="right"><font color="#FF6B24">Customer ID?</font></div></td>
                            <td width="23%" valign="top"><div align="left" style="padding:2px">
                                <div align="center">
                                  <input name="searchcustomerid" type="text" class="swifttext" id="searchcustomerid" onkeyup="searchbycustomeridevent(event);"  maxlength="20"  autocomplete="off" style="width:125px"/>
                                  <img src="../images/search.gif" width="16" height="15" align="absmiddle"  onclick="searchbycustomerid(document.getElementById('searchcustomerid').value);" style="cursor:pointer" /> </div>
                              </div></td>
                          </tr>
                      </table></td>
                    </tr>
                    <tr>
                      <td style="padding-top:3px"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                        </table></td>
                    </tr>
                    <tr>
                      <td height="5">&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="99%" border="0" align="center" cellpadding="0" cellspacing="0" class="contactcontentdisplay" style="border:1px solid #CEE7FF"  bgcolor="#FEFFE6">
                          <tr  bgcolor="#CBE0FA" style="height:35px;" >
                            <td style="padding-left:8px; padding-top:5px;"><strong>Company Name:</strong></td>
                            <td style="padding-left:8px; padding-top:5px;"><span id="customerdetailsshow" style="display:none">
                              <input type="text" value="" size="60" class="swifttext-readonly-border-h"  name="displaycompanyname"  id="displaycompanyname" readonly="readonly" />
                            </span><span id="customerdetailshide" style="display:block"></span></td>
                            <td style="padding-right:10px;"><div align="right"><img src="../images/plus.jpg" border="0" id="toggleimg2" name="toggleimg1"  align="absmiddle" onclick="displayDiv2('displaycustomerdetails','toggleimg1');" style="cursor:pointer"/></div></td>
                          </tr>
                          <tr>
                            <td width="100%" colspan="3" valign="top" ><div id="displaycustomerdetails" style="display:none">
                              <table width="99%" border="0" cellspacing="0" cellpadding="4" align="center" >
                                <tr >
                                  <td colspan="4" valign="top" style="border-right:#E6F0F9 1px solid; border-bottom:#E6F0F9 1px solid"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                      <tr>
                                        <td width="43%"><strong>Customer ID: </strong></td>
                                        <td width="57%" id="displaycustomerid">&nbsp;</td>
                                      </tr>
                                      <tr>
                                        <td colspan="2" valign="top"><strong>Address: </strong>                                          <br />
                                          <br />
                                          <textarea  cols="47" rows="2" autocomplete="off" id="displayaddress" class="swifttext-readonly-border" type="text" name="displayaddress" readonly="readonly"></textarea>                                        </td>
                                        </tr>
                                      <tr>
                                        <td><strong>Contact Person: </strong></td>
                                        <td ><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displaycontactperson"  id="displaycontactperson" readonly="readonly"/>                                        </td>
                                      </tr>
                                      <tr>
                                        <td><strong>Email: </strong></td>
                                        <td ><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displayemail"  id="displayemail" readonly="readonly" /></td>
                                      </tr>
                                  </table></td>
                                  <td width="52%" colspan="2" valign="top"  style=" border-bottom:#E6F0F9 1px solid"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                    <tr>
                                      <td ><strong>Phone: </strong></td>
                                      <td><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displayphone"  id="displayphone" readonly="readonly"/></td>
                                    </tr>
                                      <tr>
                                        <td><strong>Cell: </strong></td>
                                        <td ><input type="text" value="" size="30" class="swifttext-readonly-border"  name="displaycell"  id="displaycell" readonly="readonly"/></td>
                                      </tr>
                                      <tr>
                                        <td ><strong>Type of Customer: </strong></td>
                                        <td id="displaytypeofcustomer"></td>
                                      </tr>
                                      <tr>
                                        <td ><strong>Type of Category: </strong></td>
                                        <td  id="displaytypeofcategory"></td>
                                      </tr>
                                      <tr>
                                        <td width="42%"><strong>Marketing Exe: </strong></td>
                                        <td width="58%" id="displaymarketingexe">Not Selected</td>
                                      </tr>
                                      <tr>
                                        <td><strong>Current Dealer:</strong></td>
                                        <td id="displaydealer">&nbsp;</td>
                                      </tr>
                                  </table></td>
                                </tr>
                                <tr>
                                  <td colspan="4" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr>
                                        <td><input type="hidden" name="cusnamehidden" id="cusnamehidden" />
                                            <input type="hidden" name="addresshidden" id="addresshidden" />
                                            <input type="hidden" name="contactpersonhidden" id="contactpersonhidden" />
                                          <input type="hidden" name="emailhidden" id="emailhidden" />
                                          <input type="hidden" name="phonehidden" id="phonehidden" />
                                          <input type="hidden" name="cellhidden" id="cellhidden" />
                                          <input type="hidden" name="custypehidden" id="custypehidden" />
                                          <input type="hidden" name="cuscategoryhidden" id="cuscategoryhidden" /></td>
                                      </tr>
                                  </table></td>
                                  <td colspan="2"><div align="center">
                                    <input name="edit" type="button" class="swiftchoicebutton" id="edit" value="Edit" onclick="customerdetailsremovereadonly()"/>
                                    &nbsp;&nbsp;&nbsp;
                                      <input name="cancel" type="button" class="swiftchoicebutton" id="cancel" value="Cancel"  onclick="customerdetailsmakereadonly()"/>
                                  </div></td>
                                </tr>
                              </table>
                            </div></td>
                          </tr>
                          <tr>
                            <td  colspan="3"></td>
                          </tr>
                      </table></td>
                    </tr>
                                        <tr><td colspan="3" height="10px;"></td></tr>
                    
                    <tr><td colspan="3" height="10px;"></td></tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; ">
                          <tr>
                            <td align="left" style="padding:0">&nbsp;</td>
                            <td align="right"  style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="100%" valign="top"  height="25px;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr><td colspan="2" ><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:#CCCCCC 1px solid " bgcolor="#FFFFEC" >
                                       <tr>
                                                  <td width="31%"  height="42px;" valign="middle"><select name="dealer" class="swiftselect" id="dealer" style="width:195px;">
                                                      <option value="" selected="selected">Select a Dealer</option>
                                                      <?php 
											include('../inc/dealer-invoicing.php');
											?>
                                                  </select></td>
                                                  <td width="8%" valign="middle"><a  onclick="getdealerdetails();"  class="r-text">Go &gt;&gt;</a></td>
                                                  <td width="61%" valign="bottom" ><div id="dealerdivdisplay" style="display:none1">
                                                <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0"  >
                                                  <tr>
                                                    <td valign="top" ><strong><div style="padding-top:8px;"><span id="dealerdisplayheading"><font color="#426b8e">Dealer Name: </font></span><span id="displaydealername" ><font color="#FF0000">Not Selected</font></span>&nbsp;&nbsp;<span style="display:none" id="displaydealerdetailsicon"><img src="../images/help-image.gif" width="13" height="14"  onclick ="showdealerdetails();" title="Delaer Details"  align="absmiddle" style="cursor:pointer"/></span></div></strong></td>
                                                    </tr>
                                                        <tr>
                                                    <td valign="top" >
                                                    <div style="display:none">
                                                      <div id="displaydealerdetails" style='background:#fff; width:450px; font-size:14px'></div></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td></td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                                </tr>
                                       <tr>
                                         <td colspan="3"></td>
                                         </tr>
                                      </table></td></tr>
                                          <tr>
                                            <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr>
                                                  <td width="15%"><strong>Item</strong>(Software): </td>
                                                  <td width="28%"><select name="product" class="swiftselect" id="product" style="width:195px;">
                                                      <option value="" selected="selected">Select a Item</option>
                                                      <?php 
											include('../inc/productforpurchase.php');
											?>
                                                  </select></td>
                                                  <td width="8%"><a  onclick="addselectedproduct('software');" class="r-text"  >Add &gt;&gt; </a></td>
                                                  <td width="13%"><strong>Item</strong>(others):</td>
                                                  <td width="28%"><select name="product2" class="swiftselect" id="product2" style="width:195px;">
                                                      <option value="" selected="selected">Select a Item</option>
                                                      <?php 
											include('../inc/services.php');
											?>
                                                    </select></td>
                                                  <td width="8%"><a  onclick="addselectedproduct('others');" class="r-text" >Add &gt;&gt; </a></td>
                                                </tr>
                                                
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><input type="hidden" name="dealeridhidden" id="dealeridhidden" />
                                              <input type="hidden" name="productcounthidden" id="productcounthidden" />
                                              <input type="hidden" name="lastslno" id="lastslno" />
                                              <input type="hidden" name="onlineslno" id="onlineslno" />
                                              <input type="hidden" name="offerremarkshidden" id="offerremarkshidden" />
                                              <input type="hidden" name="rowslno" id="rowslno" />
                                              <input type="hidden" name="producthiddenslnoid" id="producthiddenslnoid" /><input type="hidden" name="rowid" id="rowid" /><input type="hidden" name="cardslnohidden" id="cardslnohidden" /></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" align="left" bgcolor="#F7FAFF"><div style="overflow:auto;width:699px; display:none1;" id="productlistdiv" >
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-border-grid" >
                                                  <tr>
                                                    <td><table width="100%" border="0" cellspacing="0" cellpadding="4" id="seletedproductgrid"  >
                                                        <tr class="tr-grid-header">
                                                          <td width="9%" nowrap="nowrap" class="td-border-grid">Sl No</td>
                                                          <td width="27%" nowrap="nowrap" class="td-border-grid">Product</td>
                                                          <td width="15%" nowrap="nowrap" class="td-border-grid">Purchase Type</td>
                                                          <td width="13%" nowrap="nowrap" class="td-border-grid">Usage Type</td>
                                                          <td width="10%" nowrap="nowrap" class="td-border-grid">Quantity</td>
                                                          <td width="15%" nowrap="nowrap" class="td-border-grid">Unit Price</td>
                                                          <td width="11%" nowrap="nowrap" class="td-border-grid">Remove</td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="7" nowrap = "nowrap" class="td-border-grid" id="messagerow"><div align="center" style="height:15px;"><strong><font color="#FF0000">Select a Customer First</font></strong></div></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td  class="td-border-grid"><table width="100%" border="0" cellspacing="0" cellpadding="3" id="adddescriptionrows">
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td style="border-left:#d1dceb 1px solid;"><div align="left" id="adddescriptionrowdiv" style="display:none; height:20px;">
                                                        <div align="center" ><a onclick="adddescriptionrows();" class="r-text">Add one More &gt;&gt;</a></div>
                                                      </div></td>
                                                  </tr>
                                                </table>
                                              </div></td>
                                          </tr>
                                          <tr>
                                            <td width="49%" height="25px;" ><div id="displayofferremarksdiv" style="display:none"></div></td>
                                            <td width="51%" ><div align ="left" style="display:none" id="removeoffermegdiv" ><font color = "#FF0000"><strong><a style="cursor:pointer; " onclick="removeofferremarksdiv();">X</a></strong></font></div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2" align="left"><div id="resultdiv" style="display:none" >
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="5%">&nbsp;</td>
                                                    <td width="58%">&nbsp;</td>
                                                    <td width="37%">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="55%" valign="top"><div align="left">Invoice Remarks<br />
                                                                    <br />
                                                                    <input name="invoiceremarks" class="swifttextareanew" id="invoiceremarks" type="text" maxlength="100" style="width:300px" />
                                                                  
                                                                    <br />
                                                                  </div></td>
                                                                <td width="45%"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td>&nbsp;</td>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td width="42%">Total:</td>
                                                                      <td width="58%"><input name="totalamount" type="text" class="swifttext-readonly" id="totalamount"   maxlength="10"  autocomplete="off" style="width:100px; text-align:right" disabled="disabled" readonly="readonly"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td>Service Tax:</td>
                                                                      <td><input name="sericetaxamount" type="text" class="swifttext-readonly" id="sericetaxamount"   maxlength="10"  autocomplete="off" style="width:100px;text-align:right" disabled="disabled" readonly="readonly"/></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td>Net Amount:</td>
                                                                      <td><input name="netamount" type="text" class="swifttext-readonly" id="netamount"   maxlength="10"  autocomplete="off" style="width:100px;text-align:right" disabled="disabled"  readonly="readonly"/></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3"><a class="r-text" onclick="pricingdivhideshow();">Pricing &#8250;&#8250;</a></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                        <tr>
                                                          <td colspan="2"><div id="pricingdiv" style="display:none">
                                                              <table width="100%" border="0" cellspacing="0" cellpadding="3" style="background-color:#FFFFE8; border:1px solid #CCCCCC">
                                                                <tr>
                                                                  <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                      <tr>
                                                                        <td width="10%"><label for="normal">
                                                                          <input type="radio" name="pricing" id="normal" value="normal"  checked="checked" onclick="validatepricingfield();"/>
                                                                          Empty</label></td>
                                                                        <td width="13%"><label for="default">
                                                                          <input type="radio" name="pricing" id="default" value="default" onclick="validatepricingfield();"/>
                                                                          Price List</label></td>
                                                                        <td width="10%"><label for="offer">
                                                                          <input type="radio" name="pricing" id="offer" value="offer" onclick="validatepricingfield();"  />
                                                                          Offer</label></td>
                                                                        <td width="67%"><label for="inclusivetax">
                                                                          <input type="radio" name="pricing" id="inclusivetax" value="inclusivetax"  onclick="validatepricingfield();" />
                                                                          Inclusive Tax</label></td>
                                                                      </tr>
                                                                      <tr>
                                                                        <td colspan="4"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                              <td valign="top" height="20px;"><span id="offerdescriptiondiv" style="display:none">Offer Name:
                                                                                <input name="offerremarks" type="text" class="swifttext-mandatory" id="offerremarks"  autocomplete="off" style="width:225px"/>
                                                                                </span><span id="offeramtdiv" style="display:none;vertical-align:top">Offer Amount:
                                                                                <input name="offeramount" type="text" class="swifttext-mandatory" id="offeramount"   maxlength="150"  autocomplete="off" style="width:100px;text-align:right" disabled="disabled" align="right"/>
                                                                                </span><span id="inclusivetaxamtdiv" style="display:none; vertical-align:top">Amount Inclusive Tax:
                                                                                <input name="inclusivetaxamount" type="text" class="swifttext-mandatory" id="inclusivetaxamount"   maxlength="150"  autocomplete="off" style="width:100px;text-align:right" disabled="disabled" align="right"/>
                                                                                </span><span id="displayapplylink" style="vertical-align:top">&nbsp;</span></td>
                                                                            </tr>
                                                                          </table></td>
                                                                      </tr>
                                                                    </table></td>
                                                                </tr>
                                                              </table>
                                                            </div></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:1px solid #CCCCCC; height:80px" bgcolor="#FFF2F2"  >
                                                              <tr >
                                                                <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                    <tr>
                                                                      <td>Payment Information</td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><input type="radio" name="modeofpayment" id="databasefield1" value="credit/debit" checked="checked" onclick="showhidepaymentdiv();"/>
                                                                        &nbsp;
                                                                        <label for="databasefield1"><strong>Credit Cards / Debit Cards</strong> [Master Card / VISA]</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td><label for="databasefield2">
                                                                        <input type="radio" name="modeofpayment" id="databasefield2" value="others"  onclick="showhidepaymentdiv();"/>
                                                                        &nbsp;<strong>Others</strong> <strong ></strong> [Cheque / DD / NEFT / etc]</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td ><div id="paymentdiv" style="display:none">
                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #FFE1E1" bgcolor="#FFFFFF" height="170px;">
                                                                            <tr>
                                                                              <td width="100%" valign="top"><div>
                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                                                  
                                                                                    <tr>
                                                                                      <td colspan="3" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                       <tr>
                                                                                      <td width="23%" valign="top"><div align="left">
                                                                                          <label for="databasefield3">
                                                                                          <input type="radio" name="paymentmodeselect" id="databasefield3" value="paymentmadenow" onclick="showhidepaymentinfodiv();" checked="checked"/>
                                                                                          Payment made Now</label>
                                                                                        </div></td>
                                                                                      <td width="77%" colspan="2"><div align="left">
                                                                                          <label for="databasefield4">
                                                                                          <input type="radio" name="paymentmodeselect" id="databasefield4" value="paymentmadelater"   onclick="showhidepaymentinfodiv();"/>
                                                                                          Will Pay Later </label>
                                                                                        </div></td>
                                                                                    </tr>
                                                                                      </table></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                      <td colspan="3"><div id="paymentmadenow" style="display:none">
                                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="4" bgcolor="#FFFBF9" >
                                                                                            <tr>
                                                                                              <td width="24%"><strong>Mode Of Payment:</strong></td>
                                                                                              <td><label for="databasefield5">
                                                                                                </label>
                                                                                                <label for="databasefield7">
                                                                                                <input type="radio" name="paymentmode" id="databasefield7" value="chequeordd"   onclick="hideorshowpaymentdetailsdiv();" checked="checked"/>
Cheque / DD</label><label for="databasefield6">
                                                                                                <input type="radio" name="paymentmode" id="databasefield6" value="onlinetransfer"   onclick="hideorshowpaymentdetailsdiv();"/>
                                                                                                Online Transfer </label>
                                                                                                <label for="databasefield5">
                                                                                                <input type="radio" name="paymentmode" id="databasefield5" value="cash"   onclick="hideorshowpaymentdetailsdiv();"/>
                                                                                                Cash </label>                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                              <tr>
                                                                                                <td width="18%">Payment Amount:</td>
                                                                                                <td width="35%"><input name="paymentamount" type="text" class="swifttext-readonly" id="paymentamount" size="30" maxlength="12" autocomplete="off" readonly="readonly"/></td>
                                                                                                <td width="47%"><input type="checkbox" name="partialpayment" id="partialpayment"  onclick="disableorenablepaymentamount()"/>&nbsp;Part Payment</td>
                                                                                                </tr>
                                                                                            </table></td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                              <td colspan="2"><div id="paymentdetailsdiv1" style="display:block">
                                                                                                
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                                                    <tr>
                                                                                                      <td width="18%" valign="top">Payment Details:</td>
                                                                                                      <td width="35%" valign="top"><input name="paymentremarks"  class="swifttextareanew" id="paymentremarks" type="text" maxlength="100" style="width:190px"/></td>
                                                                                                      <td width="47%" valign="top"><div align="justify" id="cashwarningmessage" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:dashed 1px #FF0000" bgcolor="#FFFFCC">
  <tr>
    <td><div align="justify"><strong>Warning:</strong> Please collect the amount by Cheque / DD. Else, you may get an online transfer or even paid through credit/debit card. It is not recommended to collect cash, unless in exception.</div></td>
  </tr>
</table>
</div><div align="justify" id="bankdetailstip" style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border:dashed 1px #FF0000" bgcolor="#FFFFCC">
  <tr>
    <td><ol>
                            <li><strong>  Bank of India</strong></li>
                              Payee name: Relyon Softech Ltd<br />
                              Bank A/c No:   840720100010788<br />
                              Branch: J C Road, Bangalore<br />
                              NEFT/IFSC Code: BKID0008407<br />
                              <li><strong> ICICI Bank</strong><br />
                                Payee name: Relyon Softech Ltd<br />
                                  Bank A/c No:   029605004918<br />
                                    Branch: Rajajinagar, Bangalore<br />
                                      NEFT/IFSC Code: ICIC0000296<br />
                                  </li>
                          </ol></td>
  </tr>
</table>
</div></td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                              </div><div id="paymentdetailsdiv2" style="display:none">
                                                                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                                                    <tr>
                                                                                                      <td width="21%">Cheque Date: </td>
                                                                                                      <td width="33%"><input name="chequedate" type="text" class="swifttext-mandatory" id="DPC_chequedate" size="30" autocomplete="off" value=""  readonly="readonly"/></td>
                                                                                                      <td width="15%">Cheque No:</td>
                                                                                                      <td width="31%"><input name="chequeno" type="text" class="swifttext-mandatory" id="chequeno" size="30" maxlength="12" autocomplete="off" /></td>
                                                                                                    </tr>
                                                                                                    <tr>
                                                                                                      <td>Drawn On:</td>
                                                                                                      <td><input name="drawnon" type="text" class="swifttext-mandatory" id="drawnon" autocomplete="off"  style="width:192px;"/></td>
                                                                                                      <td>Deposit Date:</td>
                                                                                                      <td><input name="depositdate" type="text" class="swifttext" id="DPC_depositdate" size="30" maxlength="12" autocomplete="off" /></td>
                                                                                                    </tr>
                                                                                                  </table>
                                                                                                </div></td>
                                                                                            </tr>
                                                                                          </table>
                                                                                        </div></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                      <td colspan="3"><div id="willpaylater" style="display:none">
                                                                                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                                                            <tr>
                                                                                              
                                                                                              <td width="256" valign="top">Due Date:<br />
                                                                                                <br />
                                                                                              <input name="DPC_duedate" type="text" class="swifttext-mandatory" id="DPC_duedate" size="20" maxlength="12" autocomplete="off"   value="<?php echo(datetimelocal('d-m-Y')); ?>" readonly="readonly"/></td>
                                                                                              <td width="420">Reason:<br />
                                                                                                <br />
                                                                                                <input name="remarks" type="text" class="swifttextareanew" id="remarks" maxlength="100" style="width:450px"/>                                                                                             </td>
                                                                                            </tr>
                                                                                          </table>
                                                                                        </div></td>
                                                                                    </tr>
                                                                                  </table>
                                                                                </div></td>
                                                                            </tr>
                                                                                                                                                     </table>
                                                                        </div></td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="67%" height="35px;"><div id="form-error"></div></td>
                                                          <td width="33%"><div align="right" style="display:none;"><input type="checkbox" name="invoicedated" id="invoicedated" checked="checked" />Invoice Dated 31-03-2011</div></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="67%">&nbsp;</td>
                                                                <td width="33%"><input name="newentry" type="button" class= "swiftchoicebutton" id="newentry" value="New" onclick="newinvoiceentry();" />
                                                                  &nbsp;&nbsp;&nbsp;
                                                                  <input name="preview" type="button" class= "swiftchoicebutton" id="preview" value="Preview"  onclick="previewinvoice();"/></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                              </div><div style="display:none;" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="viewinvoicediv" style="width:650px; height:150px;" >
                                          <table width="100%" border="0" cellspacing="0" cellpadding="3"  bgcolor="#FFEAEA" style="border:1px solid #FFA8A8" height="150px;">
                                            <tr >
                                              <td ><div align="center"><strong style="font-size:14px">Transaction Successfull click here to <a  class="resendtext" style="cursor:pointer" onclick="viewinvoice('');">view invoice</a></strong> </div></td>
                                              </tr>
                                          </table>
                                        </div></td>
  </tr>
</table>
</div></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td valign="top"></td>
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
                      <td height="20px"><strong>Existing Invoices</strong></td>
                    </tr>
                    <tr>
                      <td><div align="right"><a onclick="generateinvoicedetails('');" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg"   alt="Refresh customer" border="0" align="middle" title="Refresh Invoice Data"  /></a></div></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #308ebc; border-top:none;">
                          <tr class="header-line">
                            <td width="45%"><span style="padding-left:4px;">Invoice Details</span></td>
                            <td width="24%"><span id="invoicedetailsgridwb1" style="text-align:center">&nbsp;</span></td>
                            <td width="31%"></td>
                          </tr>
                          <tr>
                            <td colspan="3" align="center"><div style="overflow:auto;padding:0px; height:250px; width:709px">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                  <tr>
                                    <td align="center"><div id="invoicedetailsgridc1_1" >
                                        <table width="100%" cellpadding="3" cellspacing="0" class="table-border-grid"  style="border-bottom:none;">
                                          <tr class="tr-grid-header">
                                            <td nowrap = "nowrap" class="td-border-grid" align="left">Sl No</td>
                                            <td nowrap = "nowrap" class="td-border-grid" align="left">Date</td>
                                            <td nowrap = "nowrap" class="td-border-grid" align="left">Invoice No</td>
                                            <td nowrap = "nowrap" class="td-border-grid" align="left">Invoice Amount</td>
                                            <td nowrap = "nowrap" class="td-border-grid" align="left">Generated By</td>
                                            <td nowrap = "nowrap" class="td-border-grid" align="left">Action
                                              <input type="hidden" name="invoicelastslno" id="invoicelastslno" /></td>
                                          </tr>
                                          <tr>
                                            <td colspan="6" valign="top" class="td-border-grid"><div align="center">No datas found to display</div></td>
                                          </tr>
                                        </table>
                                      </div></td>
                                  </tr>
                                  <tr>
                                    <td ><div id="invoicedetailsgridc1link" style="height:20px;  padding:2px;" align="left"> </div></td>
                                  </tr>
                                </table>
                              </div></td>
                          </tr>
                        </table>
                        <div id="invoicedetailsresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td width="100%" align="center"><div style="display:none" ><div style="overflow:auto;padding:0px; height:250px; width:709px;" id="cardpreviewdiv">
                                <form id="cardselectionform" name="cardselectionform"  method="post" action="" onsubmit="return false;"> <table width="100%" border="0" cellspacing="0" cellpadding="0" >
                                  <tr>
                                    <td align="center"><div id="carddetailsgridc1_1" >
                                        
                                      </div></td>
                                  </tr>
                                  <tr>
                                    <td ><div id="carddetailsgridc1link" style="height:20px;  padding:2px;" align="left"> </div></td>
                                  </tr>
                                  <tr>
                                    <td >&nbsp;</td>
                                  </tr>
                                   <tr>
                                    <td ><div align="right" style="padding-right:10px;"><input type="button" onclick="selectconvertioncard();" value="Select" id="selectcard" class="swiftchoicebutton" name="selectcard">&nbsp;&nbsp;&nbsp;<input type="button" onclick="cancelconvertioncard();" value="Cancel" id="cancelcard" class="swiftchoicebutton" name="cancelcard"></div></td>
                                  </tr>
                                </table></form>
                              </div></div></td>
                          </tr>
                        </table>
                        <div id="carddetailsresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><div style="display:none"><div id="invoicepreviewdiv" style="width:650px; height:550px; overflow:auto"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                             <tr>
      <td colspan="2"><div align="center"><font color="#FF0000"><strong>PREVIEW (Please verify and Proceed)</strong></font></div></td>
      </tr>
  <tr>
    <td colspan="2"><table width="98%" cellspacing="0" cellpadding="4" border="0" class="table-border-grid">
  <tbody>
    <tr class="tr-grid-header">
      <td valign="top" align="left" colspan="2" width="60%"><strong>Customer Details</strong></td>
      <td width="40%" valign="top" align="left"><strong>Invoice Details</strong></td>
    </tr>
    <tr>
      <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Customer ID:</strong> <span id="customeridpreview"></span></td>
      <td class="td-border-grid" style="text-align: left;"><strong>Date:</strong><span id="invoicedatepreview"> </span></td>
    </tr>
    <tr>
      <td valign="top" align="left" class="td-border-grid" colspan="2" height="40px" id="companynamepreview" nowrap="nowrap">&nbsp;</td>
      <td width="49%" valign="top" align="left" class="td-border-grid"><span style="text-align: left;"><strong>Inv No:</strong> <span id="billnumber" style="text-align: right;">Not Available</span></span></td>
    </tr>
    <tr>
      <td valign="top" align="left" class="td-border-grid" colspan="2" id="addresspreview">&nbsp;</td>
      <td width="49%" class="td-border-grid" style="text-align: left;"><strong>Marketing Exe:</strong> <span  id="marketingexepreview">Not Selected</span></td>
    </tr>
    <tr>
      <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Contact Person:</strong><span id="contactpersonpreview"></span></td>
      <td width="49%" class="td-border-grid" style="text-align: left;"><strong>Service Tax No:</strong> AABCR7796NST001</td>
    </tr>
    <tr>
      <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Email :</strong>&nbsp;<span id="emailpreview"></span></td>
      <td width="49%" class="td-border-grid" style="text-align: left;"><strong>Region: </strong>*******<span id="branch"></span></td>
    </tr>
    <tr>
      <td width="29%" class="td-border-grid" style="text-align: left;"><strong>Phone:</strong>&nbsp;<span id="phonepreview" ></span></td>
      <td width="22%" class="td-border-grid" style="text-align: left;"><strong>Cell:</strong>&nbsp;<span id="cellpreview"> </span></td>
      <td class="td-border-grid" style="text-align: left;"><strong>Company's PAN:</strong> AABCR7796N</td>
    </tr>
    <tr>
      <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Type of Customer: </strong>&nbsp;<span id="custypepreview"></span></td>
      <td class="td-border-grid" style="text-align: left;"><strong>Company's VAT TIN:</strong> 29730052233</td>
    </tr>
    <tr>
      <td class="td-border-grid" style="text-align: left;" colspan="2"><strong>Category of Customer: </strong>&nbsp; <span id="cuscategorypreview"></span></td>
      <td class="td-border-grid" style="text-align: left;"><strong>CST No:</strong> 71684955 &nbsp; <strong>w.e.f. :</strong> 16/1/2001<br></td>
    </tr>
    <tr>
      <td valign="top" align="left" colspan="3"  class="td-border-grid"><table width="100%" cellspacing="0" cellpadding="0" border="0" >
          <tbody>
           
            <tr >
              <td width="23%" ><table width="98%" border="0" cellspacing="0" cellpadding="4" id="previewproductgrid" align="center" class="grey-table-border-grid">
               
              </table>
                <br></td>
              </tr>
          </tbody>
        </table></td>
    </tr>
    <tr>
      <td valign="top" class="td-border-grid" style="text-align: left; " colspan="2"><table width="100%" cellspacing="0" cellpadding="2" border="0">
          <tbody>
            <tr>
              <td><div align="left"><strong>Invoice Remarks: </strong>&nbsp;<span id="invoiceremarkspreview"></span></div></td>
            </tr>
            <tr>
              <td><div align="left"><strong> Payment Remarks: </strong>&nbsp;<span id="paymentremarkspreview"></span></div></td>
            </tr>
          </tbody>
        </table></td>
      <td class="td-border-grid" style="text-align: left; "><div align="center"><font color="#ff0000">For <strong>RELYON SOFTECH LTD</strong></font> <br>
          <br>
          <br>
          <span id="generatedbypreview"><?php echo($fullname)?></span></div></td>
    </tr>
  </tbody>
</table></td>
  </tr>
  <tr>
      <td width="51%" valign="top" style="text-align: center; padding-top:10px;"><div id="proceedprocessingdiv"></div></td>
      <td width="49%" valign="top" style="text-align: center; padding-top:10px;">
        
        <div align="center">
  <input name="proceed" type="button" class= "swiftchoicebutton" id="proceed" value="Proceed"  onclick="proceedforpurchase();"/>
  &nbsp;&nbsp;  &nbsp;&nbsp;
  <input name="cancel" type="button" class= "swiftchoicebutton" id="cancel" value="Cancel"  onclick="cancelpurchase();"/>
        </div></td>
  </tr>
</table>

                            </div></div></td>
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
      </table></td>
  </tr>
</table>
<script>refreshcustomerarray();</script>
<?php } ?>
