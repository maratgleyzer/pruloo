<?

function gd_img ($image_data , $width = 0 , $height = 0 , $mime = "image/jpeg", $filename = false, $maintain_aspect = true)
{
	if (empty($image_data)) $image_data = file_get_contents(SYS_PATH . 'images/noimage.jpg');

	if ($mime == '' || $mime == 'image/pjpeg' || $mime == 'image/jpg' || $mime == 'image/jfif') $mime = 'image/jpeg';

	if ($mime == "image/jpeg" || $mime == "image/x-png" || $mime == "image/png" || $mime == "image/gif") {
	//	Temporarily rebuild the image from a string

		$tmp_img = imagecreatefromstring($image_data) or die($php_errormsg);
		$width_orig = imagesx($tmp_img);
		$height_orig = imagesy($tmp_img);

		// Set a maximum height and width
		$width = intval($width);
		$height = intval($height);

    if ($maintain_aspect && $width > 0) $height = 0;
    elseif ($maintain_aspect && $height > 0) $width = 0;

		if ($width != 0 && $height != 0) {
			//do nothing
		} else {
			if ($width == 0 && $height != 0) $width = $height;
			elseif ($width != 0 && $height == 0) $height = $width;
			elseif ($width == 0 && $height == 0) {
				$width = $width_orig;
				$height = $height_orig;
			}

			if ($width && ($width_orig < $height_orig)) {
				$width = ($height / $height_orig) * $width_orig;
			} else {
				$height = ($width / $width_orig) * $height_orig;
			}
		}

		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		$image = imagecreatefromstring( $image_data );
		imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
		ob_start();
		if ($filename !== false && !empty($filename)) {
			return imagejpeg($image_p, $filename, 100);
		} else {
			imagejpeg($image_p, null, 100);
		//	imagestring($image_p, null, 100);
			$image_data = ob_get_contents();
			$image_data_length = ob_get_length();
			ob_end_clean();
			$image_arr["mime"] = $mime;
			$image_arr["image"] = addslashes ( $image_data );

			return $image_arr;
		}
	} else {
		echo "Bad mim-type: '$mime' !";
		return false;
	}
}