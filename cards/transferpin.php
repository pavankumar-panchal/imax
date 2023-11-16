<?php
if($p_newtransferpin <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
  include('../inc/eventloginsert.php');
?>
    <link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
    <script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
    <script language="javascript" src="../functions/transferpin.js?dummy=<?php echo (rand());?>"></script>
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
                                <td width="27%" class="active-leftnav">Transfer PIN Numbers</td>
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
                                <td class="header-line" style="padding:0">&nbsp;Enter / Edit / View Details</td>
                                <td align="right" class="header-line" style="padding-right:7px"></td>
                              </tr>
                              <tr>
                                <td colspan="2" valign="top"><div id="maindiv">
                                    <form action="" method="post" name="cardsearchfilterform" id="cardsearchfilterform" onsubmit="return false;">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                        <tr>
                                          <td width="100%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr>
                                                <td width="25%"><table width="99%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                    <td colspan="2" align="left" class="active-leftnav">Pin Number Selection</td>
                  </tr>
                                                  <tr>
                                                            <td width="71%" height="34" id="pinselectionprocess" align="left" style="padding:0">&nbsp;
                                                            
                                                            </td>
                                                <td width="29%" style="padding:0"> <div align="right"> <img src="../images/refresh-card.gif" onclick="refreshcardarray();" /></div>
                                                  </td>
                                                  </tr>
                                                  
                                                    <tr>
                                                      <td colspan="2" align="center"><input name="cardsearchtext" type="text" class="swifttext" id="cardsearchtext" onkeyup="cardsearch(event);"  autocomplete="off" style="width:204px"/>
                                                        <span style="display:none1">
                                                        <input name="cardlastslno" type="hidden" id="cardlastslno"  disabled="disabled"/>
                                                        </span>
                                                        <select name="cardlist" size="5" class="swiftselect" id="cardlist" style="width:210px; height:200px" onclick ="selectcardfromlist();" onchange="selectcardfromlist();"  >
                                                        </select></td>
                                                    </tr>
                                                  </table></td>
                                                <td width="65%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #BFDFFF">
                                                    <tr bgcolor="#EDF4FF">
                                                      <td bgcolor="#EDF4FF" width="22%"  align="left" >Transfer PIN Number: </td>
                                                      <td bgcolor="#EDF4FF"><input name="transfercardfield" type="text" class="swifttext" id="transfercardfield" size="30" readonly="readonly" align="left" />
                                                        <input name="transfercardid" type="hidden" class="swifttext" id="transfercardid" size="30" readonly="readonly" align="left" /></td>
                                                      <td colspan="2" bgcolor="#EDF4FF"><span id="registerationspan"></span></td>
                                                    </tr>
                                                    <tr bgcolor="#f7faff">
                                                      <td bgcolor="#f7faff">&nbsp;</td>
                                                      <td bgcolor="#f7faff" align="left" ><div align="center"><strong>From</strong></div></td>
                                                      <td bgcolor="#f7faff" colspan="2" align="left"><div align="center"><strong>To</strong></div></td>
                                                    </tr>
                                                    <tr bgcolor="#EDF4FF">
                                                      <td valign="top" align="left">Dealer:</td>
                                                      <td valign="top" align="left"><input name="tfdealer" type="text" class="swifttext" id="tfdealer" size="30" disabled="disabled" />
                                                        <input name="delaerrep" type="hidden" class="swifttext" id="delaerrep" size="30" readonly="readonly" /></td>
                                                      <td  valign="top" align="left"><input type="checkbox" name="ttdealercheck" id="ttdealercheck" onclick="dealercheckbox()" /></td>
                                                      <td  valign="top" align="left"><select name="ttdealerto" class="swiftselect-mandatory" id="ttdealerto" style="width:180px;"  disabled="disabled">
                                                          <option value="">Make A Selection</option>
                                                          <?php 
                          include('../inc/firstdealer.php');
                          ?>
                                                        </select>
                                                        <input name="ttdealer" type="hidden" id="ttdealer" /></td>
                                                    </tr>
                                                    <tr  bgcolor="#f7faff">
                                                      <td valign="top" align="left">Product:</td>
                                                      <td valign="top" align="left"><input name="tfproduct" type="text" class="swifttext" id="tfproduct" size="30" disabled="disabled" />
                                                        <input name="productcode" type="hidden" class="swifttext" id="productcode" size="30" readonly="readonly" /></td>
                                                      <td valign="top" align="left"><input type="checkbox" name="ttproductcheck" id="ttproductcheck"  onclick="productcheckbox()"  /></td>
                                                      <td valign="top" align="left"><select name="ttproductto" class="swiftselect-mandatory" id="ttproductto" style="width:180px;" disabled="disabled">
                                                          <option value="">Make A Selection</option>
                                                          <?php 
                          include('../inc/firstproduct.php');
                          ?>
                                                        </select>
                                                        <input name="ttproduct" type="hidden" id="ttproduct" /></td>
                                                    </tr>
                                                    <tr bgcolor="#EDF4FF">
                                                      <td bgcolor="#EDF4FF" valign="top" align="left">Purchase Type:</td>
                                                      <td bgcolor="#EDF4FF" valign="top" align="left"><input name="tfpurchasetype" type="text" class="swifttext" id="tfpurchasetype" size="30" disabled="disabled"/></td>
                                                      <td bgcolor="#EDF4FF" valign="top"  align="left"><input type="checkbox" name="ttpurchasetypecheck" id="ttpurchasetypecheck" onclick="purchasecheckbox()"  /></td>
                                                      <td bgcolor="#EDF4FF" valign="top" align="left"><label></label>
                                                        <select name="ttpurchasetype" class="swiftselect" id="ttpurchasetype"  disabled="disabled" >
                                                          <option value="" selected="selected"></option>
                                                          <option value="new">New</option>
                                                          <option value="updation">Updation</option>
                                                        </select></td>
                                                    </tr>
                                                    <tr bgcolor="#f7faff">
                                                      <td bgcolor="#f7faff" valign="top" align="left">Usage Type:</td>
                                                      <td bgcolor="#f7faff" valign="top" align="left"><input name="tfusagetype" type="text" class="swifttext" id="tfusagetype" size="30" disabled="disabled"/></td>
                                                      <td bgcolor="#f7faff" valign="top" align="left"><input type="checkbox" name="ttusagetypecheck" id="ttusagetypecheck" onclick="usagecheckbox()" /></td>
                                                      <td bgcolor="#f7faff" valign="top" align="left"><select name="ttusagetype" class="swiftselect" id="ttusagetype"  disabled="disabled" >
                                                          <option value="" selected="selected"></option>
                                                          <option value="singleuser">Single User</option>
                                                          <option value="multiuser">Multi User</option>
                                                          <option value="additionallicense">Additional License</option>
                                                        </select></td>
                                                    </tr>
                                                    
                                                    <tr bgcolor="#f7faff">
                                                      <td bgcolor="#f7faff" valign="top" align="left">Attached Customer:</td>
                                                      <td bgcolor="#f7faff" valign="top" align="left"><input name="tfattachedcust" type="text" class="swifttext" id="tfattachedcust" size="30" disabled="disabled"/></td>
                                                      <td bgcolor="#f7faff" valign="top" align="left"><input type="checkbox" name="ttattachedcustcheck" id="ttattachedcustcheck" onclick="attachedcustcheckbox()" /></td>
                                                      <td bgcolor="#f7faff" valign="top" align="left"><input type="text" name="ttattachedcust" class="swifttext-readonly" id="ttattachedcust" size="25"  disabled="disabled" onchange="checkcustomerid();" >
                                                    <input type="hidden" name="tfregisteration" class="swifttext-readonly" id="tfregisteration" size="30" readonly="readonly" >
                                                      <?php if($userid == '1' || $userid == '146' || $userid =='150' || $userid =='167'  || $userid =='168'){?><span id="ttmoveregisteration"></span><?php ;}?>
                                                        
                                                        </td>
                                                    </tr>
                                                    
                                                    <tr bgcolor="#EDF4FF">
                                                      <td bgcolor="#EDF4FF" valign="top" align="left">Remarks:</td>
                                                      <td bgcolor="#EDF4FF" valign="top" align="left"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks"></textarea>
                                                      <td bgcolor="#EDF4FF" valign="top" align="left"></td>
                                                      <td bgcolor="#EDF4FF" valign="top" align="left">Transfer By&nbsp;:
                                                        <input name="transferby" class="swifttext" id="transferby" style="background:#FEFFE6; width:169px" size="30" maxlength="40" readonly="readonly"  autocomplete="off" value="<?php echo $fullname;?>"></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="4" height="23" valign="middle"><div id="form-error" align="center"></div></td>
                                                    </tr>
                                                  </table></td>
                                              </tr>
                                            </table></td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;">
                                          <table width="98%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td width="40%" height="35" align="left" valign="middle">&nbsp;</td>
                                                <td width="60%" height="35" align="right" valign="middle"><div align="center">
                                                    <input name="transfer" type="button" class="swiftchoicebutton" id="transfer" value="Transfer" onclick="transferscratchdetails();" />
                                                    &nbsp;&nbsp;
                                                    <input name="reset" type="button" class="swiftchoicebutton" id="reset" value="Reset" onclick="newentry();" />
                                                  </div></td>
                                              </tr>
                                            </table></td>
                                        </tr>
                                      </table>
                                    </form>
                                  </div></td>
                              </tr>
                              
                              <tr>
      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                <tr class="header-line">
                  <td width="194" align="left"  style="padding:0"><div id="tabdescription">&nbsp; &nbsp;Transfered Pin Details</div></td>
                  <td width="304"  style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                  <td width="234"  style="padding:0; text-align:left;">&nbsp;</td>
                </tr>
                <tr>
                  <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:200px; width:915px; padding:2px; " align="center">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td height="10px"><div id="tabgroupgridc1_1" align="center"></div></td>
                        </tr>
                        <tr>
                          <td><div id="tabgroupgridc1link" align="left" ></div></td>
                        </tr>
                      </table>
                    </div>
                    <div id="custresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
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
          </table></td>
      </tr>
    </table>
<script>refreshcardarray();</script>
<?php } ?>