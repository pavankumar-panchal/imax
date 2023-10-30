<?php
if($p_dealerreceiptreconciliation <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/dealerreceiptreconciliation.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictfunction.php?dummy=<?php echo(rand());?>"></script>
<script language="javascript" src="../functions/datepickercontrol.js?dummy=<?php echo (rand());?>" type="text/javascript"></script>
<div style="left: -1000px; top: 597px;visibility: hidden; z-index:100" id="tooltip1">dummy</div>
<script language="javascript" src="../functions/tooltip.js?dummy=<?php echo (rand());?>"></script>

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
                            <td width="27%" class="active-leftnav">Dealer Receipt Reconciliation</td>
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
                            <td class="header-line" style="padding:0">&nbsp;Search Receipt Entries</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2" > 
                                    
                                   <tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="2" bgcolor="#FBF3DB">
 <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                        <tr>
                                          <td width="22%" align="left" valign="top" >From Date: </td>
                                          <td width="78%" align="left" valign="top" ><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/> <input type="hidden" name="flag" id="flag" value="true" />
                                          <input type="hidden" name="category" id="category" value="<?php echo($pagelink) ?>" />
                                            <input type="hidden" name="hiddentotalreceipts" id="hiddentotalreceipts" value="" />
                                            <input type="hidden" name="hiddentotalamount" id="hiddentotalamount" value="" /></td>
                                        </tr>
                                        <tr>
                                          <td align="left" valign="top">Mode:</td>
                                          <td align="left" valign="top"><select name="paymentmode" class="swiftselect" id="paymentmode"  style="width:200px">
                                            <option value="" selected="selected">---------------ALL---------------</option>
                                            <!-- <option value="cash">Cash</option> -->
                                            <option value="chequeordd">Cheque-DD</option> 
                                            <option value="onlinetransfer">Online Transfer</option>
                                            <option value="creditordebit">Credit-Debit Card</option>
                                            <!-- <option value="Netbanking">Netbanking</option> -->

                                          </select></td>
                                        </tr>
                                         

                                      </table></td>
                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                            <td colspan="2" valign="top" style="padding:0"></td>
                                          </tr>
                                          <tr>
                                            <td width="22%" align="left" valign="top">To Date:</td>
                                            <td width="78%" align="left" valign="top"><label for="sto"></label><label for="spp">
                                              <input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<?php echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
                                              <br/>
                                            </label></td>
                                          </tr>
                                          <tr>
                                            <td align="left" valign="top">&nbsp;</td>
                                            <td align="left" valign="top">&nbsp;</td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><div align="left" style="display:block;height:20px; padding-top:5px; " id="detailsdiv" >
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="87%" ><div style="border-top:dashed 1px #000000;" align="center"></div></td>
                                              <td width="13%" ><div align="right"  onclick="displayDiv('1','filterdiv')" class="resendtext"><strong style=" padding-right:10 px">Advanced Options</strong></div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"><div id="filterdiv" style="display:none;">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                            <tr>
                                              <td width="100%" valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0">
                                              <tr><td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr>
                                            <td width="43%"><table width="99%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td width="9%" align="left" valign="top" >Text: </td>
                                                <td width="91%" colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="33" maxlength="150" class="swifttext"  autocomplete="off" value=""/>
                                                <span style="font-size:8px; color:#939393;">(Leave Empty for all)</span></td>
                                                <td>&nbsp;</td>
                                              </tr>
                                            </table></td>
                                            <td width="12%"><div align="right"><strong>Load Selection</strong>:</div></td>
                                            <td width="11%" id="displayloadselection"><select name="loadselection" class="swiftselect" id="loadselection" style="width:100px;">
                                              <option value="default">DEFAULT</option>
                                            </select></td>
                                            <td width="34%"><a onclick="receiptdisplayselection()"><strong class="select-resendtext">Go &#8250;&#8250; </strong></a>&nbsp;/&nbsp;<a onclick="receiptsearchsettings()"><strong class="select-resendtext">Save Current Selection &#8250;&#8250; </strong></a>&nbsp;/&nbsp;<a onclick="deleteselectedsettings()"><strong class="select-resendtext">Delete &#8250;&#8250; </strong></a>
                                                 </span></td>
                                          </tr>
                                        </table></td></tr>
                                                  <tr>
                                                    <td width="60%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                        <tr valign="top" >
                                                          <td width="100%" height="3" style="padding:3px">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td width="42%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                  <td colspan="2"><table width="99%" height="100" border="0" cellpadding="3" cellspacing="0" style="border:solid 1px #004000">
                                                                      <tr>
                                                                      <td align="left"><strong>Look in:</strong></td>
                                                                    </tr>
                                                                    <!-- <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" id="databasefield0" value="slno"/>
                                                                        Customer ID</label>                                                                      </td>
                                                                    </tr> -->
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
                                                                        Business Name</label></td>
                                                                    </tr>
                                                                    <tr >
                                                                      <td style="border-top:solid 1px #999999"  height="1" align="left"></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="invoiceno" id="databasefield11" />
                                                                        Invoice No</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="receiptno" id="databasefield12" />
                                                                        Receipt Number</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="chequeno" id="databasefield14" />
                                                                         Cheque Number</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="chequedate" id="databasefield15" />
                                                                         Cheque Date</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="depositdate" id="databasefield16" />
                                                                        Deposit Date</label></td>
                                                                    </tr>
                                                                     <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="drawnon" id="databasefield17" />
                                                                        Drawn On</label></td>
                                                                    </tr>
                                                                     <tr>
                                                                      <td align="left"><label>
                                                                        <input type="radio" name="databasefield" value="paymentamt" id="databasefield18" />
                                                                        Payment amount</label></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="2"></td>
                                                                    </tr>
                                                                </table></td>
                                                                </tr>
                                                                <tr>
                                                                  <td style="padding:5px"><input name="cancelledinvoice" type="checkbox" id="cancelledinvoice" checked="checked"  /></td>
                                                                  <td ><label for="cancelledinvoice">Do not consider Cancelled  Invoices</label></td>
                                                                </tr>
                                                              </table>
                                                              </td>
                                                                <td width="68%" valign="top" style="padding-left:3px"><table width="102%" border="0" cellspacing="0" cellpadding="6" style="border:solid 1px #cccccc;">
                                                                    <tr>
                                                                      <td colspan="2"><strong>Selections</strong>:</td>
                                                                    </tr>
                                                                    
                                                                   
                                                                    <tr>
                                                                      <td width="39%" height="10" align="left" valign="top">Region:</td>
                                                                      <td width="61%" height="10" align="left" valign="top"><select name="region" class="swiftselect" id="region" style="width:180px;">
                                                                          <option value="">ALL</option>
                                                                          <?php 
											include('../inc/region.php');
											?>
                                                                        </select>
                                                                      </td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10" align="left"> Branch:</td>
                                                                      <td align="left" valign="top"   height="10" ><select name="branch" class="swiftselect" id="branch" style="width:180px;">
                                                                          <option value="">ALL</option>
                                                                          <?php include('../inc/branch.php');?>
                                                                        </select></td>
                                                                    </tr>
                                                                     <tr>
                                                                      <td align="left" valign="top" height="10" >State:</td>
                                                                      <td align="left" valign="top" height="10"><select name="state2" class="swiftselect" id="state2" onchange="getdistrictfilter('districtcodedisplaysearch',this.value);" onkeyup="getdistrictfilter('districtcodedisplaysearch',this.value);" style="width:180px;">
                                                                          <option value="">ALL</option>
                                                                          <?php include('../inc/state.php'); ?>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10" align="left"> District:</td>
                                                                      <td  valign="top"  id="districtcodedisplaysearch" height="10" align="left"><select name="district2" class="swiftselect" id="district2" style="width:180px;">
                                                                          <option value="">ALL</option>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10" align="left"> Dealer:</td>
                                                                      <td align="left" valign="top"   height="10" ><select name="currentdealer" class="swiftselect" id="currentdealer" style="width:180px;">
                                                                          <option value="">ALL</option>
                                                                          <?php include('../inc/dealer-invoice.php');?>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10" align="left"> Generated by:</td>
                                                                      <td align="left" valign="top"   height="10" ><select name="generatedby" class="swiftselect" id="generatedby" style="width:180px;">
                                                                          <option value="">ALL</option>
                                                                          <?php
                                                                              $query = "(select distinct CONCAT(inv_mas_users.slno,'^[U]') as slno,CONCAT(UPPER(inv_mas_users.fullname) ,'  [U]') as name 
                                                                              from inv_mas_users where inv_mas_users.disablelogin = 'no')";
                                                                                $result = runmysqlquery($query);
                                                                                while($fetch = mysqli_fetch_array($result))
                                                                                {
                                                                                  echo('<option value="'.$fetch['slno'].'">'.$fetch['name'].'</option>');
                                                                                }
                                                                            ?>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10" align="left">Invoice Series:</td>
                                                                      <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="series" class="swiftselect" name="series">
                                                                          <option value="" selected="selected">ALL</option>
                                                                          <option value="BKG">BKG</option>
                                                                          <option value="BKM">BKM</option>
                                                                          <option value="CSD">CSD</option>
                                                                          <option value="Online">Online</option>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10" align="left">Invoice Status:</td>
                                                                      <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="status" class="swiftselect" name="status">
                                                                          <option value="" selected="selected">ALL</option>
                                                                          <option value="ACTIVE">ACTIVE</option>
                                                                          <option value="EDITED">EDITED</option>
                                                                          <option value="CANCELLED">CANCELLED</option>
                                                                        </select></td>
                                                                    </tr>
                                                                    <tr>
                                                                      <td height="10" align="left">Receipt Status:</td>
                                                                      <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="receiptstatus" class="swiftselect" name="receiptstatus">
                                                                          <option value="" selected="selected">ALL</option>
                                                                          <option value="ACTIVE">ACTIVE</option>
                                                                          <option value="CANCELLED">CANCELLED</option>
                                                                        </select></td>
                                                                    </tr>
                                                                     <tr>
                                                                       <td height="10" align="left">Reconciliation:</td>
                                                                       <td align="left" valign="top"   height="10" ><select style="width: 180px;" id="reconsilation" class="swiftselect" name="reconsilation">
                                                                         <option value="notseen" selected="selected">NOT SEEN</option>
                                                                         <option value="matched">MATCHED</option>
                                                                         <option value="unmatched">UNMATCHED</option>
                                                                         </select></td>
                                                                     </tr>
                                                                  </table></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table></td>
                                                    <td width="40%"valign="top" style="padding-left:3px"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                        <tr>
                                                          <td colspan="4" valign="top" align="left"><strong>Products: </strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="4" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:134px; overflow:scroll">
                                                              <?php include('../inc/productdetails.php'); ?>
                                                            </div></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="14%" align="left">Select: </td>
                                                          <td width="28%" align="left"><strong>
                                                            <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:75px"   >
                                                              <option value="ALL"  selected="selected">ALL</option>
                                                              <option value="NONE">NONE</option>
                                                              <?php include('../inc/productgroup.php') ?>
                                                            </select>
                                                          </strong></td>
                                                          <td width="58%" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                                                <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                              </tr>
                                                          </table></td>
                                                          </tr>
                                                         <tr>
                                                          <td colspan="3" valign="top" align="left"><strong>Items: </strong></td>
                                                        </tr>
                                                        <tr >
                                                          <td  colspan="3" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:105px; overflow:scroll" >
                                                            <?php include('../inc/itemlist.php'); ?>
                                                          </div></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="3" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td width="6%"><input type="checkbox" name="selectitem" id="selectitem" checked="checked" onchange="selectdeselectcommon('selectitem','itemarray[]')" /></td>
    <td width="93%"><label for="selectitem">Select All / None</label></td>
  </tr>
