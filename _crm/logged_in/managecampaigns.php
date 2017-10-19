<?

	include "header.php";

  if ($_POST) {
  	echo '<div style="color: #fff; padding: 17px;">';
  	echo 'Temporarily disabled...<br />';
  
  	if (empty($_POST['campaigns'])) echo 'Dude, check something first.';
    else {
    	foreach ($_POST['campaigns'] as $campaigns_id) {
      	$campaign = array('id' => $campaigns_id);
  	    if ($_POST['action'] == 'Delete') {
        	$campaign['purge'] = 1;
        } elseif ($_POST['action'] == 'Disable') {
        	$campaign['active'] = 0;
        }
  			save_record($campaign, 'campaigns');
      }
    }
    echo '</div>';
  }
  
?>


      <form action="managecampaigns.php" method="post">
<div id="centerb">
           <!-- top of shit -->
           	<div id="midsb">
               	<div id="searches"><div class="topsa">Manage Current Campaigns </div>
                  <!-- top of shit -->
                </div>
           	  <hr />
               	<h1>Current Campaigns</h1><img src="images/break.jpg" /><br /><br />
      <ul>
        <li class="buttonsm"><input type="submit" value="Disable" name="action" style="border: none; background: transparent; font-weight: bold; color: #fff;" /></li>
        <li class="buttonsm"><input type="submit" value="Delete" name="action"  style="border: none; background: transparent; font-weight: bold; color: #fff;" /></li>
        <li class="buttonsm"><a href="newcampaign.php">New </a></li>
      </ul>
        
				<table style="width:100%;" cellspacing="0">
          <tr style="background-color:#2b2b2b;text-align:center;height:30px;font-weight:bold;" ><td></td><td>Order ID</td>
            <td>Campaign Name</td>
            <td>Campaign Type</td><td>Product</td><td>Product Name</td>
            <td>Upsell</td>
            <td>Features</td>
          </tr>
        	<tr class="hi">
          	<td><input type="checkbox" name="campaigns[]" value="1" /></td>
            <td><a href="#">1169</a></td><td>Rem Bright Free Trial</td>
            <td>Two Page Campaign (Single Products) </td>
            <td>Instant Access </td>
            <td>RemBright Exlusive Offer Rebill (13) </td>
            <td><img src="images/approve.png" /></td>
            <td><a href="newcampaign.php?campaigns_id=1169">Edit</a></td>
          </tr>           
          <tr class="lo">
          	<td><input type="checkbox" name="campaigns[]" value="1" /></td>
            <td><a href="#">1172</a></td>
            <td>Rem Bright Free Trial1</td>
            <td>Two Page Campaign (Single Products) </td>
            <td>Instant Access </td>
            <td>RemBright Exlusive Offer Rebill (13) </td>
            <td><img src="images/approve.png" /></td>
            <td><a href="newcampaign.php?campaigns_id=1172">Edit</a></td>
          </tr>           
          <tr class="hi">
          	<td><input type="checkbox" name="campaigns[]" value="2" /></td><td><a href="#">1179</a></td>
          	<td>Rem Bright Free Trial2</td>
	          <td>Two Page Campaign (Single Products) </td>
            <td>Instant Access </td>
            <td>RemBright Exlusive Offer Rebill (13) </td>
            <td><img src="images/approve.png" /></td>
            <td><a href="newcampaign.php?campaigns_id=1179">Edit</a></td>
          </tr>         
        </table>
      
      
      
      
        <div class="bottm">
Page <img src="images/arrow.png" /> <input class="btmnbs" value="1" /> <img src="images/arrowr.png" />  of 20 pages / Total 395 Records found \ Show <select class="inputdrop"><option>20</option></select> Per Page
		</div>
    </div>
    
    
    
    
    
    

			</form>
</body>
</html>
