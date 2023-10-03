<?php
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand()); ?>" rel=stylesheet>
<link href="../style/shortkey.css?dummy=<?php echo (rand()); ?>" rel=stylesheet>
<script language="javascript" src="../functions/key_shortcut.js?dummy=<?php echo (rand()); ?>"></script>
<script language="javascript" src="../functions/dashboard-shortcut.js?dummy=<?php echo (rand()); ?>"></script>
<table width="952" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap" >
        <tr>
          <td valign="top">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="190" valign="top">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                          <td height="190" valign="top">
                            <table width="100%" border="0" cellspacing="0" cellpadding="8">
                              
                              <tr>
                                <td valign="top">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr >
                                      <td width="160" valign="top">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                      <tr><td>&nbsp;</td></tr>
                                          <tr>
                                            <td class="links-top">&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td class="links-mid" align="left"><?php include('../inc/quicklinks.php'); ?></td>
                                          </tr>
                                          <tr>
                                            <td class="links-btm">&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td class="links-top">&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td class="links-mid" align="left"><?php include('../inc/leftlink-websites.php'); ?></td>
                                          </tr>
                                          <tr>
                                            <td class="links-btm">&nbsp;</td>
                                          </tr>
                                           <tr>
                                            <td>&nbsp;</td>
                                          </tr>
                                         <?php if ($p_customerpendingrequest == 'yes' || $p_dealerpendingrequest == 'yes') { ?>

                                                            <tr>
                                                              <td class="links-top">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                              <td class="links-mid" align="left"><?php include('../inc/leftlink-profileupdate.php'); ?></td>
                                                            </tr>
                                                            <tr>
                                                              <td class="links-btm">&nbsp;</td>
                                                            </tr>
                                          <?php } ?>
                                      </table></td>
                                      <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td style="padding-left:30px; font-size:16px; font-family:Tahoma; font-weight:bold">Welcome <?php $fetch = runmysqlqueryfetch("SELECT fullname FROM inv_mas_users WHERE slno = '" . $userid . "'");
                                          echo ($fetch['fullname']); ?>...</td>
                                        </tr>
                                         <tr>
                                          <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td class="contbox-top" >&nbsp;</td>
                                            </tr>
                                            <tr >
                                              <td class="contbox-mid"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td width="5%">&nbsp;</td>
                                                  <td width="89%"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                    <tr>
                                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td class="dashboard-heading">Today's Registration</td>
                                                        </tr>
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td class="regbox-top">&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td class="regbox-mid"><table width="80%" border="0" align="center" cellpadding="4" cellspacing="0">
                                                              <tr>
                                                                <td width="129" align="left"><font color="#6C3600" style="font-size:11px; font-weight:bold">New  Registrations</font></td>
                                                                <td width="4" align="left"><font color="#6C3600" style="font-size:11px; font-weight:bold">:</font></td>
                                                                <td width="44" align="left"><div align="right">
                                                                  <?php $query0 = "SELECT COUNT(*) AS newregistration FROM inv_customerproduct WHERE `type` = 'newlicence' and date = curdate();";
                                                                  $fetch0 = runmysqlqueryfetch($query0);
                                                                  echo ($fetch0['newregistration']);
                                                                  ?>
                                                                </div></td>
                                                                <td width="57">&nbsp;</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><font color="#6C3600" style="font-size:11px; font-weight:bold" >Re-  Registrations</font></td>
                                                                <td align="left"> <font color="#6C3600" style="font-size:11px; font-weight:bold">:</font></td>
                                                                <td align="left"><div align="right">
                                                                  <?php $query1 = "SELECT COUNT(*) AS reregistration FROM inv_customerproduct WHERE `type` = 'reregistration' and date = curdate();";
                                                                  $fetch1 = runmysqlqueryfetch($query1);
                                                                  echo ($fetch1['reregistration']); ?>
                                                                </div></td>
                                                                <td>&nbsp;</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><font color="#6C3600" style="font-size:11px; font-weight:bold">Updation</font></td>
                                                                <td align="left"><font color="#6C3600" style="font-size:11px; font-weight:bold">:</font></td>
                                                                <td align="left"><div align="right">
                                                                  <?php $query2 = "SELECT COUNT(*) AS updation FROM inv_customerproduct WHERE `type` = 'updationlicense' and date = curdate();";
                                                                  $fetch2 = runmysqlqueryfetch($query2);
                                                                  echo ($fetch2['updation']); ?>
                                                                </div></td>
                                                                <td>&nbsp;</td>
                                                              </tr>
                                                              <tr>
                                                                <td align="left"><font color="#6C3600" style="font-size:11px; font-weight:bold">Total</font></td>
                                                                <td align="left"><font color="#6C3600" style="font-size:11px; font-weight:bold">:</font></td>
                                                                <td align="left"><div align="right">
                                                                  <?php echo ($fetch0['newregistration'] + $fetch1['reregistration'] + $fetch2['updation']); ?>
                                                                </div></td>
                                                                <td>&nbsp;</td>
                                                              </tr>
                                                          </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td  class="regbox-btm">&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                      <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td class="dashboard-heading"><strong>Other Applications</strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td class="regbox-top">&nbsp;</td>
                                                        </tr>
                                                        <tr >
                                                          <td class="regbox-mid"><table width="80%" border="0" align="center" cellpadding="4" cellspacing="0">
                                                              <tr >
                                                                <td valign="top" class="otherapp-font" align="left">
                                                                <img src="../images/aball1p.gif" /><a href="http://dealers.relyonsoft.com/"> Lead Management system</a><br/></td>
                                                              </tr>
                                                              
                                                              <tr>
                                                                <td>&nbsp;</td>
                                                              </tr>
                                                              
                                                          </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td  class="regbox-btm">&nbsp;</td>
                                                        </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                      <td valign="top">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                          <tr>
                                                            <td class="dashboard-heading">
                                                              <strong>Region wise All customers:</strong></td>
                                                          </tr>
                                                          <tr>
                                                            <td>&nbsp;</td>
                                                          </tr>
                                                          <tr>
                                                            <td>
                                                              <div id="RegionstatsDiv" > Chart is Loading... </div>
                                                        <script type="text/javascript">	
    //Instantiate the Chart	
    var chart_Regionstats = new FusionCharts("../FusionCharts/FCF_Column3D.swf", "Regionstats", "300", "250", "0", "0");
      chart_Regionstats.setTransparent("false");
    
    //Set the dataURL of the chart
    chart_Regionstats.setDataURL("./regiondata.php")
    //Finally, render the chart.
    chart_Regionstats.render("RegionstatsDiv");
                                                </script>
                                                </td>
                                                          </tr>

                                                      </table></td>
                                                      <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                        <tr>
                                                          <td class="dashboard-heading"><strong>Region wise Active customers:</strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td>&nbsp;</td>
                                                        </tr>
                                                        <tr>
                                                          <td><div id="activeRegionstatsDiv" > Chart is Loading... </div>
                                                              <script type="text/javascript">	
    //Instantiate the Chart	
    var chart_Regionstats = new FusionCharts("../FusionCharts/FCF_Column3D.swf", "Regionstats", "300", "250", "0", "0");
      chart_Regionstats.setTransparent("false");
    
    //Set the dataURL of the chart
    chart_Regionstats.setDataURL("./activecusregionwise.php")
    //Finally, render the chart.
    chart_Regionstats.render("activeRegionstatsDiv");
                                                      </script></td>
                                                        </tr>
                                                      </table></td>
                                                    </tr>
                                                    <tr>
                                                      <td colspan="2">&nbsp;</td>
                                                    </tr>
                                                  </table></td>
                                                  <td width="6%">&nbsp;</td>
                                                </tr>
                                              </table></td>
                                            </tr>
                                            <tr >
                                              <td class="contbox-mid">&nbsp;</td>
                                            </tr>
                                            <tr>
                                              <td class="contbox-btm">&nbsp;</td>
                                            </tr>
                                            <tr><td><form action="" method="post" name="detailform" id="detailform"><div style="display:none"><table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div class="wa" id="shortcut-grid">
  <div  class="wc">
    <table cellpadding="0" class="cf wf">
      <tbody>
        <tr>
          <td class="wk Dp">Keyboard shortcuts</td>
          <td class="wj Dp"></td>
