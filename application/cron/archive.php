<?php

exec('chdir /usr/www/vhosts/p/proleroinc.com/db');
exec('mysqldump moneymachine > moneymachine.'.date('Ymd').'.sql');

?>