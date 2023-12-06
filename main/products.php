<?php
if($p_products <> 'yes') {
  $pagelink = getpagelink("unauthorised");
  include($pagelink);
} else {
  // include("../inc/eventloginsert.php");
  ?>
  <link href="../style/main.css?dummy=<?php echo (rand()); ?>" rel=stylesheet>
  <script language="javascript" src="../functions/products.js?dummy=<?php echo (rand()); ?>"></script>

  <table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
    <tr>
      <td width="23%" valign="top" style="border-right:#1f4f66 1px solid;border-bottom:#1f4f66 1px solid;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
          <tr>
            <td valign="top">

              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="left" class="active-leftnav">Product Selection</td>
                </tr>
                <tr>
                  <td>
                    <form id="filterform" name="filterform" method="post" action="" onsubmit="return false;">
                      <table width="100%" border="0" cellspacing="0" cellpadding="3">
                        <tr>
                          <td height="34" id="productselectionprocess" align="left" style="padding:0">&nbsp;</td>
                        </tr>
                        <tr>
                          <td align="left"><input name="detailsearchtext" type="text" class="swifttext"
                              id="detailsearchtext" onkeyup="productsearch(event);" autocomplete="off"
                              style="width:204px" />
                            <div id="detailloaddealerlist">
                              <select name="productlist" size="5" class="swiftselect" id="productlist"
                                style="width:210px; height:400px;" onchange="selectfromlist();"
                                onclick="selectfromlist()">
                                <option></option>
                              </select>
                            </div>
                          </td>
                        </tr>
                      </table>
                    </form>
                  </td>
                </tr>
                <tr>
                  <td>
                    <table width="100%" border="0" align="center" cellpadding="2" cellspacing="0">
                      <tr>
                        <td width="45%" style="padding-left:10px;"><strong>Total Count:</strong> </td>
                        <td width="55%" id="totalproductcount"></td>

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
              </table>

              
            </td>
          </tr>
        </table>
      </td>
      <td width="77%" valign="top" style="border-bottom:#1f4f66 1px solid;">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" id="mainwrap">
          <tr>
            <td valign="top">
              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td>
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td>
                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                            <tr>
                              <td width="27%" align="left" class="active-leftnav">Product Details</td>
                              <td width="40%">
                                <div align="right">Search By Product Code:</div>
                              </td>
                              <td width="33%" align="left">
                                <div align="right">
                                  <input name="searchproductcode" type="text" class="swifttext" id="searchproductcode"
                                    size="25" onkeyup="searchbyproductcodeevent(event);" autocomplete="off" />
                                  <img src="../images/search.gif" width="16" height="15" align="absmiddle"
                                    onclick="productdetailstoform(document.getElementById('searchproductcode').value);"
                                    style="cursor:pointer" />
                                </div>
                              </td>
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
                          <table width="100%" border="0" cellspacing="0" cellpadding="0"
                            style="border:1px solid #308ebc; border-top:none;">
                            <tr>
                              <td class="header-line" style="padding:0">&nbsp;&nbsp;Enter /View Details</td>
                              <td align="right" class="header-line" style="padding-right:7px"></td>
                            </tr>
                            <tr>
                              <td colspan="2" valign="top">
                                <div id="maindiv">
                                  <form action="" method="post" name="submitform" id="submitform"
                                    onsubmit="return false;">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                      <tr></tr>
                                      <tr>
                                        <td width="50%" valign="top" style="border-right:1px solid #d1dceb;">
                                          <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top">Product Name:</td>
                                              <td align="left" valign="top" bgcolor="#f7faff"><input name="productname"
                                                  type="text" class="swifttext-mandatory" id="productname" size="40"
                                                  maxlength="50" autocomplete="off" />
                                                <input type="hidden" name="lastslno" id="lastslno" />
                                              </td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" bgcolor="#EDF4FF">Product Code:</td>
                                              <td align="left" valign="top" bgcolor="#EDF4FF"><input name="productcode"
                                                  type="text" class="swifttext-mandatory" id="productcode" size="40"
                                                  maxlength="10" autocomplete="off" />
                                                <br />
                                              </td>
                                            </tr>
                                            <tr bgcolor="#edf4ff">
                                              <td align="left" valign="top" bgcolor="#F7FAFF">Product Not in use:</td>
                                              <td align="left" valign="top" bgcolor="#F7FAFF"><input type="checkbox"
                                                  name="productnotinuse" id="productnotinuse" /></td>
                                            </tr>
                                            <tr bgcolor="#f7faff">
                                              <td align="left" valign="top" bgcolor="#EDF4FF">Product Type:</td>
                                              <td align="left" valign="top" bgcolor="#EDF4FF"><select name="producttype"
                                                  class="swiftselect" id="producttype" style="width:110px">
                                                  <option value="General" selected="selected">General</option>
                                                  <option value="DOTNET">DOTNET</option>
                                                  <option value="COMPTAX">COMPTAX</option>
                                                  <option value="ECONOMY">ECONOMY</option>
                                                </select></td>
                                            </tr>
                                            <tr bgcolor="#edf4ff">
                                              <td align="left" valign="top" bgcolor="#F7FAFF">Product Group:</td>
                                              <td align="left" valign="top" bgcolor="#F7FAFF"><select name="productgroup"
                                                  class="swiftselect-mandatory" id="productgroup" style="width:110px">
                                                  <option value="" selected="selected">Select a group</option>
                                                  <option value="NA">NA</option>
                                                  <option value="STO">STO</option>
                                                  <option value="SES">SES</option>
                                                  <option value="CONTACT">CONTACT</option>
                                                  <option value="SPP">SPP</option>
                                                  <option value="SAC">SAC</option>
                                                  <option value="OTHERS">OTHERS</option>
                                                  <option value="SURVEY">SURVEY</option>
                                                  <option value="TDS">TDS</option>
                                                  <option value="SVH">SVH</option>
                                                  <option value="SVI">SVI</option>
                                                  <option value="AIR">AIR</option>
                                                  <option value="XBRL">XBRL</option>
                                                  <option value="GST">GST</option>
                                                </select></td>
                                            </tr>
                                            <tr bgcolor="#edf4ff">
                                              <td align="left" valign="top" bgcolor="#EDF4FF">Financial Year:</td>
                                              <td align="left" valign="top" bgcolor="#EDF4FF">
                                                <select name="financialyear" id="financialyear"
                                                  class="swiftselect-mandatory" style="width:110px">
                                                  <option value="">Select a Year</option>
                                                  <?php
                                                  include('../inc/financialyear.php');
                                                  ?>

                                                </select>
                                              </td>
                                            </tr>
                                            <tr bgcolor="#edf4ff">
                                              <td align="left" valign="top" bgcolor="#F7FAFF">Updation:</td>
                                              <td align="left" valign="top" bgcolor="#F7FAFF"><select name="updationtype"
                                                  class="swiftselect-mandatory" id="updationtype">
                                                  <option value="" selected="selected">Select a Type</option>
                                                  <option value="latest">Latest</option>
                                                  <option value="newupdationavailable">New Updation Available</option>
                                                </select></td>
                                            </tr>
                                            <tr bgcolor="#edf4ff">
                                              <td align="left" valign="top" bgcolor="#edf4ff">Allow for Dealer Purchase:
                                              </td>
                                              <td align="left" valign="top" bgcolor="#edf4ff"><input type="checkbox"
                                                  name="allowdealerpurchase" id="allowdealerpurchase"
                                                  onclick="dealerpurchasecaptionmandatory();"
                                                  onchange="dealerpurchasecaptionmandatory();" /></td>
                                            </tr>
                                            <tr bgcolor="#edf4ff">
                                              <td align="left" valign="top" bgcolor="#F7FAFF">Dealer Purchase Caption:
                                              </td>
                                              <td align="left" valign="top" bgcolor="#F7FAFF"><input
                                                  name="dealerpurchasecaption" type="text" class="swifttext"
                                                  id="dealerpurchasecaption" size="40" maxlength="100"
                                                  autocomplete="off" /></td>
                                            </tr>

                                          </table>
                                        </td>

                                      </tr>
                                      <tr>
                                        <td colspan="2" align="right" valign="middle"
                                          style="padding-right:15px; border-top:1px solid #d1dceb;">
                                          <table width="98%" border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                              <td width="58%" height="35" align="left" valign="middle">
                                                <div id="form-error"></div>
                                              </td>
                                              <td width="42%" height="35" align="right" valign="middle"><input name="new"
                                                  type="button" class="swiftchoicebutton" id="new" value="New"
                                                  onclick="newentry(); document.getElementById('form-error').innerHTML = '';" />
                                                &nbsp;
                                                <input name="save" type="button" class="swiftchoicebutton" id="save"
                                                  value="Save" onclick="formsubmit('save');" />
                                                &nbsp;
                                                <input name="search" type="submit" class="swiftchoicebutton" id="search"
                                                  value="Search"
                                                  onclick="document.getElementById('filterdiv').style.display='block';" />
                                              </td>
                                            </tr>
                                          </table>
                                        </td>
                                      </tr>
                                    </table>
                                  </form>
                                </div>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                      <tr>
                        <td>
                          <div id="filterdiv" style="display:none;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0"
                              style="border:1px solid #308ebc;">
                              <tr>
                                <td valign="top">
                                  <div>
                                    <form action="" method="post" name="searchfilterform" id="searchfilterform"
                                      onsubmit="return false;">
                                      <table width="100%" border="0" cellspacing="0" cellpadding="2">
                                        <tr>
                                          <td width="100%" valign="top" style="border-right:1px solid #d1dceb;">&nbsp;
                                          </td>
                                        </tr>
                                        <tr>
                                          <td valign="top" style="border-right:1px solid #d1dceb;">
                                            <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                              <tr bgcolor="#edf4ff">
                                                <td width="14%" align="left" valign="top">Search Text: </td>
                                                <td width="86%" align="left" valign="top"><input name="searchcriteria"
                                                    type="text" id="searchcriteria" size="50" maxlength="25"
                                                    class="swifttext" autocomplete="off" value="" /></td>
                                              </tr>
                                              <tr bgcolor="#f7faff">
                                                <td colspan="2" align="left" valign="top" style="padding:0">
                                                  <table width="100%" border="0" cellspacing="0" cellpadding="3">
                                                    <tr>
                                                      <td width="14%" valign="top">In:
                                                      </td>
                                                      <td width="86%"><label>
                                                          <input type="radio" name="databasefield" id="databasefield0"
                                                            value="productcode" />
                                                          Product code</label>
                                                        <label>
                                                          <input type="radio" name="databasefield" id="databasefield1"
                                                            value="productname" checked="checked" />
                                                          Product Name</label>
                                                        <label>
                                                          <input type="radio" name="databasefield" id="databasefield2"
                                                            value="productnotinuse" />
                                                          Product Not in use</label>
                                                        <label>
                                                          <input type="radio" name="databasefield" value="producttype"
                                                            id="databasefield3" />
                                                          Product Type</label>
                                                        <label>
                                                          <input type="radio" name="databasefield" value="productgroup"
                                                            id="databasefield4" />
                                                          Product Group</label>
                                                        <label></label><label></label>
                                                      </td>
                                                    </tr>
                                                  </table>
                                                  <label></label>
                                                </td>
                                              </tr>
                                              <tr bgcolor="#edf4ff">
                                                <td align="left" valign="top">Order By:</td>
                                                <td align="left" valign="top"><select name="orderby" id="orderby"
                                                    class="swiftselect">
                                                    <option value="productcode">Product Code</option>
                                                    <option value="productname" selected="selected">Product Name</option>
                                                    <option value="producttype">Type</option>
                                                    <option value="productgroup">group</option>
                                                  </select>
                                                </td>
                                              </tr>
                                            </table>
                                          </td>
                                        </tr>
                                        <tr>
                                          <td align="right" valign="middle"
                                            style="padding-right:15px; border-top:1px solid #d1dceb;">
                                            <table width="98%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                <td width="69%" height="35" align="left" valign="middle">
                                                  <div id="filter-form-error"></div>
                                                </td>
                                                <td width="31%" align="right" valign="middle"><input name="filter"
                                                    type="button" class="swiftchoicebutton" id="filter" value="Filter"
                                                    onclick="searchfilter('');" />
                                                  &nbsp;&nbsp;
                                                  <input name="close" type="button" class="swiftchoicebutton-red"
                                                    id="close" value="Close"
                                                    onclick="document.getElementById('filterdiv').style.display='none';" />
                                                </td>
                                              </tr>
                                            </table>
                                          </td>
                                        </tr>
                                      </table>
                                    </form>
                                  </div>
                                </td>
                              </tr>
                            </table>
                          </div>
                        </td>
                      </tr>
                      <tr>
                        <td>&nbsp;</td>
                      </tr>
                      <tr>
                        <td>
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                              <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="140px" align="center" id="tabgroupgridh1"
                                      onclick="gridtab2('1','tabgroupgrid','&nbsp; &nbsp;Default'); "
                                      style="cursor:pointer" class="grid-active-tabclass">Default</td>
                                    <td width="2">&nbsp;</td>

                                    <td width="140px" align="center" id="tabgroupgridh2"
                                      onclick="gridtab2('2','tabgroupgrid','&nbsp; &nbsp;Search Results');"
                                      style="cursor:pointer" class="grid-tabclass">Search Result</td>
                                    <td width="2">&nbsp;</td>
                                    <td width="140" align="center"></td>
                                    <td width="140" align="center"></td>
                                    <td width="140" align="center"></td>
                                    <td>
                                      <div id="gridprocessing"></div>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                                  style="border:1px solid #308ebc; border-top:none;">
                                  <tr class="header-line">
                                    <td width="220px">
                                      <div id="tabdescription"></div>
                                    </td>
                                    <td width="216px" style="text-align:center;"><span id="gridcount"></span></td>
                                    <td width="296px" style="padding:0">&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td colspan="3" align="center" valign="top">
                                      <div id="tabgroupgridc1"
                                        style="overflow:auto; height:150px; width:704px; padding:2px;" align="center">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td>
                                              <div id="tabgroupgridc1_1" align="center"></div>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td>
                                              <div id="tabgroupgridc1link" align="left">
                                              </div>
                                            </td>
                                          </tr>
                                        </table>
                                        <div id="productresultgrid"
                                          style="overflow:auto; display:none; height:150px; width:704px; padding:2px;"
                                          align="center">&nbsp;</div>
                                      </div>
                                      <div id="tabgroupgridc2"
                                        style="overflow:auto;height:150px; width:704px; padding:2px; display:none;"
                                        align="center">
                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                          <tr>
                                            <td>
                                              <div id="tabgroupgridc2_1"></div>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td>
                                              <div id="tabgroupgridc1linksearch" align="left">
                                              </div>
                                            </td>
                                          </tr>
                                        </table>
                                        <div id="searchresultgrid" style="display:none;" align="center">&nbsp;</div>
                                      </div>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
  <script>refreshproductarray(); </script>
<?php } ?>