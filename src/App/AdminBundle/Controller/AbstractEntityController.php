<?php

namespace App\AdminBundle\Controller;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractEntityController.
 *
 * Контроллер абстрактной сущности:
 * - инкапсулирует методы по подготовке респонсов
 * - определяет контракты для конкретных сущностей
 * - использует трейты-хелперы
 */
abstract class AbstractEntityController extends Controller
{
    /*
     * Используемые трейты:
     * - PaginateTrait для работы с пагинацией и подготовки списков сущностей
     * - FilterTrait для работы с фильтрами, определенными в контроллерах.
     */
    use PaginateTrait, FilterTrait;

    /**
     * Метод для создания пустого объекта новой сущности.
     *
     * @return Entity объект конкретной Entity
     */
    abstract protected function createEntity();

    /**
     * Экшн, определяемый в конкретных контроллерах.
     * Использует метод prepareList и отдает шаблон, определенный в константе конкретного контроллера.
     *
     * @param Request $request - реквест, приходящий в Контроллер
     * @param int     $page    - страница пагинации.
     *
     * @return Response с отрендеренным шаблоном.
     */
    abstract public function listAction($page, Request $request);

    /**
     * Экшн, определяемый в конкретных контроллерах.
     * Использует метод prepareEdit и отдает шаблон, определенный в константе конкретного контроллера.
     *
     * @param Request $request - реквест, приходящий в Контроллер
     * @param int     $id      - опциональный параметр id. Если он указан, мы редактируем уже существующую Entity.
     *
     * @return Response с отрендеренным шаблоном.
     */
    abstract public function editAction(Request $request, $id);

    /**
     * Рендерит шаблон-список.
     * Вызывается конкретными экшенами в контроллерах-подклассах.
     *
     * @param int     $page    - страница списка.
     * @param Request $request
     *
     * @return Response отрендеренный шаблон-список
     */
    protected function renderListTemplate($page, Request $request)
    {
        $paginationQuery = $this->getInitialQuery();

        $appliedFilters = array();

        if ($this->isFiltersApplied($request)) {
            $GETParameters = $request->query->all();
            $appliedFilters = $this->getAppliedFilters($GETParameters);
            $paginationQuery = $this->applyFilters($paginationQuery, $appliedFilters);
        }

        $pagination = $this->paginate($page, $paginationQuery);
        $parents = $this->getEntityParents();

        $listTemplateParameters = array(
            'page' => $pagination,
            'filters' => $appliedFilters,
            'parents' => $parents, );

        return $this->render(static::LIST_TEMPLATE, $listTemplateParameters);
    }

    /**
     * Рендерит шаблон для редактирования/создания Entity.
     * Вызывается конкретными экшенами в контроллерах-подклассах.
     *
     * @param Request  $request
     * @param int|null $id
     *
     * @return RedirectResponse|Response
     */
    protected function renderEditEntityTemplate(Request $request, $id = null)
    {
        $entity = $this->getEditedEntity($id);

        $form = $this->createForm(static::ENTITY_FORM, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->saveEntity($entity);

            return $this->redirectToRoute('admin_'.static::ENTITY_NAME.'_edit', array(
                'id' => $entity->getId()
            ));
        }

        $templateData = array('form' => $form->createView());

        if ($entity->getId()) {
            $templateData['fileList'] = $this->renderEntityFilesTemplate($entity->getId());
        }

        return $this->render(
            static::EDIT_TEMPLATE, $templateData
        );
    }

    /**
     * Удаляет объект сущности из БД.
     *
     * @param Entity $entity
     */
    protected function deleteEntity($entity)
    {
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->remove($entity);
        $entityManger->flush();
    }

    /**
     * Возвращает начальный DQL-запрос для пагинации и применения фильтров.
     *
     * @return mixed
     */
    private function getInitialQuery()
    {
        $repository = $this->getDoctrine()->getRepository(static::ENTITY);

        return $repository->createQueryBuilder('p')->where('p.id > 0');
    }

    /**
     * Возвращает массив всех родительских сущностей, если у редактируемой есть поле PARENT_ENTITY.
     *
     * @return array
     */
    private function getEntityParents()
    {
        $parents = array();

        if (defined('static::PARENT_ENTITY')) {
            $parents = $this->getDoctrine()->getRepository(static::PARENT_ENTITY)->findAll();
        }

        return $parents;
    }

    /**
     * Получает объект редактируемой сущности, либо создает новый объект.
     *
     * @param int|null $id
     *
     * @return Entity
     */
    private function getEditedEntity($id = null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entity = $id ? $entityManager->getRepository(static::ENTITY)->find($id) : $this->createEntity();

        return $entity;
    }

    /**
     * Получает отрендеренный шаблон с файлами сущности.
     *
     * @param int $entityId
     *
     * @return array
     */
    private function renderEntityFilesTemplate($entityId)
    {
        return $this->get('filemanager')->renderFileList(static::ENTITY_NAME, $entityId);
    }

    /**
     * Сохраняет сущность в базу.
     *
     * @param Entity $entity
     */
    private function saveEntity($entity)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($entity);
        $entityManager->flush();
    }
}
