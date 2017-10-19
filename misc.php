<?

function redirect($template = false)
{
	if (!$template || !is_dir(SITE_PATH . "_/$template/")) {
  	$templates = scandir(SITE_PATH . "_/");
    $template = $templates[array_rand($templates)];
  }
  
  header("Location: /_/$template/");
  exit; 
}