<?php
	header("Pragma: no-cache");
	header("Cache: no-cahce");
?>
<HTML>
<BODY bgcolor="#FFFFFF">

<?php
//include charts.php to access the InsertChart function
include "charts.php";

echo InsertChart ( "charts.swf", "charts_library", "sample.php", 400, 250 );
echo '<br /> <br /> <br />';
echo InsertChart ( "charts.swf", "charts_library", "sample2.php", 400, 250 );

?>

</BODY>
</HTML>

