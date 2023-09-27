<?php
if($p_viewinvoice <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");

?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/viewinvoice.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<?php $userid = imaxgetcookie('userid');?>
<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
  <tr>
    <td valign="top" style="border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">View Invoices </td>
                            <td width="60%">&nbsp;</td>
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
                      <td><table width="97%" border="0" cellspacing="0" cellpadding="0"  align="center">
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv"  >
                                <form id="submitform" name="submitform" action="" method="post">
                                  <table width="100%" border="0"  cellpadding="0" cellspacing="0">
                                    <tr height="280px">
                                      <td height="200px"><input type ="hidden" name = "lastslno" id = "lastslno"><div class="invoicetext" align="center"  id="displayinvoicetext" style="display:block; "> No Invoice Selected </div>
                                        <div id="displaygridinfo" style="display:none;" ></div></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td width="49%"  id="productselectionprocess">&nbsp;</td>
                            <td width="51%" height="25px" style="padding:5px"  valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0"><tr>
              <td height="35" align="left" valign="middle">&nbsp;&nbsp;
                  <div align="right">
                    &nbsp;&nbsp;
                    <input name="view" type="button" class="swiftchoicebuttonbig" id="view" value="View Invoice" onclick="viewinvoice()"/>
                    &nbsp;&nbsp;
                    <input name="send" type="button" class="swiftchoicebuttonbig" id="send" value="Resend Invoice" onclick="resendinvoice();" />
                 </div>
                 
              </tr>

</table></td>
                          </tr>
                          <tr>
                            <td colspan="2"><div >
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                                <tr><td style="border-right:1px solid #d1dceb;"  class="header-line">Filter: <a onclick="showhidefilterdiv()" class="filterdiv">[show/hide]</a></td></tr>
                                  <tr>
                                    <td valign="top"><div id="filterdiv" style="display:none;">
                                        <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                      <tr>
                                              <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr bgcolor="#edf4ff">
                                                    <td width="14%" align="left" valign="top">Search Text: </td>
                                                    <td colspan="3" align="left" valign="top"><input name="searchcriteria" type="text" id="searchcriteria" size="50" maxlength="25" class="swifttext"  autocomplete="off" value=""/><input type="hidden" name="searchtexthiddenfield" value=""/><input type="hidden" name="subselectionhiddenfield" value=""/><input type="hidden" name="orderbyhiddenfield" value=""/></td>
                                                  </tr>
                                                  <tr bgcolor="#f7faff">
                                                    <td colspan="4" align="left" valign="top" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                        <tr>
                                                          <td width="14%" valign="top" align="left">In: </td>
                                                          <td width="86%" align="left">
                                                           <label>
                                                            <input type="radio" name="databasefield" value="invoiceno" id="databasefield3" />
                                                            Invoice No</label>&nbsp;<label>
                                                            <input type="radio" name="databasefield" id="databasefield0" value="customerid"/>
                                                            Customer ID </label>
                                                            <label> &nbsp;
                                                            <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                            Business Name</label>
                                                            &nbsp;
                                                            <label>
                                                            <input type="radio" name="databasefield" value="contactperson" id="databasefield2" />
                                                            Contact Person</label>
                                                            &nbsp;
                                                            <label>
                                                            <input type="radio" name="databasefield" id="databasefield4" value="createddate" />
                                                            Date</label>
                                                            &nbsp;&nbsp;
                                                            <label>
                                                            <input type="radio" name="databasefield" value="dealername" id="databasefield5" />
                                                            Dealer Name</label>
                                                            <label>
                                                            <input type="radio" name="databasefield" value="createdby" id="databasefield6" />
                                                            Generated by </label></td>
                                                        </tr>
                                                      </table>
                                                      <label></label></td>
                                                  </tr>
                                                  <tr bgcolor="#edf4ff">
                                                    <td width="14%" align="left" valign="top" colspan="3">Order By:</td>
                                                        <td width="86%" align="left" valign="top"><select name="orderby" id="orderby" class="swiftselect"><option value="invoiceno" selected="selected">Invoice number </option>
                                                        <option value="customerid">Customer ID</option>
                                                        <option value="businessname" >Business Name</option>
                                                        <option value="contactperson">Contact Person</option>
                                                        
                                                        <option value="date">Date</option>
                                                        <option value="dealername">Dealer Name</option>
                                                        <option value="generatedby">Generated By</option>
                                                    </select></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                            <tr>
                                              <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td width="69%" height="35" align="left" valign="middle"><div id="filter-form-error"></div></td>
                                                    <td width="31%" align="right" valign="middle"><input name="filter" type="button" class="swiftchoicebutton" id="filter" value="Search" onclick="searchfilter('');" />
                                                      &nbsp;&nbsp;
                                                      <input name="close" type="button" class="swiftchoicebutton-red" id="close" value="Close" onclick="document.getElementById('filterdiv').style.display='none';" /></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table>
                                        </form>
                                      </div></td>
                                  </tr>
                                </table>
                              </div></td>
                          </tr>
                          <tr>
                            <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default'); getinvoicedetails(''); " style="cursor:pointer" class="grid-active-tabclass">Default</td>
                                  <td width="2">&nbsp;</td>
                                  <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');" style="cursor:pointer" class="grid-tabclass">Search Result</td>
                                  <td width="2">&nbsp;</td>
                                 <td width="140" align="center" ></td>
                                   <td width="140" align="center" ></td>
                                    <td width="140" align="center" ></td>
                                     <td width="140" align="center" ></td>
                                  <td><div id="gridprocessing"></div></td>
                                </tr>
                              </table></td>
                          </tr>
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line" >
                                  <td width="220px"><div id="tabdescription"></div></td>
                                  <td width="216px" style="text-align:center;"><span id="tabgroupgridwb1" ></span></td>
                                  <td width="296px" style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:150px; width:904px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center" ></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link" align="left" >
</div></td>
  </tr>
</table><div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></div>
                                    <div id="tabgroupgridc2" style="overflow:auto;height:150px; width:904px; padding:2px; display:none;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc2_1" ></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc2link" align="left">
</div></td>
  </tr>
</table><div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div>
                                    </div>                                    </td>
                                </tr>
                            </table></td>
                          </tr>
                        </table></td>
                          </tr>
                        </table></td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
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
<?php } ?>