</table>
</td>
                                                          </tr>
                                                      </table></td>
                                                  </tr>
                                                  
                                                  <tr>
                                                    <td align="right" valign="middle" style="padding-right:3px;"></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
</table>
</td></tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:5px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          
                                          <tr>
                                            <td width="67%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="33%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="searchfilter('');" />
                                              &nbsp;&nbsp;
<input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);" /></td>
                                          </tr>
                                      </table></td>
                                    </tr>
                                    
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr><td colspan="2"><form id="filterform" name="filterform" action="" method="post" onsubmit="return false"><table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
                                      <td align="right" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td></td>
                                        </tr>
                                        <tr>
                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                              <tr class="header-line" >
                                                <td width="220px"><div id="tabdescription"></div></td>
                                                <td width="216px" style="text-align:center;">Receipt Entries <span id="tabgroupgridwb1" ></span></td>
                                                <td width="296px" style="padding:0">&nbsp;</td>
                                              </tr>
                                              <tr>
                                                <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="height:270px; width:925px; padding:1px;" align="center">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td colspan="2"><div id="tabgroupgridc1_1" align="center" ></div></td>
                                                      </tr>
                                                                                                             <tr>
                                                        <td><div id="tabgroupgridc1link" align="left" > </div></td>
                                                      </tr>
                                                    </table>
                                                  <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                                </div>
                                                    </td>
                                              </tr>
                                          </table></td>
                                        </tr>
                                      </table></td>
                                    </tr>
                                    <tr><td>&nbsp;</td></tr>
                                    
</table>
</form></td>
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