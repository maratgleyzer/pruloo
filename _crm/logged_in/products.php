<?

	include "header.php";

  if(isset($_REQUEST['start'])) $start = $_REQUEST['start'];
  else $start = 0;
  
  if($start < 0) $start = 0;
  
  
?>
  			<div id="center">
             <!-- top of shit -->
             	<div id="mids">
  				<h1>Products</h1>
          	<img src="images/break.jpg" /><br /><br />
                  <table cellspacing="0">
                  	<tr style="height:30px;text-align:center;background-color:#2b2b2b;font-size:16px;font-weight:bold;">
                      	<td style="width:50px">ID</td>
                        <td style="width:250px">Products SKU # </td>
                        <td style="width:250px">Products Name</td>
                        <td style="width:300px">Action</td><br />                    
                    </tr>
                    <?
                      $num = mysql_num_rows(mysql_query("SELECT * FROM products"));
                      $pages = ceil($num/15);
                      $current_page = ceil($start/15);
                      if($current_page <= 0) $current_page = 1;
                      
                      $sql = "SELECT * FROM products ORDER BY name, id LIMIT $start, 15";
                      $qry = mysql_query($sql);
                      
                      while($product = mysql_fetch_assoc($qry)) {
                    ?>
                    <tr height="25px" style="background-color:#131313;">
                      <td><?=$product['id']?></td>
                     	<td><?=$product['sku']?></td>
                      <td><?=$product['name']?></td>
                      <td>
                        <ul>
                          <li class="buttonsm"><a href="editproduct.php?products_id=<?=$product['id']?>">Edit</a></li>
                       		<li class="buttonsm"><a href="editproduct.php?products_id=<?=$product['id']?>&delete=1">Delete</a></li>
                        </ul>
                    	</td>
                    </tr>
                    <?
                    	}
                    ?>
                    <tr style="height:30px;text-align:center;font-weight:bold;">
                     	<td style="width:200px">
                       	<ul>
                      	  <li class="buttonsm"><a href="editproduct.php?products_id=0">New Product</a></li>
                          <li class="buttonsm"><a href="#" onclick="history.back();">Back</a></li>
                        </ul>
                      </td>
                      <td style="width:200px">
                      	&nbsp;
                      </td>
                      <td style="width:200px">
                      	&nbsp;
                      </td>
                      <td style="width:250px">
                      	&nbsp;
                      </td>
                      <br />                    
                    </tr>
                    <tr style="background-color:#2b2b2b;height:35px;">
                     	<td>Page 
                      <?
                      if($start >= 15) {
                      ?>
                      <a href="products.php?start=<?=($start-15)?>"><img src="images/arrowl.jpg" border=0 /></a>
                      <?
                      }
                      ?>
                      <input style="width:10px;" value="<?=$current_page?>" />
                      <a href="products.php?start=<?=($start+15)?>"><img src="images/arrowr.jpg" border=0 /></a> of <?=$pages?> Pages</td>
                      <td colspan=2>
                      <span>
                      <?
                      $total = 0;
                      while($pages >= 1) {
                      ?>
                      	<strong><a href="products.php?start=<?=(15*$total)?>"><?=($total+1)?></a>, &nbsp;</strong>
                      <?
                      	$pages -= 1;
                      	$total += 1;
                      }
                      ?>
                      </span>
                      </td>
                      <td><strong>Total <?=$num?> records found</strong></td>
                    </tr>
                  </table>
      		</div>
           <!-- top of shit -->    
        </div>
    </div>
</body>
</html>
