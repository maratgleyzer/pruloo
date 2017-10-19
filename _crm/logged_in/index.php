<?php
	include '../charts/new/Code/PHP/Includes/FusionCharts.php';
?>
<?

	include "header.php";
  
  if(isset($_REQUEST['sortby'])) $sortby = $_REQUEST['sortby'];

?>
<?php
	//include_once 'chart/salesChart.php';
?>
<style type="text/css">
<!--
.date_input {
	background-color:transparent; 
  border:0; 
  color:silver; 
  position:absolute; 
  left:10px; 
  font-weight:bold;
  text-align:center;
}
-->
</style>


			<div id="center">
        	<div id="dashhead">
            	<div id="dashleft">
                <h1>Sort</h1>
                <ul>
                  <li class="sortdate" style="position:relative;">
                  <form name="date" action="index.php" method="get">
                  <div>
                  	<span>                   
                    	<script language="JavaScript">
                    	new tcal ({
                    		// form name
                    		'formname': 'date',
                    		// input name
                    		'controlname': 'date'
                    	});
                    	</script>
										</span>
                    <span>
                			<input type="text" class="date_input" name="date" value="<?=date('m/d/Y')?>" readonly />
                    </span>
                  </form>
                  </li>
                  <li><a href="#"><img src="images/campaign.jpg" border="0" /></a></li>
                </ul>
              </div>
                <div id="dashright">
                <!-- charts -->
                	<?php echo renderChartHTML("../charts/Charts/FCF_MSArea2D.swf", "Test.xml", "", "data", 490, 240); ?>
                <!-- charts -->
                </div>
            </div>
            <!-- top of shit -->
            <div id="left">
				<div id="bottomhead">
                	<img src="images/exit.jpg" class="exit" /><h1>Order Summary</h1>
                    <img src="images/break.jpg" />
                    <table>
                    	<tr height="25px" style="background-color:#131313;">
                        	<td width="100%">Total New Orders:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT Order_Id FROM orders WHERE Order_Status='Pending' "))?></td>
                        </tr>
                        <tr height="25px">
                        	<td>Total Archive Orders:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT Order_Id FROM orders WHERE Order_Status!='Pending' AND Is_Recurring='0' "))?></td>
                        </tr>
                        <tr height="25px" style="background-color:#131313;">
                        	<td>Total Orders On Hold:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT Order_Id FROM orders WHERE Order_Status='Hold' "))?></td>
                        </tr>
                        <tr height="25px">
                        	<td>Total Active Recurring Orders:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT Order_Id FROM orders WHERE Is_Recurring='1' "))?></td>
                        </tr>
                        <tr height="25px" style="background-color:#131313;">
                        	<td>Total Declined Orders:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT Order_Id FROM orders WHERE Order_Status='Declined' "))?></td>
                        </tr>
                        <tr height="25px">
                        	<td>Total Void/Refund Orders:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT Order_Id FROM orders WHERE Order_Status='Credit' "))?></td>
                        </tr>
                       	<tr height="25px" style="background-color:#131313;">
                        	<td>Total Shipped Orders:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT Order_Id FROM orders WHERE Order_Status='Shipped' "))?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- top of shit -->
             <!-- top of shit -->
            <div id="right">
				<div id="bottomhead">
                	<img src="images/exit.jpg" class="exit" /><h1>Client Summary</h1>
                    <img src="images/break.jpg" />
                    <table>
                    	<tr height="25px" style="background-color:#131313;">
                        	<td width="100%">Active Prospects:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT * FROM contacts"))?></td>
                        </tr>
                        <tr height="25px">
                        	<td>Active Cusomers:</td>
                          <td>
                          <?
														$qry = mysql_query("SELECT * FROM orders WHERE blacklist != '1'");
                            echo mysql_num_rows($qry);
                          ?>
                          </td>
                        </tr>
                        <tr height="25px" style="background-color:#131313;">
                        	<td>Blacklisted Customers:</td>
                          <td>
                          <?
                          	$qry = mysql_query("SELECT * FROM orders WHERE blacklist = '1'") or die(mysql_error());
                          	echo mysql_num_rows($qry);
                          ?>
                          </td>
                        </tr>
                    </table>
                </div><br />
                <div id="bottomhead">
                	<img src="images/exit.jpg" class="exit" /><h1>Affiliate Summary</h1>
                    <img src="images/break.jpg" />
                     <table>
                    	<tr height="25px" style="background-color:#131313;">
                        	<td width="100%">Active Affiliates:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT id FROM affiliate WHERE status='1' "))?></td>
                        </tr>
                        <tr height="25px">
                        	<td>Pending Affiliates:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT id FROM affiliate WHERE status='2' "))?></td>
                        </tr>
                        <tr height="25px" style="background-color:#131313;">
                        	<td>Blacklisted Affiliates:</td>
                          <td><?=mysql_num_rows(mysql_query("SELECT id FROM affiliate WHERE status='0' "))?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- top of shit -->
        </div>
    
<?

	include "footer.php";

?>
