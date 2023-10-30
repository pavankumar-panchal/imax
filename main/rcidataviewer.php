<?php
if($p_viewrcidata <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/getdistrictfunction.php?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/rcidataviewer.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<?php $userid = imaxgetcookie('userid');?>
<table width="952" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
  <tr>
    <td valign="top" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">RCI Data Viewer</td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0"  align="center">
                          <tr>
                            <td width="100%"><div >
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc;">
                                <tr><td   class="header-line">Filter:</td></tr>
                                  <tr>
                                    <td valign="top"><div id="filterdiv">
                                      <form action="" method="post" name="searchfilterform" id="searchfilterform" onsubmit="return false;">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">

                                          <tr>
                                            <td width="100%" valign="top" ><table width="100%" border="0" cellpadding="0" cellspacing="0" bordercolor="#F9F8E6" bgcolor="#FCF7E0">
                                              <tr>
                                                <td width="57%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2" style=" border-right:1px solid #CCCCCC">
                                                    <tr>
                                                      <td colspan="4" align="left" valign="middle"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td width="9%" align="left" valign="middle" >Text: </td>
                                                            <td width="91%" colspan="3" align="left" valign="top" ><input name="searchcriteria" type="text" id="searchcriteria" size="35" maxlength="60" class="swifttext"  autocomplete="off" value=""/>
                                                                <span style="font-size:9px; color:#999999; padding:1px">(Leave Empty for all)</span></td>
                                                            <td>&nbsp;</td>
                                                          </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr valign="top" >
                                                      <td height="2" colspan="2" ><input type="hidden" name="flag" id="flag" value="true" /></td>
                                                    </tr>
                                                     <tr valign="top" >
                                                      <td width="34%" height="2" ><table width="100%" border="0" cellspacing="0" cellpadding="6" style="border:1px solid #CCCCCC">
                                                        <tr>
                                                          <td><strong>Look In</strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td style="text-align:left"><label>
                                                          <input type="radio" name="databasefield" id="databasefield0" value="customerid"/>
Customer ID</label></td>
                                                        </tr>
                                                        <tr>
                                                          <td style="text-align:left"><label>
                                                          <input type="radio" name="databasefield" id="databasefield1" value="businessname" checked="checked"/>
Company Name</label></td>
                                                        </tr>
                                                        <tr>
                                                          <td style="text-align:left"><label>
                                                          <input type="radio" name="databasefield" id="databasefield2" value="registeredname" checked="checked"/>
Registration Name</label></td>
                                                        </tr>
                                                        <tr>
                                                          <td style="text-align:left"><span style="padding:1px">
                                                            <label>
                                                            <input type="radio" name="databasefield" value="pinnumber" id="databasefield3" />
Registration PIN  </label>
                                                          </span></td>
                                                        </tr>
                                                        <tr>
                                                          <td style="text-align:left"><span style="padding:1px">
                                                            <label>
                                                            <input type="radio" name="databasefield" value="computerid" id="databasefield4" />
Computer ID</label>
                                                          </span></td>
                                                        </tr>
                                                      </table></td>
                                                      <td width="66%" ><table width="100%" border="0" cellspacing="0" cellpadding="4" style="border-left: 1px solid #CCCCCC;border-top: 1px solid #CCCCCC;border-bottom: 1px solid #CCCCCC" >
                                                        <tr>
                                                          <td colspan="2"><strong>Selections</strong>:</td>
                                                        </tr>
                                                        <tr>
                                                          <td height="10" align="left" valign="top">Operting System:</td>
                                                          <td height="10" align="left" valign="top"><select name="os" class="swiftselect" id="os" style="width:180px;">
                                                              <option value="">ALL</option>
                                                              <?php 
											include('../inc/operatingsystem.php');
											?>
                                                            </select>
                                                          </td>
                                                        </tr>
                                                        <tr>
                                                          <td height="10" align="left" valign="top">Processor:</td>
                                                          <td height="10" align="left" valign="top"><select name="processor" class="swiftselect" id="processor" style="width:180px;">
                                                              <option value="">ALL</option>
                                                              <?php 
											include('../inc/processor.php');
											?>
                                                            </select>
                                                          </td>
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
                                                          <td align="left" valign="top" height="10" >State:</td>
                                                          <td align="left" valign="top" height="10"><select name="state" class="swiftselect" id="state" onchange="getdistrictfilter('districtcodedisplaysearch',this.value);" onkeyup="getdistrictfilter('districtcodedisplaysearch',this.value);" style="width:180px;">
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
                                                          <td height="10" align="left"> Branch:</td>
                                                          <td align="left" valign="top"   height="10" ><select name="branch" class="swiftselect" id="branch" style="width:180px;">
                                                              <option value="">ALL</option>
                                                              <?php include('../inc/branch.php');?>
                                                          </select></td>
                                                        </tr>
                                                        <tr>
                                                          <td height="10" align="left"> Type:</td>
                                                          <td align="left" valign="top"   height="10" ><select name="type" class="swiftselect" id="type" style="width:180px;">
                                                              <option value="">ALL</option>
                                                              <option value="Not Selected">Not Selected</option>
                                                              <?php include('../inc/custype.php');?>
                                                          </select></td>
                                                        </tr>
                                                        <tr>
                                                          <td height="10" align="left"> Category:</td>
                                                          <td align="left" valign="top"   height="10" ><select name="category" class="swiftselect" id="category" style="width:180px;">
                                                              <option value="">ALL</option>
                                                              <option value="Not Selected">Not Selected</option>
                                                              <?php include('../inc/category.php');?>
                                                          </select></td>
                                                        </tr>
                                                      </table></td>
                                                     </tr>
                                                    
                                                    <tr>
                                                      <td colspan="2" align="left" style="padding:3px"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td valign="top">&nbsp;</td>
                                                          </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr>
                                                      <td height="40" colspan="2" align="left" valign="middle" ><div id="filter-form-error"></div></td>
                                                    </tr>
                                                </table></td>
                                                <td width="43%" valign="top" style="padding-left:3px"><table width="99%" border="0" cellspacing="0" cellpadding="4">
                                                    <tr>
                                                      <td colspan="4" valign="top" style="padding:0"></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="4" valign="top" align="left"><strong>Products: </strong></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="4" valign="top" bgcolor="#FFFFFF" style="border:solid 1px #A8A8A8" align="left"><div style="height:200px; overflow:scroll">
                                                          <?php include('../inc/rciproductdetails.php'); ?>
                                                      </div></td>
                                                    </tr>
                                                    <tr>
                                                      <td width="14%" align="left">Select: </td>
                                                      <td width="34%" align="left"><strong>
                                                        <select name="selectproduct" class="swiftselect" id="selectproduct" style="width:120px" >
                                                          <option value="ALL"  selected="selected">ALL</option>
                                                          <option value="NONE">NONE</option>
                                                          <?php include('../inc/productgroup.php') ?>
                                                        </select>
                                                      </strong></td>
                                                      <td width="52%" align="left"><a onclick="selectdeselectall('one');"><strong class="resendtext">Go &#8250;&#8250;</strong></a>&nbsp;<strong>OR</strong>&nbsp;<a onclick="selectdeselectall('more');"> <span class="reg-text">Add to selection &#8250;&#8250;</span></a></td>
                                                    </tr>
                                                    <tr >
                                                      <td  colspan="3" ><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td width="21%">&nbsp;</td>
                                                            <td width="79%"></td>
                                                            <input type="hidden" name="groupvalue" id="groupvalue"  />
                                                          </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr>
                                                      <td height="20" colspan="3" ><div align="right">
                                                          <input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value="Search" onclick="filterrcidata('');" />
                                                        &nbsp;
                                                        <input type="button" name="reset_form" value="Reset" class="swiftchoicebutton" onclick="resetDefaultValues(this.form);" />  &nbsp;
                                                        <input type="button" name="toexcel" value="To Excel" class="swiftchoicebutton" onclick="filtertoexcel('toexcel');" id="toexcel" />
                                                        &nbsp;</div></td>
                                                    </tr>
                                                </table></td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                          <tr>
                                            <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"></td>
                                          </tr>
                                        </table>
                                      </form>
                                    </div></td>
                                  </tr>
                                </table>
                              </div></td>
                          </tr>
                          <tr>
                            <td>&nbsp;</td>
                          </tr>
                          <tr>
                            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td width="140px" align="center" id="tabgroupgridh1" onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default'); getrcidetails(''); " style="cursor:pointer" class="grid-active-tabclass">Default</td>
                                  <td width="2">&nbsp;</td>
                                  <td width="140px" align="center" id="tabgroupgridh2" onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');document.getElementById('tabgroupgridwb1').innerHTML = '';" style="cursor:pointer" class="grid-tabclass">Search Result</td>
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
                                  <td width="216px" style="text-align:center;"><span id="tabgroupgridwb1" ></span><span id="tabgroupgridwb2" ></span></td>
                                  <td width="296px" style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="3" align="center" valign="top"><div id="tabgroupgridc1" style="overflow:auto; height:265px; width:935px; padding:0px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div id="tabgroupgridc1_1" align="center" ></div></td>
  </tr>
  <tr>
    <td><div id="tabgroupgridc1link" align="left" >
</div></td>
  </tr>
</table><div id="resultgrid" style="overflow:auto; display:none; height:200px; width:935px; padding:0px;" align="center">&nbsp;</div></div>
                                    <div id="tabgroupgridc2" style="overflow:auto;height:265px; width:935px; padding:0px; display:none;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
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