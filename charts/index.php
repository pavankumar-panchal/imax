<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/charts.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/highcharts.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/excanvas.compiled.js?dummy=<?php echo (rand());?>"></script>
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
                            <td width="27%" class="active-leftnav">Charts - Graphical Representation</td>
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
                            <td class="header-line" style="padding:0">&nbsp;</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onSubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                   
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td bgcolor="#f7faff"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr bgcolor="#EDF4FF">
                                                <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                    <tr>
                                                      <td width="30%">Product Group:</td>
                                                      <td width="70%"><select name="groupwise" class="swiftselect-mandatory" id="groupwise" style=" width:225px">
                                                          <option value="">ALL</option>
                                                          <option value="contact">CONTACT</option>
                                                          <option value="sto">STO</option>
                                                          <option value="svh">SVH</option>
                                                          <option value="svi">SVI</option>
                                                          <option value="tds">TDS</option>
                                                          <option value="spp">SPP</option>
                                                          <option value="others">OTHERS</option>
                                                        </select>
                                                      </td>
                                                    </tr>
                                                    <tr>
                                                      <td>Dealer:</td>
                                                      <td><select name="dealerwise" class="swiftselect-mandatory" id="dealerwise" style=" width:225px">
                                                          <option value="">ALL</option>
                                                          <?php include('../inc/firstdealer.php'); ?>
                                                        </select></td>
                                                    </tr>
                                                    <tr>
                                                      <td>State:</td>
                                                      <td><select name="statewise" class="swiftselect-mandatory" id="statewise" style=" width:225px">
                                                          <option value="">ALL</option>
                                                          <?php include('../inc/state.php'); ?>
                                                      </select></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                      <td colspan="2"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                          
                                                      </table></td>
                                                    </tr>
                                                </table></td>
                                                <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                  <tr>
                                                      <td>Branch:</td>
                                                      <td><select name="branchwise" class="swiftselect-mandatory" id="branchwise" style=" width:225px">
                                                          <option value="">ALL</option>
                                                          <?php include('../inc/branch.php'); ?>
                                                      </select></td>
                                                    </tr>
                                                    <tr>
                                                      <td>Type:</td>
                                                      <td><select name="typewise" class="swiftselect-mandatory" id="typewise" style=" width:225px">
                                                          <option value="">ALL</option>
                                                          <?php include('../inc/custype.php'); ?>
                                                      </select></td>
                                                    </tr>
                                                    <tr>
                                                      <td>Category:</td>
                                                      <td><select name="categorywise" class="swiftselect-mandatory" id="categorywise" style=" width:225px">
                                                          <option value="">ALL</option>
                                                          <?php include('../inc/category.php'); ?>
                                                      </select></td>
                                                    </tr>
                                                    <tr><td colspan="2"><fieldset style="border:1px solid #666666; padding:3px;">
                                             <legend>Split  By</legend>
                                             <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                               <tr>
                                                 <td align="left">
                                                 <label for="splityear">
                                                       <input type="checkbox" name="splityear" id="splityear" value="splityear" checked="checked"/>
Year Wise</label>
                                                 </td>
                                               </tr>
                                                          </table>
                                             </fieldset></td></tr>
                                                </table></td>
                                              </tr>
                                            </table></td>
                                          </tr>
                                          <tr><td><fieldset style="border:1px solid #666666; padding:3px;">
                                              <legend><strong>Summarize:</strong> </legend>
                                              <table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                <tr>
                                                  <td width="4%"></td>
                                                  <td width="13%"><label for="databasefield0">
                                                    <input type="radio" name="charts" id="databasefield0" value="dealer"/>
                                                  Dealer wise</label></td>
                                                  <td width="13%"><label for="databasefield1">
                                                    <input type="radio" name="charts" id="databasefield1" value="branch" checked="checked"/>
                                                  Branch wise</label></td>
                                                  <td width="13%"><label for="databasefield2">
                                                    <input type="radio" name="charts" id="databasefield2" value="state"/>
                                                  State wise</label></td>
                                                  <td width="12%"><label for="databasefield3">
                                                    <input type="radio" name="charts" value="customertype" id="databasefield3" />
                                                  Type wise</label></td>
                                                  <td width="45%"><label for="databasefield4">
                                                    <input type="radio" name="charts" value="customercategory" id="databasefield4" />
                                                  Category wise</label></td> 
                                                  <input type="hidden" name="year04" id="year04" />
                                                  <input type="hidden" name="year05" id="year05" />
                                                  <input type="hidden" name="year06" id="year06" />
                                                  <input type="hidden" name="year07" id="year07" />
                                                   <input type="hidden" name="year08" id="year08" />
                                                    <input type="hidden" name="year09" id="year09" />
                                                     <input type="hidden" name="year10" id="year10" />
                                                      <input type="hidden" name="name" id="name" />
                                                      <input type="hidden" name="allyear" id="allyear" />
                                                  <!--<td width="29%"><label for="databasefield5">
                                                    <input type="radio" name="charts" id="databasefield5" value="group" />
                                                    Group wise</label></td>-->
                                                </tr>
                                              </table>
                                              </fieldset></td></tr>
                                        </table></td>
                                    </tr>
                                    <tr><td height="5px"></td></tr>
                                  
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="57%" align="left" valign="middle" height="35" id="form-error">&nbsp;</td>
                                            <td width="43%" align="right" valign="middle">&nbsp;
                                              <input name="view" type="submit" class="swiftchoicebutton" id="view" value="View" onClick="formsubmit();"/></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td style="padding:10px" ><div id="graphdisplay" ></div></td>
                                    </tr>
                                  </table>
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
