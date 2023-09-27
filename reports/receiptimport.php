<?
if($p_importreceipt <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<? echo (rand());?>"  />
<script language="javascript" src="../functions/receiptimport.js?dummy=<? echo (rand());?>"></script>
<SCRIPT language="javascript">
$( document ).ready(function() {
filter();
});
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
                            <td width="27%" class="active-leftnav" colspan="4"> Reciept Import</td>
                          </tr>
                          <tr>
                            <td  height="10" align="right" valign="top" style="padding-right:10px;">Region:</td>
                            <td  height="10" align="left" valign="top" style="width:180px;">
                            	<select name="region" class="swiftselect" id="region" style="width:180px;" onclick="filter();">
                                    <option value="">ALL</option>
                                    <? include('../inc/region.php');?>
                              	</select>
                            </td>
                            <td height="10" align="right" valign="top" style="padding-right:10px;">Prepared By:</td>
                            <td  height="10" align="left" valign="top" style = "width:60%">
                                <select name="generatedby" class="swiftselect" id="generatedby" style="width:180px;" onclick="filter();">
                                    <option value="">ALL</option>
                                    <? include('../inc/generatedby.php');?>
                                </select>
                             </td>
                          </tr>
                          <tr>
                            <td colspan="4">&nbsp;</td>
                          <tr>
                            <td colspan="3" style="padding-left:20px;"><div id="radiovalue" onclick="filter();" >
                                <input type="radio" name="import" id="imort1" value="notimport" checked="checked"  />
                                <strong>Not Imported</strong>
                                <input type="radio" name="import" id="import0" value="import"  />
                                <strong>Imported</strong>
                                <input type="radio" name="import" id="imort2" value="all" />
                                <strong>All</strong> 
                               </div>
                             </td>
                              <td align="left" valign="middle" height="35"><div id="form-error"> </div></td>
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
                          <tr class="header-line">
                            <td width="220px" style="padding-left:20px;">Make A Report</td>
                            <td width="216px" style="text-align:left;"><span id="tabgroupgridwb1" ></span><span id="tabgroupgridwb2" ></span></td>
                            <td width="296px" style="padding:0">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                    <tr>
                                      <td colspan="2" align="left" valign="top"><div id="tabgroupgridc1" style="height:925px; width:925px; padding:1px;" align="center">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td colspan="2"><div id="tabgroupgridc1_1" align="center" ></div></td>
                                            </tr>
                                            <tr>
                                              <td><div id="tabgroupgridc1link" align="left" > </div></td>
                                            </tr>
                                          </table>
                                          <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                        </div></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2"></td>
                                    </tr>
                                  </table>
                                  
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:5px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td width="67%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                          <td width="33%" align="right" valign="middle">&nbsp;
                                            <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Text" onclick="formsubmit('toexcel');"/>
                                            &nbsp;
                                            <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetfunc();" /></td>
                                        </tr>
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
<? } ?>