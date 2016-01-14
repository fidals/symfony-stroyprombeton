<?php
//TODO: здесь вопрос к госу. Почему без этой строки не видит файлы снаружи?
namespace App\MainBundle\Extension;

class Utils
{
	public static function rmDirFull($dir)
	{
		if (!is_dir($dir)) return true;

		$files = array_diff(scandir($dir), array('.', '..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? Utils::rmDirFull("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}
}
