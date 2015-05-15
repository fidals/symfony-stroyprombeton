<?php
//TODO: здесь вопрос к госу. Почему без этой строки не видит файлы снаружи?
namespace App\CatalogBundle\Extension;


use App\CatalogBundle\AppCatalogBundle;
use App\CatalogBundle\Entity\Category;
use App\CatalogBundle\Entity\CategoryClosure;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

	public static function categoryRemove($category)
	{

		$em = AppCatalogBundle::getContainer()->get('doctrine');
		$catRp = $em->getRepository('AppCatalogBundle:Category');
		$catsToDel = $catRp->getChildren($category, false, null, 'ASC', true);
		foreach ($catsToDel as $catToDel) {
			$nameFile = Category::$defaultDirForImg . $catToDel->getId() . ".";

			foreach (Category::$imgExtensions as $extension) {
				if (file_exists($nameFile . $extension)) {
					unlink($nameFile . $extension);
				}

			}
		}
	}
}
