<?

	include "header.php";

  if(!empty($_REQUEST['per'])) $per = $_REQUEST['per'];
  else $per = 20;
  
  if(!empty($_REQUEST['page'])) $_REQUEST['start'] = $per*($_REQUEST['page']-1);
  
  if(!empty($_REQUEST['start'])) $start = $_REQUEST['start'];
  else $start = 0;
  
  
?>

		<div id="centerb">
           <!-- top of shit -->
      <div id="midsb">
				<h1>Customers</h1><img src="images/break.jpg" />
        <br /><br />
        	<form action="customers.php" id="search" method="get">
            <div id="searches">
            <div class="topsa">Search Orders </div>
            <table width="100%" style="font-size:14px;">
            	<tr>
               	<td>First Name:</td>
                <td><input type="text" name="first_name" class="inputbgs" /></td>
                <td>Last Name:</td>
                <td><input type="text" name="last_name" class="inputbgs" /></td>
                <td>Phone:</td>
                <td><input type="text" name="phone" class="inputbgs" /></td>
                <td>Email:</td>
                <td><input type="text" name="email" class="inputbgs" /></td>
              </tr>  
              <tr>
                <td>Campaign Id:</td>
                <td>
                	<select name="campaign_filter" class="inputdrop">
										<option value="">--Select a Campaign--</option>
                  </select>
								</td>
                <td>
                	<ul>
                  	<li class="buttonsm"><a href="#" onclick="document.getElementById('search').submit();">Search</a></li>
                  </ul>
                </td>
              </tr>           
            </table>
          </form>
			<hr />
        	<h1>Other Shit</h1>
          <img src="images/break.jpg" /><br /><br />
            <table width="100%">
            
           	  <tr style="background-color:#2b2b2b;text-align:center;height:30px;font-weight:bold;">
                <td>&nbsp;</td>
             	  <td>Customer ID</td>
             	  <td>First Name</td>
             	  <td>Last Name</td>
             	  <td>Phone</td>
             	  <td>Email Address</td>
             	  <td>Account  Created</td>
             	  <td>Features</td>
           	  </tr>
              
              <?
              $sql = "SELECT * FROM orders WHERE 1=1 ";
              if(!empty($_REQUEST['first_name'])) $sql.= " AND Bill_First LIKE '%{$_REQUEST['first_name']}%' ";
              if(!empty($_REQUEST['last_name'])) $sql.= " AND Bill_Last LIKE '%{$_REQUEST['last_name']}%' ";
              if(!empty($_REQUEST['phone'])) $sql.= " AND Bill_Phone LIKE '%{$_REQUEST['phone']}%' ";
              if(!empty($_REQUEST['email'])) $sql.= " AND Bill_Email LIKE '%{$_REQUEST['email']}%' ";
              if(!empty($_REQUEST['campaign_filter'])) $sql.= " AND Product_Name LIKE '%{$_REQUEST['campaign_filter']}%' ";
              
              $qry = mysql_query($sql);
              $num = mysql_num_rows($qry);
              $current_page = ceil($start/$per)+1;
              $max_pages = ceil($num/$per);
              
              
              $sql.= " ORDER BY Bill_Last ASC, Bill_First ASC, Order_Id ASC ";
              $sql.= " LIMIT $start, $per";
              $qry = mysql_query($sql);
              
              while($customer = mysql_fetch_assoc($qry)) {
              ?>
              
              	<tr class="hi">
                  <td><input type="checkbox" /></td>
                  <td><?=$customer['Order_Id']?></td>
                	<td><?=$customer['Bill_First']?></td>
                	<td><?=$customer['Bill_Last']?></td>
                	<td><?=formatPhone($customer['Bill_Phone'])?></td>
                	<td><?=$customer['Bill_Email']?></td>
                	<td><?=$customer['Date_of_Sale']?>
                	<td><a href="customeredit.php?customers_id=<?=$customer['Order_Id']?>">Edit</a> / <a href="customeredit.php?customers_id=<?=$customer['Order_Id']?>&delete=1">Delete</a></td>
                </tr>  
              <?
              }
              ?>        
            </table>
            <div class="bottm">
            <form action="customers.php?start=<?=$start?>" method="get">
            <?
            if(!empty($_REQUEST['first_name'])) { ?> <input type="hidden" name="first_name" value="<?=$_REQUEST['first_name']?>" /> <? }
            ?>
            <?
            if(!empty($_REQUEST['last_name'])) { ?> <input type="hidden" name="last_name" value="<?=$_REQUEST['last_name']?>" /> <? }
            ?>
            <?
            if(!empty($_REQUEST['phone'])) { ?> <input type="hidden" name="phone" value="<?=$_REQUEST['phone']?>" /> <? }
            ?>
            <?
            if(!empty($_REQUEST['email'])) { ?> <input type="hidden" name="email" value="<?=$_REQUEST['email']?>" /> <? }
            ?>
            <?
            if(!empty($_REQUEST['campaign_filter'])) { ?> <input type="hidden" name="campaign_filter" value="<?=$_REQUEST['campaign_filter']?>" /> <? }
            ?>
							Page <img src="images/arrow.png" /> 
              	<input name="page" class="btmnbs" value="<?=$current_page?>" onblur="this.form.submit();" /> 
              	<img src="images/arrowr.png" />  of <?=$max_pages?> pages / Total <?=$num?> Records found   ||  Show 
                <select name="per" class="inputdrop" onchange="this.form.submit();">
                	<option><?=$per?></option
                	<option>10</option>
                	<option>20</option>
                	<option>50</option>
                	<option>100</option>
                </select> Per Page
              </form>
						</div>
           <!-- top of shit -->    
        </div>
    </div>
</body>
</html>
