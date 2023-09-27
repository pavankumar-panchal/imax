<table width="100%" border="0" cellspacing="0" cellpadding="2">
<tr class="dashboard-heading">
  <td>Quick Links</td>
</tr>
<tr>
  <td class="quicklinks-font">&nbsp;</td>
</tr>
<tr><td class="quicklinks-font"><table width="100%" border="0" cellspacing="0" cellpadding="2">
 <tr >
            <td >
<A href="./index.php?a_link=home_dashboard" >Home</A> </td></tr>
            
          <tr>  <td>    <? if($p_products == 'yes') { ?>
                <a href="./index.php?a_link=product">Products</a> 
                <? } ?></td></tr>
          <tr> <td>
                <? if($p_dealer == 'yes') { ?>
               <a href="./index.php?a_link=dealer">Dealer</a> 
                <? } ?></td></tr>
                        <tr> <td> <? if($p_bills == 'yes') { ?>
                <a href="./index.php?a_link=bill">Purchases</a> 
                <? } ?>
              </td></tr>
          
           <tr> <td> <? $auditorid = array('195','196'); if(!in_array($userid, $auditorid, true)) { ?>
               <a href="./index.php?a_link=customer">Customer </a></td></tr>
            <tr>  <td><a href="./index.php?a_link=customeramc">Customer AMCs </a>
                <? } ?></td></tr>
       
</table>
</td></tr>

</table>
