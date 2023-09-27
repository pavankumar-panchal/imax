<?
if($p_mergecustomer <> 'yes') 
{ 
	$pagelink = getpagelink("unauthorised"); include($pagelink);
} 
else 
{
include("../inc/eventloginsert.php");
?>
<link href="../style/main.css?dummy=<? echo (rand());?>" rel=stylesheet>
<script language="javascript" src="../functions/mergecustomer.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/javascripts.js?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictjs.php?dummy=<? echo (rand());?>"></script>
<script language="javascript" src="../functions/getdistrictfunction.php?dummy=<? echo (rand());?>"></script>
<table width="952" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td width="77%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
        <tr>
          <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="3">
                          <tr>
                            <td width="13%" align="left" class="active-leftnav"> Merge Customer</td>
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
                      <td><table width="100%" border="0" cellspacing="0" cellpadding="0" >
                          <tr style="cursor:pointer" onClick="displayDiv2('filterdiv','toggleimg1');">
                            <td class="header-line" style="padding:0">&nbsp;&nbsp;Select Customers for Merge</td>
                            <td align="right" class="header-line" style="padding-right:7px;"><div align="right"><img src="../images/minus.jpg" border="0" id="toggleimg1" name="toggleimg1"  align="absmiddle" /></div></td>
                            <!--<td width="20%" align="right" class="header-line" style="padding-right:7px"><a href="./index.php?a_link=mergecustomerlist" class="textlink">Merge Customer List &gt;&gt;</a></td>-->
                          </tr>
                          <tr>
                            <td colspan="3" valign="top"><div id="maindiv">
                                <form action="" method="post" name="submitform" id="submitform" onsubmit="return false;">
                                  <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                      <td><div id="filterdiv" style=" display:block">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="3" style="border:1px solid #308ebc; border-top:none; border-bottom:1px solid #308ebc">
                                            <tr>
                                              <td width="25%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td width="17%" align="left" style="padding:0">From:</td>
                                                    <td width="56%" id="customerselectionprocess" align="left" style="padding:0">&nbsp;</td>
                                                    <td width="27%" id="customerselectionprocess" style="padding:0"><a onclick="gettotalcustomercount();" style="cursor:pointer"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" /></a>
                                                      <input type="hidden" name="customer1" id="customer1" /></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" align="left"><input name="detailsearchtext" type="text" class="swifttext" id="detailsearchtext"  onkeyup="customersearch(event);displaytoform()" autocomplete="off" style="width:204px"/>
                                                      <div id="detailloadcustomerlist">
                                                        <select name="customerlist" size="5" class="swiftselect" id="customerlist" style="width:210px; height:225px" onclick ="selectfromlist();" onchange="selectfromlist();">
                                                        </select>
                                                      </div></td>
                                                  </tr>
                                                  <tr >
                                                    <td colspan="2"><strong>Total Count:</strong> <span id="totalcount1"></span></td>
                                                    <td align="left">&nbsp;</td>
                                                  </tr>
                                                </table></td>
                                              <td width="25%" valign="top"><table width="94%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td width="17%" align="left" style="padding:0">To:</td>
                                                    <td width="56%" id="customerselectionprocess2" align="left" style="padding:0">&nbsp;</td>
                                                    <td width="27%" id="customerselectionprocess2" style="padding:0"><a onclick="gettotalcustomercount2();" style="cursor:pointer"><img src="../images/imax-customer-refresh.jpg" alt="Refresh customer" title="Refresh customer Data" />
                                                      <input type="hidden" name="customer2" id="customer2" />
                                                      </a></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="3" align="left"><input name="detailsearchtext2" type="text" class="swifttext" id="detailsearchtext2" onkeyup="customersearch2(event);displaytoform()"  autocomplete="off" style="width:204px"/>
                                                      <div id="detailloadcustomerlist2">
                                                        <select name="customerlist2" size="5" class="swiftselect" id="customerlist2" style="width:210px; height:225px" onclick ="selectfromlist2();" onchange="selectfromlist2();">
                                                        </select>
                                                      </div></td>
                                                  </tr>
                                                  <tr>
                                                    <td colspan="2"><strong>Total Count:</strong> <span id="totalcount2"></span></td>
                                                    <td align="left">&nbsp;</td>
                                                  </tr>
                                                </table></td>
                                              <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                  <tr>
                                                    <td>&nbsp;</td>
                                                  </tr>
                                                  <tr>
                                                    <td align="center"><table width="90%" border="0" cellspacing="0" cellpadding="5" bgcolor="#F1F4FA" style="border:solid 1px #dbe5fa ">
                                                        <tr>
                                                          <td align="left"><strong><font color="#6464FF">Merging a Customer, will also merge the following dependent records:</font></strong></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left"><UL>
                                                              <strong>1. Customer Data:</strong>
                                                              <li  style="padding-left:10px">1. Registration Records</li>
                                                              <li  style="padding-left:10px">2. Hardware lock records</li>
                                                              <li  style="padding-left:10px">3. Customer AMCs</li>
                                                              <li  style="padding-left:10px">4. Customer Interactions</li>
                                                              <li  style="padding-left:10px">5. Customer Payment Requests</li>
                                                              <li  style="padding-left:10px">6. Unregistered PIN Numbers Attached</li>
                                                              <li  style="padding-left:10px">7. Cross Product Followups Details</li>
                                                              <li  style="padding-left:10px">8. Invoice Details </li>
                                                              <li  style="padding-left:10px">9. All Activity Logs</li>
                                                            </UL></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left"><UL>
                                                              <strong>2. SMS Data:</strong>
                                                              <li style="padding-left:10px">1. SMS Credits</li>
                                                              <li style="padding-left:10px">2. SMS logs</li>
                                                            </UL></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="left"><UL>
                                                              <strong>3. Support Data</strong>
                                                              <li style="padding-left:10px">1. All the registers (Calls/Emails/etc) </li>
                                                            </UL></td>
                                                        </tr>
                                                      </table></td>
                                                  </tr>
                                                </table></td>
                                            </tr>
                                          </table>
                                        </div></td>
                                    </tr>
                                    <tr>
                                      <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                      <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                          <tr>
                                            <td id="displayprocessingimage" height="10px" >&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                  <td><div style="display:none; width:935px;" id="displaydiv" >
                                                      <table width="100%" border="0" cellspacing="0" cellpadding="8" style="border:1px solid #308ebc; border-top:none; border-bottom:1px solid #308ebc">
                                                        <tr>
                                                          <td align="left"  class="header-line" style="padding:0">Merging Information</td>
                                                        </tr>
                                                        <tr>
                                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                              <tr>
                                                                <td><table width="100%" border="0" cellspacing="0" cellpadding="4">
                                                                    <tr>
                                                                      <td width="2%" height="25" class="producttabheadnone"></td>
                                                                      <td width="18%" onclick="tabopen5('1','tabg1')" class="producttabheadactive" id="tabg1h1" style="cursor:pointer;"><div align="center"><strong>General Details</strong></div></td>
                                                                      <td width="2%" class="producttabheadnone"></td>
                                                                      <td width="18%" onclick="tabopen5('2','tabg1')" class="producttabhead" id="tabg1h2" style="cursor:pointer;"><div align="center"><strong>Contact Details</strong></div></td>
                                                                      <td width="2%" class="producttabheadnone">&nbsp;</td>
                                                                      <td width="18%" class="producttabhead" ></td>
                                                                      <td width="2%" class="producttabheadnone">&nbsp;</td>
                                                                      <td width="18%"  class="producttabhead" >&nbsp;</td>
                                                                      <td width="2%" class="producttabheadnone">&nbsp;</td>
                                                                      <td width="18%"  class="producttabhead">&nbsp;</td>
                                                                    </tr>
                                                                  </table></td>
                                                              </tr>
                                                              <tr>
                                                                <td><div style="display:block; "  align="justify" id="tabg1c1" >
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="productcontent" height="450px">
                                                                      <tr>
                                                                        <td><table width="100%" border="0" cellspacing="0" cellpadding="2" >
                                                                            <tr>
                                                                              <td  width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5" style="border-right:1px solid #308ebc">
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td width="24%" valign="top" bgcolor="#EDF4FF" align="left">Customer ID:</td>
                                                                                    <td width="76%" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><input name="customerid" type="text" class="swifttext" id="customerid" size="45"  autocomplete="off" />
                                                                                            <input type="hidden" name="fromcustomerid" id="fromcustomerid" />
                                                                                            <input type="hidden" name="tocustomerid" id="tocustomerid" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td align="left" id="m_customerid" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td width="24%" valign="top" bgcolor="#f7faff" align="left">Company:</td>
                                                                                    <td width="76%" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><input name="businessname" type="text" class="swifttext" id="businessname" size="45"  autocomplete="off" />
                                                                                            <input type="hidden" name="lastslno" id="lastslno" />
                                                                                            <input type="hidden" name="slno" id="slno" />
                                                                                            <input type="hidden" name="frombusinessname" id="frombusinessname" />
                                                                                            <input type="hidden" name="tobusinessname" id="tobusinessname" />
                                                                                            <input type="hidden" name="fromdistrict" id="fromdistrict" />
                                                                                            <input type="hidden" name="todistrict" id="todistrict" />
                                                                                            <input type="hidden" name="fromdistrictname" id="fromdistrictname" />
                                                                                            <input type="hidden" name="todistrictname" id="todistrictname" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td align="left" id="m_businessname" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left">Address:</td>
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td ><textarea name="address" cols="45" class="swifttextarea" id="address">
                                                                     </textarea>
                                                                                            <input type="hidden" name="fromaddress" id="fromaddress" />
                                                                                            <input type="hidden" name="toaddress" id="toaddress" /></td>
                                                                                          <input type="hidden" name="tocontact" id="tocontact" />
                                                                                          <input type="hidden" name="fromcontact" id="fromcontact" />
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_address" style="font-weight:bold" height="30px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td valign="top" bgcolor="#F7FAFF" align="left">Place:</td>
                                                                                    <td valign="top" bgcolor="#F7FAFF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td><input name="place" type="text" class="swifttext" id="place" size="45" autocomplete="off" />
                                                                                            <input type="hidden" name="fromplace" id="fromplace" />
                                                                                            <input type="hidden" name="toplace" id="toplace" />
                                                                                            <input type="hidden" name="reffromcontact" id="reffromcontact" />
                                                                                            <input type="hidden" name="reftocontact" id="reftocontact" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_place" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left">State:</td>
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td><select name="state" class="swiftselect" id="state" onchange="getdistrict('districtcodedisplay',this.value);" onkeyup="getdistrict('districtcodedisplay',this.value);"  style="width:200px;">
                                                                                              <option value="">Select A State</option>
                                                                                              <? include('../inc/state.php'); ?>
                                                                                            </select>
                                                                                            <input type="hidden" name="fromstate" id="fromstate" />
                                                                                            <input type="hidden" name="tostate" id="tostate" />
                                                                                            <input type="hidden" name="fromstatename" id="fromstatename" />
                                                                                            <input type="hidden" name="tostatename" id="tostatename" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_state" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td valign="top" bgcolor="#F7FAFF" align="left">District:</td>
                                                                                    <td valign="top" bgcolor="#F7FAFF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td id="districtcodedisplay" align="left"><select name="district" class="swiftselect" id="district" style="width:200px;">
                                                                                              <option value="">Select A State First</option>
                                                                                            </select>
                                                                                          </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_district" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left">Pincode:</td>
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><input name="pincode" type="text" class="swifttext" id="pincode" size="45" maxlength="6"  autocomplete="off"/>
                                                                                            <input type="hidden" name="frompincode" id="frompincode" />
                                                                                            <input type="hidden" name="topincode" id="topincode" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_pincode" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left">Remarks :</td>
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><input name="remarks" type="text" class="swifttext" id="remarks" size="45" maxlength="200"  autocomplete="off"/>
                                                                                            <input type="hidden" name="fromremarks" id="fromremarks" />
                                                                                            <input type="hidden" name="toremarks" id="toremarks" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_remarks" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  
                                                                                  
                                                                                </table></td>
                                                                              <td width="50%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="5">
                                                                                  <tr bgcolor="#EDF4FF">
                                                                                    <td width="24%" align="left" valign="top" bgcolor="#EDF4FF">STD code:</td>
                                                                                    <td width="76%" align="left" valign="top" bgcolor="#EDF4FF"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><input name="stdcode" type="text" class="swifttext" id="stdcode" size="45"  autocomplete="off"/>
                                                                                            <input type="hidden" name="fromstdcode" id="fromstdcode" />
                                                                                            <input type="hidden" name="tostdcode" id="tostdcode" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_stdcode" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" align="left">Fax:</td>
                                                                                    <td valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td><input name="fax" type="text" class="swifttext" id="fax" size="45" autocomplete="off" />
                                                                                            <input type="hidden" name="fromfax" id="fromfax" />
                                                                                            <input type="hidden" name="tofax" id="tofax" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_fax" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" bgcolor="#f7faff" align="left">Website:</td>
                                                                                    <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><input name="website" type="text" class="swifttext" id="website" size="45" autocomplete="off" />
                                                                                            <input type="hidden" name="fromwebsite" id="fromwebsite" />
                                                                                            <input type="hidden" name="towebsite" id="towebsite" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_website" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td valign="top" bgcolor="#edf4ff" align="left">Type:</td>
                                                                                    <td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><select name="type" class="swiftselect" id="type" style="width:200px">
                                                                                              <option value="" selected="selected">Type Selection</option>
                                                                                              <? 
											include('../inc/custype.php');
											?>
                                                                                            </select>
                                                                                            <input type="hidden" name="fromtype" id="fromtype" />
                                                                                            <input type="hidden" name="totype" id="totype" />
                                                                                            <input type="hidden" name="fromtypename" id="fromtypename" />
                                                                                            <input type="hidden" name="totypename" id="totypename" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_type" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" bgcolor="#f7faff" align="left">Category:</td>
                                                                                    <td valign="top" bgcolor="#f7faff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><select name="category" class="swiftselect" id="category"  style="width:200px">
                                                                                              <option value="">Category Selection</option>
                                                                                              <? 
											include('../inc/category.php');
											?>
                                                                                            </select>
                                                                                            <input type="hidden" name="fromcategory" id="fromcategory" />
                                                                                            <input type="hidden" name="tocategory" id="tocategory" />
                                                                                            <input type="hidden" name="fromcategoryname" id="fromcategoryname" />
                                                                                            <input type="hidden" name="tocategoryname" id="tocategoryname" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_category" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#edf4ff">
                                                                                    <td valign="top" bgcolor="#edf4ff" align="left">Region:</td>
                                                                                    <td valign="top" bgcolor="#edf4ff" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><select name="region" class="swiftselect-mandatory" id="region" style="width:200px">
                                                                                              <option value="">Select A Region</option>
                                                                                              <? 
											include('../inc/region.php');
											?>
                                                                                            </select>
                                                                                            <input type="hidden" name="fromregion" id="fromregion" />
                                                                                            <input type="hidden" name="toregion" id="toregion" />
                                                                                            <input type="hidden" name="fromregionname" id="fromregionname" />
                                                                                            <input type="hidden" name="toregionname" id="toregionname" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_region" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" align="left">Current Dealer:</td>
                                                                                    <td valign="top" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><select name="dealer" class="swiftselect" id="dealer" style="width:180px;">
                                                                                              <option value="">Make A Selection</option>
                                                                                              <? 
											include('../inc/firstdealer.php');
											?>
                                                                                            </select>
                                                                                            <input type="hidden" name="fromdealer" id="fromdealer" />
                                                                                            <input type="hidden" name="todealer" id="todealer" />
                                                                                            <input type="hidden" name="fromdealername" id="fromdealername" />
                                                                                            <input type="hidden" name="todealername" id="todealername" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_dealer" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr bgcolor="#f7faff">
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left">Branch:</td>
                                                                                    <td valign="top" bgcolor="#EDF4FF" align="left"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                        <tr>
                                                                                          <td align="left"><select name="branch" class="swiftselect-mandatory" id="branch" style="width:200px">
                                                                                              <option value="">Select A Branch</option>
                                                                                              <? 
											include('../inc/branch.php');
											?>
                                                                                            </select>
                                                                                            <input type="hidden" name="frombranch" id="frombranch" />
                                                                                            <input type="hidden" name="tobranch" id="tobranch" />
                                                                                            <input type="hidden" name="frombranchname" id="frombranchname" />
                                                                                            <input type="hidden" name="tobranchname" id="tobranchname" /></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                          <td id="m_branch" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                          </table></td>
                                                                      </tr>
                                                                    </table>
                                                                  </div>
                                                                  <div style="display:none; " align="justify" id="tabg1c2">
                                                                    <table width="100%" border="0" cellspacing="0" cellpadding="2" class="productcontent" height="450px">
                                                                      <tr>
                                                                        <td colspan="2" valign="top"><table width="100%" border="0" cellspacing="3" cellpadding="0"  >
                                                                            <tr>
                                                                              <td><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                  <tr>
                                                                                    <td ><table width="100%" border="0" cellspacing="0" cellpadding="5" style="color:#646464; font-weight:bold">
                                                                                        <tr>
                                                                                          <td width="4%">&nbsp;</td>
                                                                                          <td width="14%" ><div align="center">Type</div></td>
                                                                                          <td width="20%"><div align="center">Name</div></td>
                                                                                          <td width="20%"><div align="center">Phone</div></td>
                                                                                          <td width="15%"><div align="center">Cell</div></td>
                                                                                          <td width="23%"><div align="center">Email Id</div></td>
                                                                                          <td width="4%">&nbsp;</td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td ><table width="100%" border="0" cellspacing="0" cellpadding="3"  id="adddescriptionrows">
                                                                                        <tr id="removedescriptionrow1">
                                                                                          <td width="5%"><div align="center">
                                                                                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                                <tr>
                                                                                                  <td><strong>1</strong></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td>&nbsp;</td>
                                                                                                </tr>
                                                                                              </table>
                                                                                            </div></td>
                                                                                          <td width="11%"><div align="center">
                                                                                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                                <tr>
                                                                                                  <td align="left"><select name="selectiontype1" id="selectiontype1" style="width:120px" class="swiftselect-mandatory">
                                                                                                      <option value="" selected="selected" >--Select--</option>
                                                                                                      <option value="general" >General</option>
                                                                                                      <option value="gm/director">GM/Director</option>
                                                                                                      <option value="hrhead">HR Head</option>
                                                                                                      <option value="ithead/edp">IT-Head/EDP</option>
                                                                                                      <option value="softwareuser" >Software User</option>
                                                                                                      <option value="financehead">Finance Head</option>
                                                                                                      <option value="others" >Others</option>
                                                                                                    </select>
                                                                                                    <input type="hidden" name="fromtype" id="fromtype" />
                                                                                                    <input type="hidden" name="totype" id="totype" />
                                                                                                    <input type="hidden" name="fromtypename" id="fromtypename" />
                                                                                                    <input type="hidden" name="totypename" id="totypename" /></td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td id="m_type" style="font-weight:bold" height="15px" align="left">&nbsp;</td>
                                                                                                </tr>
                                                                                              </table>
                                                                                            </div></td>
                                                                                          <td width="16%"><div align="center">
                                                                                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                                <tr>
                                                                                                  <td align="left"><input name="name1" type="text" class="swifttext" id="name1"   autocomplete="off" style="width:170px" />
                                                                                                  </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td align="left" id="m_name" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                                                </tr>
                                                                                              </table>
                                                                                            </div></td>
                                                                                          <td width="18%"><div align="center">
                                                                                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                                <tr>
                                                                                                  <td align="left"><input name="phone1" type="text" class="swifttext" id="phone1"  autocomplete="off" style="width:170px" />
                                                                                                  </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td align="left" id="m_phone" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                                                </tr>
                                                                                              </table>
                                                                                            </div></td>
                                                                                          <td width="15%"><div align="center">
                                                                                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                                <tr>
                                                                                                  <td align="left"><input name="cell1" type="text" class="swifttext" id="cell1"   autocomplete="off" style="width:120px" />
                                                                                                  </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td align="left" id="m_cell" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                                                </tr>
                                                                                              </table>
                                                                                            </div></td>
                                                                                          <td width="27%"><div align="center">
                                                                                              <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                                                                <tr>
                                                                                                  <td align="left"><input name="emailid1" type="text" class="swifttext" id="emailid1"  autocomplete="off" style="width:200px" />
                                                                                                  </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                  <td align="left" id="m_emailid" style="font-weight:bold" height="15px">&nbsp;</td>
                                                                                                </tr>
                                                                                              </table>
                                                                                            </div></td>
                                                                                          <td width="8%"><font color = "#FF0000"><strong><a id="removerowdiv1" onclick ="removedescriptionrows('removedescriptionrow1','1')" style="cursor:pointer;">X</a></strong></font>
                                                                                            <input type="hidden" name="contactslno1" id="contactslno1" /></td>
                                                                                        </tr>
                                                                                      </table></td>
                                                                                  </tr>
                                                                                  <tr>
                                                                                    <td><div align="left" id="adddescriptionrowdiv">
                                                                                        <div align="right"><a onclick="adddescriptionrows();" style="cursor:pointer" class="r-text">Add one More >></a></div>
                                                                                      </div></td>
                                                                                  </tr>
                                                                                </table></td>
                                                                            </tr>
                                                                          </table></td>
                                                                      </tr>
                                                                    </table>
                                                                  </div></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                        <tr>
                                                          <td align="right"><table width="100%" border="0" cellspacing="0" cellpadding="2">
                                                              <tr >
                                                                <td ><div id="form-error" style="padding:0px"></div></td>
                                                                <td align="right" ><input name="merge" type="button" class= "swiftchoicebutton" id="merge" value="Merge" onclick="formsubmit();" />
                                                                  &nbsp;&nbsp;&nbsp;
                                                                  <input name="clear" type="reset" class= "swiftchoicebutton" id="clear" value="Cancel" onclick="clearform()" /></td>
                                                              </tr>
                                                            </table></td>
                                                        </tr>
                                                      </table>
                                                    </div></td>
                                                </tr>
                                              </table></td>
                                          </tr>
                                        </table></td>
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
<script>
gettotalcustomercount();gettotalcustomercount2();
</script>
<? 
}
?>
