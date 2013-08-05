<?php

namespace App\MainBundle\Extension;

class FileExistExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            'fileExists' => new \Twig_Function_Method($this, 'fileExists'),
            'fileCount' => new \Twig_Function_Method($this, 'fileCount'),
        );
    }

    public function fileExists($path)
    {
        return file_exists($path);
    }

    public function fileCount($dirPath)
    {
        return count(glob($dirPath));
    }

    public function getName()
    {
        return 'file_exist_extension';
    }
}