</tr>
        <tr>
          <td class="Dn"><table cellpadding="0" class="cf">
              <tbody>
                <tr>
                  <th class="Do"></th>
                  <th class="Do">Navigation</th>
                </tr>
                <tr>
                  <td class="wg Dn"><span class="wh">Alt + Shift + C</span> :</td>
                  <td class="we Dn">Customer Master Page</td>
                </tr>
                <tr>
                  <td class="wg Dn"><span class="wh">Alt + Shift + D</span>:</td>
                  <td class="we Dn">Dealer Master Page</td>
                </tr>
                <tr>
                  <td class="wg Dn"><span class="wh">Alt + Shift + I</span>:</td>
                  <td class="we Dn">Invoicing Page</td>
                </tr>
                <tr><td colspan="2">&nbsp;</td></tr>
                  <tr>
                  <td  colspan="2"><span class="wk Dp" style="font-weight:bold" >TIP:</span> <span class="we Dn">To view the list in a Drop-Down, press F4 button from the keyboard, while keeping the focus on that field.</span></td>
                </tr>
              </tbody>
          </table></td>
          <td class="Dn">&nbsp;</td>
        </tr>
      </tbody>
    </table>
  </div>
    </div>
</td>
  </tr>
</table>
 </div></form></td></tr>
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
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
