<?
if($p_dealerattachcard <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/dealerattachcard.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/dealer-cardattach.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" valign="middle" class="active-leftnav">Customer Selection</td>
              </tr>
              <tr>
                <td colspan="2"><form id="filterform" name="filterform" method="post" action="" onSubmit="return false;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="3">
                      <tr>
                        <td width="71%" height="34" id="customerselectionprocess" style="padding:0" align="left">&nbsp;</td>
                        <td width="29%" id="customerselectionprocess" style="padding:0"><div align="right"><a onclick="gettotalcustomercount();" style="cursor:pointer; padding-right:10px;"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a></div></td>
                      </tr>
                      <tr>
                        <td colspan="2" align="left"><input name="description" type="hidden" class="swifttext" id="description"  style="width:191px" /><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onKeyUp="customersearch(event);"  autocomplete="off" style="width:204px"/>
                          <div id="detailloadcustomerlist">
                          <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:400px"  onchange="selectfromlist();">
                                                      </select> 
                          </div>                       </td>
                      </tr>
                    </table>
                  </form></td>
              </tr>
              <tr>
                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcount" align="left">&nbsp;</td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                        <tr>
                          <td width="27%" align="left" class="active-leftnav">Attach PIN Serial Numbers</td>
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
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    
                                    <tr>
                                      <td width="48%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#EDF4FF">
                                            <td width="37%" align="left" valign="top" bgcolor="#EDF4FF">Business Name:
                                            <input type="hidden" name="lastslno" id="lastslno">
                                            <input type="hidden" name="purcheck" id="purcheck">
                                            <input type="hidden" name="licensepurchase" id="licensepurchase">
                                            <input type="hidden" name="yearcount" id="yearcount">
                                            <input type="hidden" name="lastyearusagecheck" id="lastyearusagecheck">
                                             </td>
                                            <td width="63%" align="left" valign="top" bgcolor="#EDF4FF" id="displaycustomername">&nbsp;</td>
                                          </tr>
                                          <tr bgcolor="#EDF4FF">
                                            <td width="37%" align="left" valign="top" bgcolor="#EDF4FF">Customer ID:
                                            <td width="63%" align="left" valign="top" bgcolor="#EDF4FF" id="customerid"></td>
                                          </tr>
                                          
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                            <tr>
                                                <td width="53%" height="34px;"><strong>Select Dealer</strong></td>
                                                <td width="47%" height="34px;">
                                                	<select name="dealerid" class="swiftselect" id="dealerid" style="width:180px;" onChange="gettotalcusattachcard();">
                                                        <option value="">ALL</option>
                                                        <? include('../inc/getdealer.php');?>
                                                    </select>
                                				          </td>
                                              </tr>
                                              
                                              <tr>
                                              <td width="53%"><strong>Item Product</strong>: </td>
                                                  <td width="47%"><select name="product" class="swiftselect" id="product" style="width:195px;" onchange="getpin(this.value);">
                                                      <option value="" selected="selected">Select a Item</option>                                                      
                                                    </select>
                                                  </td>
                                              </tr>
                                              <tr>
                                              <td width="53%"><strong>Purchase Type</strong>: </td>
                                              <td width="47%"><select name="purtype" id="purtype" class="swiftselect" onchange="getpindetails();">
                                                <option value="" selected>Select Purchase Type</option>
                                                <option value="new">New</option>
                                                <option value="updation">Updation</option>
                                              </select>
                                              </td>
                                              </tr>
                                              <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
                                              <tr>
                                                <td width="59%" height="34px;"><strong>Select a PIN</strong></td>
                                                <td width="47%" height="34px;"><div id="scratchcradloading"></div></td>
                                              </tr>
                                              <tr>
                                                <td colspan="2"><div id="dispreregcardlist">
                                                                
                                                              <div align="left">
                                                                <input name="searchscratchnumber" type="text" class="swifttext" id="searchscratchnumber"  style="width:191px" onkeyup="reg_cardsearch(event);" />
                                                                <select name="scratchcardlist" size="5" class="swiftselect" id="scratchcardlist" style="width:197px; height:200px" onclick="reg_selectcardfromlist();scratchdetailstoform(document.getElementById('scratchcardlist').value);getcustomerdetails(document.getElementById('scratchcardlist').value);"  >
                                                                </select>
                                                      </div>
                                                  </div></td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                          
                                          
                                      </table><table width="100%">
                                       <tr>
                <td width="22%" style="padding-left:10px;"><strong>Total Count:</strong></td>
                <td width="55%" id="totalcountofcard" align="left"></td>
              </tr>
                                      </table></td>
                                      <td width="52%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                      <tr bgcolor="#EDF4FF">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Date:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input name="currentdate" type="text" class="swifttext" id="currentdate" style="background:#FEFFE6;" size="30" maxlength="40" readonly="readonly"  autocomplete="off" value="<? echo(datetimelocal('d-m-Y')." (".datetimelocal('H:i').")"); ?>"/></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Remarks:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks" ></textarea></td>
                                          </tr>
                                      <tr bgcolor="#f7faff">
                                        <td colspan="2" align="left" valign="top" bgcolor="#f7faff" height="250px;"><div id="detailsonscratch" style="display:none;">
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="3" class="diaplaytableborder">
                                                      <tr>
                                                        <td colspan="3" bgcolor="#EDF4FF" headers="40px"><strong>Card Info</strong></td>
                                                      </tr>
                                                        <tr>
                                                          <td width="38%" valign="top">PIN Serial Number</td>
                                                          <td width="4%" valign="top">:</td>
                                                          <td width="58%" valign="top" id="cardnumberdisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">PIN Number</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="scratchnodisplay">&nbsp;</td>
                                                        </tr>
                                                         <tr>
                                                          <td valign="top">Product Name</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="productdisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Purchase Type</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="purchasetypedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Usage Type</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="usagetypedisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Attached To (Dealer)</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="attachedtodisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Attached To (customer)</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="registeredtodisplay">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td valign="top">Attached Date</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="attachdatedisplay">&nbsp;</td>
                                                        </tr>
                                                                                                             <tr>
                                                          <td valign="top">PIN Status</td>
                                                          <td valign="top">:</td>
                                                          <td valign="top" id="cardstatusdisplay">&nbsp;</td>
                                                        </tr>
                                                         <tr>
                                                           <td valign="top">Customer card Attached Date:</td>
                                                           <td valign="top">:</td>
                                                           <td valign="top" id="cardattacheddate">&nbsp;</td>
                                                         </tr>
                                                      </table>
                                            </div></td>
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
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="44%" align="right" valign="middle"><input name="attachcard" type="button" class= "swiftchoicebuttondisabledbig" id="attachcard" value="Attach PIN Number"  disabled="disabled" onClick="formsubmit('attachcard');" />
                                              &nbsp;
                                              <input name="detachcard" type="button" class="swiftchoicebuttondisabledbig" id="detachcard" value="Detach PIN Number" onClick="formsubmit('detachcard');" disabled="disabled"  />
                                              &nbsp;
                                             </td>
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
                                  <td width="314" align="left"  style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Attach PIN Numbers (Not Registered)</div></td>
                                  <td width="418" style="padding:0; text-align:left;"><span id="tabgroupgridwb1"></span></td>
                                </tr>
                                <tr>
                                  <td colspan="2" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:704px; padding:2px; " align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td height="10px"><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link" align="left"></div></td>
                                        </tr>
                                      </table>
                                    </div>
                                    <div id="custresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>                                   </td>
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
gettotalcustomercount();
//refreshdealerattachcardarray();
</script>
<? } ?>