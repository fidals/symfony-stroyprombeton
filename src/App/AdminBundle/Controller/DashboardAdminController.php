<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Extension\TableGear;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DashboardAdminController - контроллер главной страницы Админки.
 */
class DashboardAdminController extends Controller
{
    /**
     * @Route("/", name="admin_index")
     *
     * Action для главной страницы Админки - экрана Dashboard.
     *
     * @return Response
     */
    public function indexAction()
    {
        return $this->render('AppAdminBundle:Admin:layout.html.twig');
    }

    /**
     * @Route("/editproducts", name="admin_table")
     *
     * Action для страницы Табличного редактора продуктов - TableGear.
     *
     * @return Response
     */
    public function tableEditorAction()
    {
        $tableGear = new TableGear($this->container);

        return $this->render('AppAdminBundle:Admin:tablegear.html.twig', array(
            'tablegear_content' => $tableGear->getContent(),
        ));
    }
}
