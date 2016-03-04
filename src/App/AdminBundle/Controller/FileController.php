<?php

namespace App\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class FileController.
 */
class FileController extends Controller
{
    /**
     * @Route("/files/delete/{entity}/{id}/{file}", name="admin_file_delete")
     *
     * @param string $entity
     * @param int    $id
     * @param string $file
     *
     * @return Response
     *
     * @internal param $path
     */
    public function deleteAction($entity, $id, $file)
    {
        $fileManager = $this->get('filemanager');
        $fileManager->deleteFile($entity, $id, $file);

        return new JsonResponse(array('html' => $fileManager->renderFileList($entity, $id)));
    }

    /**
     * @Route("/files/upload", name="admin_file_upload")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function uploadAction(Request $request)
    {
        $entity = $request->request->get('entity');
        $id = $request->request->getInt('id');
        $uploadedFile = $request->files->get('file');
        $fileManager = $this->get('filemanager');

        $fileManager->uploadFile($uploadedFile, $entity, $id);

        return new JsonResponse(array('html' => $fileManager->renderFileList($entity, $id)));
    }
}
