<?php
if($p_smssummary <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/smscreditssummary.js?dummy=<?php echo (rand());?>"></script>
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
                            <td width="27%" align="left" class="active-leftnav">SMS  Summary</td>
                            <td width="60%">&nbsp;</td>
                            <td width="13%"><div onclick="getsmscreditssummary('');getsmscreditssummarypromo('')" ><img src="../images/imax-customer-refresh.jpg" alt="Refresh Data" title="Refresh Data Data" style="cursor:pointer" /><span class="resendtext">Refresh Data</span></div></td>
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
                      <td><form id="submitform" name="submitform" action="" method="post">
                        <table width="97%" border="0" cellspacing="0" cellpadding="0"  align="center">
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                            <table width="100%" border="0"  cellpadding="5" cellspacing="0" style="border:1px solid #308ebc;" bgcolor="#FFF0F0">
                                  <tr>
                                    <td width="7%"  style="border-right:1px solid #CCCCCC;border-bottom:1px solid  #CCCCCC"></td>
                                    <td width="13%" valign="top" class="smstextheading2" style="border-bottom:1px solid #CCCCCC"><div align="center">Total Purchased</div></td>
                                    <td width="11%" valign="top" class="smstextheading2" style="border-bottom:1px solid #CCCCCC"><div align="center">Total Utilized</div></td>
                                    <td width="13%" valign="top" class="smstextheading2" style="border-right:1px solid #CCCCCC;border-bottom:1px solid #CCCCCC"><div align="center">Stock Available</div></td>
                                    <td width="13%" valign="top" class="smstextheading2" style="border-bottom:1px solid #CCCCCC"><div align="center">Total Allocated</div></td>
                                    <td width="16%" valign="top" class="smstextheading2" style="border-bottom:1px solid #CCCCCC" ><div align="center">Total Used by users</div></td>
                                    <td width="15%" valign="top" class="smstextheading2" style="border-right:1px solid #CCCCCC;border-bottom:1px solid #CCCCCC"><div align="center">Unused with users</div></td>
                                    <td width="11%" valign="top" class="smstextheading2" style="border-bottom:1px solid #CCCCCC"><div align="center">Balance Available</div></td>
                                  </tr>
                                  <tr height="25px;">
                                    <td valign="top" class="smstextheading2" style="border-right:1px solid #CCCCCC">Service</td>
                                    <td valign="top" class="smstextheading" ><div align="center" id="totalpurchased"></div></td>
                                    <td valign="top" class="smstextheading" ><div align="center" id="totalutilized"></div></td>
                                    <td valign="top" class="smstextheading" style="border-right:1px solid #CCCCCC"><div align="center" id="stockavailable"></div></td>
                                    <td valign="top" class="smstextheading" ><div align="center" id="totalallocatd"></div></td>
                                    <td valign="top" class="smstextheading" ><div align="center" id="totalusedbyusers"></div></td>
                                    <td valign="top" class="smstextheading" style="border-right:1px solid #CCCCCC"><div  id="unusedwithusers" align="center"></div></td>
                                    <td valign="top" class="smstextheading" ><div  id="balanceavailable" align="center"></div></td>
                                  </tr>
                                  <tr height="25px;">
                                    <td valign="top" class="smstextheading2" style="border-right:1px solid #CCCCCC">Promotional</td>
                                     <td valign="top" class="smstextheading" ><div align="center" id="totalpurchasedpromo"></div></td>
                                    <td valign="top" class="smstextheading" ><div align="center" id="totalutilizedpromo"></div></td>
                                    <td valign="top" class="smstextheading" style="border-right:1px solid #CCCCCC"><div align="center" id="stockavailablepromo"></div></td>
                                    <td valign="top" class="smstextheading" ><div align="center" id="totalallocatdpromo"></div></td>
                                    <td valign="top" class="smstextheading" ><div align="center" id="totalusedbyuserspromo"></div></td>
                                    <td valign="top" class="smstextheading" style="border-right:1px solid #CCCCCC"><div  id="unusedwithuserspromo" align="center"></div></td>
                                    <td valign="top" class="smstextheading" ><div  id="balanceavailablepromo" align="center"></div></td>
                                  </tr>
                                </table>
                            </div></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="197" align="left"  style="padding:0"><div id="tabdescription">&nbsp; Service Account Summary:</div></td>
                                  <td width="497"  style="padding:0; text-align:center;"><span id="tabgroupgridwb1"></span></td>
                                  <td width="4" align="left"  style="padding:0">&nbsp;</td>
                                  <td width="223" align="left"  style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:260px; width:904px; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link"  align="left" style="height:20px; padding:2px;"> </div></td>
                                        </tr>
                                      </table>
                                  </div>
                                      <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
                                </tr>
                            </table></td>
                          </tr>
                          <tr>
                                <td>&nbsp;</td>
                                </tr>
                          <tr>
                                <td width="100%" align="right" valign="middle"><input type="hidden" name="flag" id="flag" value="true" /><input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="filtertoexcel('toexcel');"/></td>
                                </tr>
                                <tr>
                                <td>&nbsp;</td>
                                </tr>
                          <tr>
                            <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="209" align="left"  style="padding:0"><div id="tabdescriptionpromo">&nbsp; Promotional Account Summary:</div></td>
                                  <td width="485"  style="padding:0; text-align:center;"><span id="tabgroupgridwb1promo"></span></td>
                                  <td width="4" align="left"  style="padding:0">&nbsp;</td>
                                  <td width="223" align="left"  style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top"><div id="tabgroupgridc1promo" style="overflow:auto; height:260px; width:904px; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc1_1promo" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1linkpromo"  align="left" style="height:20px; padding:2px;"> </div></td>
                                        </tr>
                                      </table>
                                  </div>
                                      <div id="resultgridpromo" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
                                </tr>
                            </table></td>
                          </tr>
                        </table>
                      </form></td>
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