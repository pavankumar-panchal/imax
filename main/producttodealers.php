<?php
if($p_producttodealers <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<?php echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/producttodealers.js?dummy=<?php echo (rand());?>"></script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;" ><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" class="active-leftnav">Product Selection</td>
              </tr>
              <tr>
                <td><form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
          <table width="100%" border="0" cellspacing="0" cellpadding="3">
            <tr>
              <td height="34" id="productselectionprocess" align="left" style="padding:0">&nbsp;</td>
            </tr>
            <tr>
              <td align="left" ><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext" onkeyup="productsearch(event);" autocomplete="off" style="width:204px" />
                  <div id="detailloaddealerlist">
                    <select name="productlist" size="5" class="swiftselect" id="productlist" style="width:210px; height:400px;" onchange="selectfromlist();" onclick="selectfromlist()">
                      <option></option>
                    </select>
                </div></td>
            </tr>
          </table>
        </form></td>
              </tr>
              <tr>
                <td><table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
 <tr>
                <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong> </td>
                <td width="55%" id="totalproductcount"></td>
                
              </tr>
</table></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
            </table></td>
        </tr>
      </table></td>
    <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td>
                      	<table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="27%" align="left" class="active-leftnav">Dealers Selection</td>
                            <td width="40%">&nbsp;</td>
                            <td width="33%" align="left">&nbsp;</td>
                          </tr>
                       </table>
                     </td>
                    </tr>
                    <tr>
                      <td></td>
                    </tr>
                    <tr>
                      <td height="5"></td>
                    </tr>
                    <tr>
                      <td>
                      		<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #308ebc; border-top:none;">
                          <tr>
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Selected Product Details</td>
                            <td align="right" class="header-line" style="padding-right:7px"></td>
                          </tr>
                          <tr>
                             <td colspan="2">&nbsp;</td>
                          </tr>
                          <tr>
                            <td colspan="2" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                    <tr></tr>
                                    <tr>
                                      <td width="50%" valign="top" style="border-right:1px solid #d1dceb;"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top">Product Name:</td>
                                            <td align="left" valign="top" bgcolor="#f7faff"><input name="productname" type="text" class="swifttext-mandatory" id="productname" size="40" maxlength="50"  autocomplete="off" />
                                              <input type="hidden" name="lastslno" id="lastslno" /></td>
                                          </tr>
                                          <tr bgcolor="#f7faff">
                                            <td align="left" valign="top" bgcolor="#EDF4FF">Product Code:</td>
                                            <td align="left" valign="top" bgcolor="#EDF4FF"><input name="productcode" type="text" class="swifttext-mandatory" id="productcode" size="40" maxlength="10"  autocomplete="off" />
                                              <br /></td>
                                          </tr>                                         
                                      </table></td>
                                      
                                    </tr>
                                    <tr>
                                    	<td colspan="2">&nbsp;</td>
                                    </tr>
                                  </table>
                                </form>
                              </div></td>
                          </tr>
                        </table>
                      </td>
                    </tr>
                    
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                    	<td>
                        	
                        	 <table width="100%" border="0" cellspacing="0" cellpadding="5">
                             <tr><td colspan="5"><strong>Filter : </strong></td></tr>
                               <tr>
                                 <td width="20%" align="left">                                 	
                                    <input name="checkvalue[]" onchange="filter(this)" type="checkbox" id="branchhead" value="branchhead"/>
                                    <label for="branchhead">Branch Head</label>
                                 </td>
                                <td width="20%" align="left">                                	
                                    <input name="checkvalue[]" onchange="filter(this)" type="checkbox" id="relyonexecutive" value="relyonexecutive"/> 
                                    <label for="relyonexecutive">Relyon Executive</label>
                                 </td>
                                <td width="20%" align="left">
                                	<input name="checkvalue[]" onchange="enable(this);filter(this);" type="checkbox" id="regionwise"  value="regionwise"/>
                                    <label for="regionwise">Region wise</label>
                                </td>
                                <td width="20%" align="left">
                                	<input name="checkvalue[]" onchange="enable(this);filter(this);" type="checkbox" id="branchwise"  value="branchwise"/>
                                    <label for="branchwise">Branch wise</label>
                                </td>
                                <td width="20%" align="left">
                                	<input name="checkvalue[]" onchange="filter(this)" type="checkbox" id="all" value="all"/>
                                   <label for="all">ALL</label>
                                </td>
                              </tr>
                              <tr> <td colspan="5">
                              	<table width="100%" border="0" cellspacing="0" cellpadding="3"><tr>
                              	 <td align="left" valign="top" bgcolor="#edf4ff">Region:</td>
                                 <td align="left" valign="top" bgcolor="#edf4ff"><label>
                                    <select name="region" class="swiftselect-mandatory" disabled="disabled" id="region" style=" width:200px">
                                      <option value="">Select A Region</option>
                                      <?php 
                                              include('../inc/region.php');
                                              ?>
                                    </select>
                                  </label></td>
                                  <td align="left" valign="top" bgcolor="#F7FAFF">Branch : </td>
                                  <td align="left" valign="top" bgcolor="#F7FAFF"><select name="branch" class="swiftselect-mandatory" disabled="disabled" id="branch" style=" width:200px">
                                    <option value="">Select A Branch</option>
                                    <?php 
                                              include('../inc/branch.php');
                                              ?>
                                  </select></td>
                                  </tr></table>
                               </td></tr>
                               <tr><td colspan="4">&nbsp;</td><td align="left">
                                      <input name="dofilter" disabled="disabled" type="button" class="swiftchoicebutton" id="dofilter" value="Filter" onClick="filterdealter();" />
                               </td></tr>
                               <tr><td colspan="5">
                               		<div id="filtererror"></div>
                               </td></tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                      <td>&nbsp;</td>
                    </tr>
                    <form id="submitform" name="submitform">
                    <tr>
                      <td>
                         <table width="100%" border="0" cellspacing="0" cellpadding="4" >
                             <tr>
                                 <td width="39%" rowspan="15" >
                                 	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    	<tr>
                                        	<td valign="top" class="swiftselectfont" style="padding-left:9px" >Add Dealers from this List</td>
                                        </tr>
                                        <tr>
                                            <td valign="top">
                                            <div id="dlist">
                                                 <select name="dealerlist" size="5" class="swiftselect" id="dealerlist" style="width:210px; height:200px;" >
                                           </select>
                                           </div>
                                           
                                <?phpphp
/*                            $query = "SELECT productcode,productname FROM inv_mas_product order by productname;";
                            $result = runmysqlquery($query);
                            $productlistoptions = '';
                            while($fetch = mysqli_fetch_array($result))
                            {
                                $productlistoptions .= '<option value="'.$fetch['productcode'].'"  ondblclick="addentry(\''.$fetch['productcode'].'\')">'.$fetch['productname'].'</option>';
                            }
                            echo($productlistoptions);*/
                            ?>
                                                                           
                                            </td>
                                        </tr>
                                   </table>
                                 </td>
                                 <td width="22%" >&nbsp;</td>
                                 <td width="39%" rowspan="15" >
                                 	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                          <td style="padding-left:25px"  class="swiftselectfont" >Selected Dealers</td>
                                        </tr>
                                        <tr>
                                           <td>
                                           		<select name="selecteddealers" size="5" class="swiftselect" id="selecteddealers" style="width:210px; height:200px" >
                                                                                </select>
                                           </td>
                                        </tr>
                                     </table>
                                 </td>
                              </tr>
                              <tr>
                                 <td>&nbsp;</td>
                             </tr>
                             <tr>
                                <td>&nbsp;</td>
                             </tr>
                              <tr>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>
                                	<div align="left">
                                    <input name="add" type="button" class= "swiftchoicebutton" id="add" value="Add &gt;&gt;" onclick="addentry(document.getElementById('dealerlist').value)" disabled="disabled" />
                                    </div>
                                </td>
                             </tr>
                             <tr>
                                <td>&nbsp;</td>
                             </tr>
                             <tr>
                                <td><div align="left">
                                    <input name="remove" disabled="disabled" type="button" class= "swiftchoicebutton" id="remove" value="&lt;&lt; Remove" onclick="deleteentry()" />
                                  </div>
                                </td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>
                                  <div align="left">
                                    <input name="removeall" disabled="disabled" type="button" class= "swiftchoicebutton" id="removeall" value="Remove All" onclick="deleteallentry()" />
                                  </div>
                                 </td>
                               </tr>
                              <tr>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                              </tr>
                              <tr>
                                <td>&nbsp;</td>
                              </tr>
                          </table>
                       </td>
                    </tr>
                    <tr>
                    	<td style="padding-right:15px; border-top:1px solid #d1dceb;">
                        	<table width="98%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>   <td colspan="2" align="left" valign="middle" height="25"  ><div id="form-error"></div></td></tr>
                                          <tr>
                                          
                                            <td width="56%" align="left" valign="middle" height="35"><div id="form-meg"></div></td>
                                            <td width="44%" align="right" valign="middle"><input name="new" type="button" class= "swiftchoicebutton" id="new" value="New" onClick="newentry(); document.getElementById('form-error').innerHTML = '';document.getElementById('form-meg').innerHTML = '';document.getElementById('dealerlist').innerHTML = '';document.getElementById('selecteddealers').innerHTML = ''; "/>
                                              &nbsp;
                                              <input name="save" type="button" class="swiftchoicebutton" id="save" value="Save" onClick="formsubmit('save');" />
                                              &nbsp;</td>
                                          </tr>
                                        </table>
                        </td>
                    </tr>
                    </form>
                  </table></td>
              </tr>
            </table></td>
        </tr>
      </table></td>
  </tr>
</table>

<script>refreshproductarray();
</script>
<?php } ?>