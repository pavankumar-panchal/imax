<?
if($p_activitylog <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
	
?>
<script language="javascript" src="../functions/activitylog.js?dummy=<? echo (rand());?>"></script>
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
                            <td width="27%" class="active-leftnav">Report - Activity Log</td>
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
                                            
                                            <td width="37%"  valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3" >
                                                <tr >
                                                  <td width="22%" align="left" valign="top">From Date: </td>
                                                  <td width="78%" align="left" valign="top"><input name="fromdate" type="text" class="swifttext-mandatory" id="DPC_fromdate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>" readonly="readonly" />
                                                    <input type="hidden" name="flag" id="flag" value="true" />
                                                    <input type="hidden" name="category" id="category" value="<? echo($pagelink) ?>" /></td>
                                                </tr>
                                              </table></td>
                                            <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                <tr >
                                                  <td colspan="2" valign="top" style="padding:0"></td>
                                                </tr>
                                                <tr >
                                                  <td width="22%" align="left" valign="top" >To Date:</td>
                                                  <td width="78%" align="left" valign="top" ><label for="sto"></label>
                                                    <label for="spp">
                                                      <input name="todate" type="text" class="swifttext-mandatory" id="DPC_todate" size="30" autocomplete="off" value="<? echo(datetimelocal('d-m-Y')); ?>"  readonly="readonly"/>
                                                    </label></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                          <tr>
                                            <td colspan="3"><div align="left" style="display:block;height:20px; padding-top:5px; " id="detailsdiv" >
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
                                            <td colspan="3"><div id="filterdiv" style="display:none; text-align: left;">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                  <tr>
                                                    <td width="100%" valign="top" ><table width="100%" border="0" cellpadding="3" cellspacing="0" >
                                                        <tr>
                                                          <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                              <tr>
                                                                <td width="43%"><table width="99%" border="0" cellspacing="0" cellpadding="0">
                                                                    <tr>
                                                                      <td width="9%" align="left" valign="top" >Text: </td>
                                                                      <td width="91%" colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="33" maxlength="150" class="swifttext"  autocomplete="off" value=""/>
                                                                        <span style="font-size:8px; color:#939393;">(Leave Empty for all)</span></td>
                                                                      <td>&nbsp;</td>
                                                                    </tr>
                                                                  </table></td>
                                                                <td width="12%">&nbsp;</td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                                            
                                                            
                                                            <tr valign="top" >
                                                              <td width="17%" height="2" ><table width="100%" border="0" cellspacing="0" cellpadding="6" style="border:solid 1px #000" >
                                                                <tr>
                                                                  <td align="left"><strong>Look In</strong></td>
                                                                </tr>
                                                                <tr>
                                                                  <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield0" value="userid"/>
                                                                    User ID</label></td>
                                                                </tr>
                                                                
                                                                <tr>
                                                                  <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield1" value="modulename" checked="checked" />
                                                                    Module Name</label></td>
                                                                </tr>
                                                                <tr>
                                                                  <td align="left"><label>
                                                                    <input type="radio" name="databasefield" id="databasefield2" value="systemip" />
                                                                    System IP </label></td>
                                                                </tr>
                                                              </table></td>
                                                              <td width="83%" ><table width="100%" border="0" cellspacing="0" cellpadding="4"  style="border:solid 1px #CCC" >
                                                                <tr>
                                                                  <td colspan="2" align="left"><strong>Selections</strong>:</td>
                                                                </tr>
                                                                <tr>
                                                                  <td height="10" align="left" valign="top">Module Name:</td>
                                                                  <td height="10" align="left" valign="top"><select name="modulename" class="swiftselect" id="modulename" style="width:180px;">
                                                                    <option value="" selected="selected">ALL</option>
                                                                    <option value="user">User Module</option>
                                                                    <option value="dealer">Dealer Module</option>
                                                                    <option value="implementation">Implementation</option>
                                                                 
                                                                  </select></td>
                                                                </tr>
                                                                <tr>
                                                                  <td height="10" align="left" valign="top">Username:</td>
                                                                  <td height="10" align="left" valign="top"><select name="username" class="swiftselect" id="username" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                  <? include('../inc/username.php');?>
                                                                  </select></td>
                                                                </tr>
                                                                <tr>
                                                                  <td width="21%" height="10" align="left" valign="top">Event Type</td>
                                                                  <td width="79%" height="10" align="left" valign="top"><select name="eventtype" class="swiftselect" id="eventtype" style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? 
											include('../inc/eventtype.php');
											?>
                                                                  </select></td>
                                                                </tr>
                                                                <tr>
                                                                  <td align="left" valign="top" height="10" >Pages Short Name:</td>
                                                                  <td align="left" valign="top" height="10"><select name="pageshortname" class="swiftselect" id="pageshortname"  style="width:180px;">
                                                                    <option value="">ALL</option>
                                                                    <? include('../inc/pageshortname.php'); ?>
                                                                  </select></td>
                                                                </tr>
                                                              </table></td>
                                                            </tr>
                                                           
                                                            
                                                          </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td width="60%" align="right" valign="middle" style="padding-right:3px;"></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table>
                                            </div></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td colspan="2" align="right" valign="middle" style="padding-right:5px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="67%" align="left" valign="middle" height="35"><div id="form-error"></div></td>
                                            <td width="33%" align="right" valign="middle"><input name="view" type="button" class="swiftchoicebutton" id="view" value="View" onclick="searchfilter('');" />
                                              &nbsp;
                                              <input name="toexcel" type="submit" class="swiftchoicebutton" id="toexcel" value="To Excel" onclick="filtertoexcel('toexcel');"/>
                                              &nbsp;
                                              <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);" /></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                          <tr>
                            <td colspan="2">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td colspan="2" align="right" valign="middle" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                              <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Activity');gettodaysresult('') " style="cursor:pointer" class="grid-active-tabclass">Today's Activity</td>
                                                <td width="2">&nbsp;</td>
                                                <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');" style="cursor:pointer" class="grid-tabclass">Search Result</td>
                                                <td><div id="gridprocessing"> </div></td>
                                              </tr>
                                            </table></td>
                                        </tr>
                                        <tr>
                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                              <tr class="header-line" >
                                                <td width="220px"><div id="tabdescription">&nbsp;</div></td>
                                                <td width="216px" style="text-align:center;"><span id="tabgroupgridwb1" ></span></td>
                                                <td width="296px" style="padding:0">&nbsp;</td>
                                              </tr>
                                              <tr>
                                                <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:270px; width:925px; padding:2px;" align="center">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td><div id="tabgroupgridc1_1" align="center" ></div></td>
                                                      </tr>
                                                      <tr>
                                                        <td><div id="tabgroupgridc1link" align="left" > </div></td>
                                                      </tr>
                                                    </table>
                                                    <div id="searchresultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div>
                                                  </div>
                                                  <div id="tabgroupgridc2" style="overflow:auto;height:270px; width:925px; padding:2px; display:none;" align="center">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                      <tr>
                                                        <td colspan="2"><div id="tabgroupgridc1_2" ></div></td>
                                                      </tr>
                                                      <tr>
                                                        <td><div id="tabgroupgridc2link" align="left"> </div></td>
                                                        <td>&nbsp;</td>
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
                                 
                                </table>
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
</table>
<? } ?>