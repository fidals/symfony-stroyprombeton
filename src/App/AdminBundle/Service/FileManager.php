<?php

namespace App\AdminBundle\Service;

use Exception;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Twig_Environment;
use function Functional\map;

/**
 * Сервис для работы с файлами.
 * Содержит методы по загрузке/удалению/получению файлов сущностей из админки.
 * Можно получить как $this->get('filemanager');.
 */
class FileManager
{
    const RELATIVE_PATH_TO_ASSETS = '/../web/';
    const FILES_TEMPLATE = 'AppAdminBundle:Admin:files.html.twig';
    const SUPPORTED_FILE_TYPES = array('jpeg', 'jpg', 'png', 'rar', 'zip', 'doc', 'docx', 'pdf', 'xls', 'xlsx');
    const MAXIMUM_FILE_SIZE = 25000000;

    /**
     * @var Finder инстанс FinderComponent для поиска файлов и папок в файловой системе
     */
    private $finder;

    /**
     * @var Filesystem инстанс FilesystemComponent для удобной работы с файловой системой
     */
    private $fileSystem;

    /**
     * @var string абсолютный путь до директории web
     */
    private $webDirectory;

    /**
     * @var Twig_Environment инстанс TwigEnvironment для рендеринга шаблона со спиком файлов
     */
    private $twig;

    /**
     * FileManager constructor.
     *
     * Параметры внедряются через DIC.
     *
     * @param Finder           $finder
     * @param Filesystem       $fs
     * @param Twig_Environment $twig
     * @param string           $rootDirectory
     */
    public function __construct(Finder $finder, Filesystem $fs, Twig_Environment $twig, $rootDirectory)
    {
        $this->finder = $finder;
        $this->fileSystem = $fs;
        $this->twig = $twig;
        $this->webDirectory = realpath($rootDirectory.self::RELATIVE_PATH_TO_ASSETS);
    }

    /**
     * Метод поиска файлов в директории.
     * Папка определяется по entity и id (напр. category/666).
     * Если директории не существует, она создается.
     *
     * @param $entity - сущность
     * @param $id - id сущности для поиска в соотв. директории
     *
     * @return array - массив и информацией о файлах в директории
     */
    public function find($entity, $id)
    {
        $path = $this->getAbsolutePath($entity, $id);

        try {
            $files = $this->finder->files()->in($path);
        } catch (Exception $e) {
            $this->fileSystem->mkdir($path);
            $files = null;
        }

        return $this->constructFilesArray($files);
    }

    /**
     * Метод-обертка над операцией удаления файлов.
     * Для безопасности он принимает три параметра (а не абсолютный путь):.
     *
     * @param string $entity
     * @param string $id
     * @param string $fileName
     */
    public function deleteFile($entity, $id, $fileName)
    {
        $path = $this->getAbsolutePath($entity, $id, $fileName);
        $this->fileSystem->remove($path);
    }

    /**
     * Метод-обертка над операцией загрузки файла на сервер
     *
     * @param UploadedFile $file
     * @param string       $entity
     * @param int          $id
     */
    public function uploadFile(UploadedFile $file, $entity, $id)
    {
        if (!$this->isFileValid($file)) {
            throw new \InvalidArgumentException('Файл не может быть загружен');
        }

        $path = $this->getAbsolutePath($entity, $id);
        $file->move($path, $file->getClientOriginalName());
    }

    /**
     * Рендерит шаблон со спиком файлов сущности.
     *
     * @param string $entity
     * @param int    $id
     *
     * @return string
     */
    public function renderFileList($entity, $id)
    {
        return $this->twig->render(self::FILES_TEMPLATE, array(
            'entity' => $entity,
            'id' => $id,
            'files' => $this->find($entity, $id), ));
    }

    /**
     * Метод-костыль для получения пути до файла.
     * SplFileInfo не содержит сеттеров, поэтому отдельная функция, создающая отдельный массив.
     *
     * @param array $files - изначальный массив с элементами SplFileInfo
     *
     * @return array - получившееся отображение с относительными путями и изначальными SplFileInfo
     */
    private function constructFilesArray($files)
    {
        $filesArray = array();

        if ($files) {
            $filesArray = map($files, function ($file) {
                return array(
                    'path' => str_replace($this->webDirectory, '', $file->getRealPath()),
                    'info' => $file,
                );
            });
        }

        return $filesArray;
    }

    /**
     * Возвращает абсолютный путь до папки/файла по:.
     *
     * @param $entity - название сущности
     * @param $id - id сущности
     * @param string|null $fileName - опциональное имя файла
     *
     * @return string - абсолютный путь до папки/файла
     */
    private function getAbsolutePath($entity, $id, $fileName = null)
    {
        $path = $this->webDirectory.'/assets/'.$entity.'/'.$id;

        if ($fileName) {
            $path .= '/'.$fileName;
        }

        return $path;
    }

    /**
     * @param UploadedFile $file
     *
     * @return bool
     */
    private function isFileValid(UploadedFile $file)
    {
        return in_array($file->guessExtension(), self::SUPPORTED_FILE_TYPES) &&
            $file->getClientSize() <= self::MAXIMUM_FILE_SIZE;
    }
}
