<?php
/*************************************************************************************/
/*                                                                                   */
/*      Thelia	                                                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : info@thelia.net                                                      */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      This program is free software; you can redistribute it and/or modify         */
/*      it under the terms of the GNU General Public License as published by         */
/*      the Free Software Foundation; either version 3 of the License                */
/*                                                                                   */
/*      This program is distributed in the hope that it will be useful,              */
/*      but WITHOUT ANY WARRANTY; without even the implied warranty of               */
/*      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the                */
/*      GNU General Public License for more details.                                 */
/*                                                                                   */
/*      You should have received a copy of the GNU General Public License            */
/*	    along with this program. If not, see <http://www.gnu.org/licenses/>.         */
/*                                                                                   */
/*************************************************************************************/

namespace Thelia\Controller\Admin;

use Thelia\Core\Event\CategoryDeleteEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\Event\CategoryUpdateEvent;
use Thelia\Core\Event\CategoryCreateEvent;
use Thelia\Model\CategoryQuery;
use Thelia\Form\CategoryModificationForm;
use Thelia\Form\CategoryCreationForm;
use Thelia\Core\Event\UpdatePositionEvent;
use Thelia\Core\Event\CategoryToggleVisibilityEvent;

/**
 * Manages categories
 *
 * @author Franck Allimant <franck@cqfdev.fr>
 */
class CategoryController extends AbstractCrudController
{
    public function __construct() {
        parent::__construct(
            'category',
            'manual',
            'category_order',

            'admin.categories.default',
            'admin.categories.create',
            'admin.categories.update',
            'admin.categories.delete',

            TheliaEvents::CATEGORY_CREATE,
            TheliaEvents::CATEGORY_UPDATE,
            TheliaEvents::CATEGORY_DELETE,
            TheliaEvents::CATEGORY_TOGGLE_VISIBILITY,
            TheliaEvents::CATEGORY_UPDATE_POSITION
        );
    }

    protected function getCreationForm() {
        return new CategoryCreationForm($this->getRequest());
    }

    protected function getUpdateForm() {
        return new CategoryModificationForm($this->getRequest());
    }

    protected function getCreationEvent($formData) {
        $createEvent = new CategoryCreateEvent();

        $createEvent
            ->setTitle($formData['title'])
            ->setLocale($formData["locale"])
            ->setParent($formData['parent'])
            ->setVisible($formData['visible'])
        ;

        return $createEvent;
    }

    protected function getUpdateEvent($formData) {
        $changeEvent = new CategoryUpdateEvent($formData['id']);

        // Create and dispatch the change event
        $changeEvent
            ->setLocale($formData['locale'])
            ->setTitle($formData['title'])
            ->setChapo($formData['chapo'])
            ->setDescription($formData['description'])
            ->setPostscriptum($formData['postscriptum'])
            ->setVisible($formData['visible'])
            ->setUrl($formData['url'])
            ->setParent($formData['parent'])
        ;

        return $changeEvent;
    }

    protected function createUpdatePositionEvent($positionChangeMode, $positionValue) {

        return new UpdatePositionEvent(
                $this->getRequest()->get('category_id', null),
                $positionChangeMode,
                $positionValue
        );
    }

    protected function getDeleteEvent() {
        return new CategoryDeleteEvent($this->getRequest()->get('category_id', 0));
    }

    protected function eventContainsObject($event) {
        return $event->hasCategory();
    }

    protected function hydrateObjectForm($object) {

        // Prepare the data that will hydrate the form
        $data = array(
            'id'           => $object->getId(),
            'locale'       => $object->getLocale(),
            'title'        => $object->getTitle(),
            'chapo'        => $object->getChapo(),
            'description'  => $object->getDescription(),
            'postscriptum' => $object->getPostscriptum(),
            'visible'      => $object->getVisible(),
            'url'          => $object->getUrl($this->getCurrentEditionLocale()),
            'parent'       => $object->getParent()
        );

        // Setup the object form
        return new CategoryModificationForm($this->getRequest(), "form", $data);
    }

    protected function getObjectFromEvent($event) {
        return $event->hasCategory() ? $event->getCategory() : null;
    }

    protected function getExistingObject() {
        return CategoryQuery::create()
        ->joinWithI18n($this->getCurrentEditionLocale())
        ->findOneById($this->getRequest()->get('category_id', 0));
    }

    protected function getObjectLabel($object) {
        return $object->getTitle();
    }

    protected function getObjectId($object) {
        return $object->getId();
    }

    protected function renderListTemplate($currentOrder) {
        return $this->render('categories',
                array(
                    'category_order' => $currentOrder,
                    'category_id' => $this->getRequest()->get('category_id', 0)
                )
         );
    }

    protected function renderEditionTemplate() {
        return $this->render('category-edit', array('category_id' => $this->getRequest()->get('category_id', 0)));
    }

    protected function redirectToEditionTemplate() {
        $this->redirectToRoute(
                "admin.categories.update",
                array('category_id' => $this->getRequest()->get('category_id', 0))
        );
    }

    protected function redirectToListTemplate() {
        $this->redirectToRoute(
                'admin.categories.default',
                array('category_id' => $this->getRequest()->get('category_id', 0))
                );
    }

    /**
     * Online status toggle category
     */
    public function setToggleVisibilityAction()
    {
        // Check current user authorization
        if (null !== $response = $this->checkAuth("admin.categories.update")) return $response;

        $event = new CategoryToggleVisibilityEvent($this->getExistingObject());

        try {
            $this->dispatch(TheliaEvents::CATEGORY_TOGGLE_VISIBILITY, $event);
        } catch (\Exception $ex) {
            // Any error
            return $this->errorPage($ex);
        }

        // Ajax response -> no action
        return $this->nullResponse();
    }

    protected function performAdditionalDeleteAction($deleteEvent)
    {
        // Redirect to parent category list
        $this->redirectToRoute(
                'admin.categories.default',
                array('category_id' => $deleteEvent->getCategory()->getParent())
        );
    }

    protected function performAdditionalUpdatePositionAction($event)
    {

        $category = CategoryQuery::create()->findPk($event->getObjectId());

        if ($category != null) {
            // Redirect to parent category list
            $this->redirectToRoute(
                    'admin.categories.default',
                    array('category_id' => $category->getParent())
            );
        }

        return null;
    }
}
