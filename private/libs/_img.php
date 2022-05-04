<?php
/**
 */
class _img
{
	# (gif|jpeg|jpg|png|mp3|mp4|svg|svgz)

	# Thumbnail::make("$dir/$name", 'm');

	public static function save($files_group, $to='')
	{
		foreach ($files_group as $file_type=>$files)
		{
			foreach ($files['name'] as $key=>$name)
			{
				if ($files['error'][$key]>0) {
					continue;
				}

				$file_ext = explode('.', $name);

				$ext = array_pop($file_ext);
					
				$names[] = $name = _str::aid() . ".$ext";

				move_uploaded_file($files['tmp_name'][$key], "img/users/$name");
			}
		}
		return $names;
	}
}
