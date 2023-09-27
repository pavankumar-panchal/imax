<?php
if($p_blockcancel <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include('../inc/eventloginsert.php');
$enabledid = array('146');
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
<link media="screen" rel="stylesheet" href="../style/jquery.dataTables.min.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/jquery-3.3.1.min.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/jquery.dataTables.min.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/blockcancel.js?dummy=<?php echo (rand());?>"></script>
<style rel=stylesheet>
.progress { position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px; }
.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }
</style>

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
                            <td width="27%" class="active-leftnav">Block or Cancel PIN Numbers</td>
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
                                <form action="" method="post" name="cardsearchfilterform" id="cardsearchfilterform"  onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="100%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="1" cellpadding="3">
                                          <tr>
                                            <td valign="top" style=""><!--<table width="99%" border="0" cellspacing="0" cellpadding="3">
                     
                                              <tr>
                                                <td align="center">
                            
                                                <input name="cardsearchtext" type="text" class="swifttext" id="cardsearchtext" onkeyup="cardsearch(event);"  autocomplete="off" style="width:204px"/>
                                                  <span style="display:none1">
                                                  <input name="cardlastslno" type="hidden" id="cardlastslno"  disabled="disabled"/>
                                                  </span>
                                                  <select name="cardlist" size="5" class="swiftselect" id="cardlist" style="width:210px; height:200px" onclick ="selectcardfromlist();" onchange="selectcardfromlist();"  >
                                                  </select>                 </td>
                                              </tr>
                                            </table>-->
                                              
                                              <table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                <tr >
                                                  <td width="53%" height="34px;" colspan="2" bgcolor="#edf4ff"><strong>Enter PIN/Card</strong></td>
                                                </tr>
                                                <tr bgcolor="#edf4ff">
                                                  <td width="20%">Pin Number:</td>
                                                  <td ><input name="pinno" type="text" id="pinno" 
                                            size="25" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                                </tr>
                                                <tr  bgcolor="#edf4ff">
                                                  <td width="5%"  align="left" valign="top" >CardId: </td>
                                                  <td width="55%" align="left" valign="top"><input name="cardid" type="text" id="cardid" size="25" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2"></td>
                                                </tr>
                                                <tr>
                                                  <td colspan="2"><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value=
                                            "Filter" onclick="carddetailstoform(); griddata();" /></td>
                                                </tr>
                                              </table></td>
                                            <!-- <td width="14%" height="34" valign="top" id="cardselectionprocess" style="padding:0">&nbsp;</td> -->
                                            <td width="61%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border:solid 1px #BFDFFF">
                                                <tr  bgcolor="#EDF4FF">
                                                  <td width="27%"  bgcolor="#EDF4FF" align="left"> PIN Serial Number:</td>
                                                  <td width="73%"  bgcolor="#EDF4FF" align="left"><input name="scratchnumber" type="text" class="swifttext" id="scratchnumber" size="30" maxlength="6"  autocomplete="off" readonly="readonly"/></td>
                                                </tr>
                                                    <tr>
                                                        <td width="27%"  bgcolor="#EDF4FF" align="left"> PIN Remarks Status:</td>
                                                        <td width="73%"  bgcolor="#EDF4FF" align="left">
                                                            <select name="pinremarksstatus" id="pinremarksstatus" class="swiftselect">
                                                                <option value=" ">Select PIN Remarks Status</option>
                                                            <?php include('../inc/pinremarks.php'); ?>
                                                            </select>
                                                        </td>
                                                    </tr>
                                                <tr bgcolor="#f7faff">
                                                  <td bgcolor="#f7faff" align="left">Remarks:</td>
                                                  <td bgcolor="#f7faff" align="left"><textarea name="remarks" cols="27" class="swifttextarea" id="remarks"></textarea></td>
                                                </tr>
                                                <tr>
                                                  <td  bgcolor="#EDF4FF" align="left">Attached:</td>
                                                  <td id="cardattached"  bgcolor="#EDF4FF" align="left">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#f7faff" align="left">Registered:</td>
                                                  <td id="cardregistered" bgcolor="#f7faff" align="left">&nbsp;</td>
                                                </tr>
                                                <tr  bgcolor="#EDF4FF">
                                                  <td  bgcolor="#EDF4FF" align="left">Action:</td>
                                                  <td  bgcolor="#EDF4FF" align="left"><label for="actiontype0">
                                                      <input name="actiontype" type="radio" id="actiontype0" value="none" checked="checked" />
                                                      Active </label>
                                                    <label for="actiontype1">
                                                      <input type="radio" name="actiontype" id="actiontype1" value="block" />
                                                      Block </label>
                                                    <label for="actiontype2">
                                                      <input type="radio" name="actiontype" id="actiontype2" value="cancel" />
                                                      Cancel</label></td>
                                                </tr>
                                                <tr>
                                                  <td bgcolor="#f7faff" align="left" >PIN Status:</td>
                                                  <td id="cardstatus" bgcolor="#f7faff" align="left">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td >&nbsp;</td>
                                                  <td >&nbsp;</td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="40%" height="35" align="left" valign="middle"><div id="form-error"></div></td>
                                            <td width="60%" height="35" align="right" valign="middle"><div align="center">
                                                <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onclick="formsubmit('save');" />
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
                                  <td width="15%" class="header-line" style="padding:0"><div id="tabdescription">Results</div></td>
                                </tr>
                                <tr>
                                  <td style="padding:0"><div id="displaysearchresult" style="width:928px; padding:2px;" 
                                            align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td><div id="tabgroupgridc3_1" align="center"></div></td>
                                        </tr>
                                        
                                      </table>
                                    </div>
                                    </td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr>
                      <?phpphp if(in_array($userid, $enabledid, true) ) { ?>
                    <tr>
											<td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
													<tr>
														<td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Upload Only CSV Files</td>
														<td align="right" class="header-line" style="padding-right:7px"></td>
													</tr>
													<tr>
														<td colspan="2" valign="top"><div id="maindiv">
																<form  method="post" name="submitform" id="submitform" enctype="multipart/form-data">
																	<table width="100%" border="0" cellspacing="0" cellpadding="2">
																		<tr>
																			<td width="100%" valign="top" style="border-right:1px solid #d1dceb;">&nbsp;</td>
																		</tr>
																		<tr>
																			<td valign="top" style="border-right:1px solid #d1dceb;">
																				<table width="40%" border="0" cellspacing="0" cellpadding="3">
																					<tr bgcolor="#edf4ff">
																						<td width="10%" align="left" valign="top">File Upload: </td>
																						<td width="15%" align="left" valign="top"><input name="uploadfile" type="file" id="uploadfile" 
																						size="30" maxlength="25" required  /></td>
																						
																					</tr>
																					<tr bgcolor="#f7faff">
																						<td colspan="2" align="left" valign="top" style="padding:0">
																							<table width="100%" border="0" cellspacing="0" cellpadding="0">
																								<tr>
																									<td ><table width="100%" border="0" cellspacing="0" cellpadding="3">
																										<input name="filter" type="submit" class="swiftchoicebutton-red" id="filter" value="Upload" onclick="uploadpins();"/>
																										</table></td>
																								</tr>
																							</table></td>
																					</tr>
																				</table></td>
																		</tr>
																			 </table></td>
																		</tr>
																	</table>
																</form>
																<br>
														 <br>

                                <div class="progress">
                                <div class="bar"></div >
                                <div class="percent">0%</div >
                            </div>

                            <div id="status"></div> 

														</td>
														 </tr>
							 
																</table></td>
                                </table></td>
                            </tr>
                          </table></td>
                      </tr>
                      <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                                <tr class="header-line">
                                  <td width="131" align="left"  style="padding:0"><div id="tabdescription">&nbsp; Pin Details</div></td>
                                  <td width="427"  style="padding:0; text-align:center;">
                                  <span id="pinscountid"></span>
                                  </td>
                                  <td width="35" align="left"  style="padding:0">&nbsp;</td>
                                  <td width="139" align="left"  style="padding:0">&nbsp;</td>
                                </tr>
                                <tr>
                                  <td colspan="4" align="center" valign="top">
                                  <div id="tabgroupgridc1" style="overflow:auto; height:200px; padding:2px;" align="center">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td>
                                          <table width="100%" cellpadding="3" cellspacing="0">
                                          <tr>
                                          <td><div id="tabgroupgridc1_1" align="center"></div></td>
                                        </tr>
                                        <tr>
                                          <td><div id="tabgroupgridc1link"  align="left" style="height:20px; padding:2px;"> </div></td>
                                        </tr>
                                          </table></td>
                                        </tr>
                                      </table>

                                    </div>
                                    <div id="resultgrid" style="overflow:auto; display:none; height:150px; width:704px; padding:2px;" align="center">&nbsp;</div></td>
                                </tr>
                              </table></td>
                          </tr>
                        </table></td>
                    </tr> <?php } ?>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<!--<script>refreshcardarray();</script>-->
<?php } ?>