<?php
if($p_pindetails <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<link media="screen" rel="stylesheet" href="../style/colorbox.css?dummy=<?php echo (rand());?>"  />
<script language="javascript" src="../functions/colorbox.js?dummy=<?php echo (rand());?>" ></script>
<script language="javascript" src="../functions/pindetails.js?dummy=<?php echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<?php echo (rand());?>"></script>
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
                            <td width="27%" class="active-leftnav">Search PIN Details</td>
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
                            <td align="left" class="header-line" style="padding:0">&nbsp;&nbsp;Enter / Edit / View Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr>
                                      <td width="100%" valign="top" style="border-right:1px solid #d1dceb;">&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#edf4ff">
                                            <td width="10%" align="left" valign="top">Pin Number: </td>
                                            <td width="15%" align="left" valign="top"><input name="pinno" type="text" id="pinno" 
                                            size="30" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                            <td width="5%"  align="left" valign="top">CardId: </td>
                                            <td width="55%" align="left" valign="top"><input name="cardid" type="text" id="cardid" size="30" maxlength="25" class="swifttext"  autocomplete="off" value=""/></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td colspan="2" align="left" valign="top" style="padding:0"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td ><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    </table></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="middle" style="padding-right:15px; border-top:1px solid #d1dceb;"><table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="50%" height="35" align="left" valign="middle"><div id="filter-form-error"></div></td>
                                            <td width="31%" align="right" valign="middle"><input name="filter" type="button" class="swiftchoicebutton-red" id="filter" value=
                                            "Filter" onclick="getpindetails('');rcidetails('');" />
                                              &nbsp;&nbsp;
                                              <input name="clear" type="button" class="swiftchoicebutton" id="clear" value=
                                              "Clear" onclick="clearsearchform()" /></td>
                                          </tr>
                                        </table></td>
                                    </tr>
                                    <tr>
                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td width="15%" class="header-line" style="padding:0"><div id="tabdescription">Results </div></td>
                                          </tr>
                                          <tr>
                                            <td style="padding:0"><div id="displaysearchresult" style="width:928px; padding:2px;" 
                                            align="center">
                                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td><div id="tabgroupgridc3_1" align="center"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td><div id="tabgroupgridc1linksearch"  align="left"> </div></td>
                                                  </tr>
                                                </table>
                                              </div>
                                              <div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div></td>
                                          </tr>
                                          <tr>
                                            <td><div id="displaydiv" style="display:none; float:left;">
                                                <input name="edit" type="button" class= "swiftchoicebuttonbig" id="edit" 
                                          value="Edit Data" onclick="editformdata()" />
                                                &nbsp;&nbsp; </div>
                                              <div id="displaybutton" style="display:none; float:left;" >
                                                <input name="delete" type="button" class= "swiftchoicebuttonbig" id="delete" value=
                                           "Cancel Editing" onclick="canceleditform();" />
                                                &nbsp;&nbsp;
                                                <input name="update" type="button" class= "swiftchoicebuttonbig" id="update" value= 
                                          "Update" onclick="updatedata();"  /><br /><br />
                                              </div></td>
                                                                                            </tr>
                                           
                                       </table></td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                             </tr>
               
                                         </table></td>
                                         
             
                                                   <tr>
                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td  width="15%" class="header-line" style="padding:0;"><div id="tabdescription">&nbsp;RCI Details </div></td>
                                            
                                          </tr>
                                          
                                         <tr>
                                            <td style="padding:0;border:#308ebc 1px solid;"><div id="displaysearchresult" style="width:928px; padding:2px;" align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                    <td><div id="tabgroupgridrcic3_1" align="center"></div></td>
                                                  </tr>
                                                  <tr>
                                                    <td><div id="tabgroupgridc1linkrcisearch"  align="left"></div></td>
                                                  </tr>
                                                  
                                                </table>
                                              </div>
                                              
                                              <div id="searchresultrcigrid" style="display:none;" align="center">&nbsp;</div></td>
                                              
                                          </tr>
                                         </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>
<!--popup rci-->
<div style="display:none">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                              <tr>
                                <td><div id="rcidatagrid" style='background:#fff; width:709px'>
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #CCCCCC">
                                      <tr class="header-line">
                                        <td width="45%"><span style="padding-left:4px;">RCI Details</span></td>
                                        
                                      </tr>
                                      <tr>
                                        <td colspan="3"><div style="overflow:auto;padding:0px; height:290px; width:709px;">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td align="center"><div id="rcidetailsgridc1_1" > </div></td>
                                              </tr>
                                              <tr>
                                                <td ><div id="rcidetailsgridc1link" style="height:20px;  padding:2px;" align="centre"> </div></td>
                                              </tr>
                                            </table>
                                          </div></td>
                                      </tr>
                                    </table>
                                    
                                    <div align="right" style="padding-top:15px; padding-right:25px">
                                      <input type="button" value="Close" id="closecolorboxbutton1"  onclick="$().colorbox.close();" class="swiftchoicebutton"/>
                                    </div>
                                  </div></td>
                              </tr>
                            </table>
                          </div>
<?php } ?>