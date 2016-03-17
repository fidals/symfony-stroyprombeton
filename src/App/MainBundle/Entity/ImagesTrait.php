<?php
namespace App\MainBundle\Entity;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

trait ImagesTrait
{
    /**
     * Ищет все файлы с названием {id}.*, возвращает первый найденный
     * Оно именно здесь, а не в репозитории, потому что используется напрямую в шаблонах.
     *
     * @return string
     */
    public function getPicturePath()
    {
        $entityImagesDirectory = __DIR__ . self::WEB_DIR_PATH . self::IMG_DIR_PATH . '/' . $this->getId() . '/';
        $fs = new Filesystem();
        if ($fs->exists($entityImagesDirectory)) {
            $finder = new Finder();
            $finder
                ->files()
                ->name('*.jpeg')
                ->name('*.png')
                ->name('*.jpg')
                ->name('*.gif')
                ->in($entityImagesDirectory);

            foreach ($finder as $file) {
                return self::IMG_DIR_PATH . '/' . $this->getId() . '/' . $file->getBasename();
            }
        }
        return self::IMG_DIR_PATH . '/' . self::EMPTY_THUMB_NAME;
    }
}