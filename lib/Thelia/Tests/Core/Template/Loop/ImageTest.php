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

namespace Thelia\Tests\Core\Template\Loop;

use Thelia\Model\ImageQuery;
use Thelia\Tests\Core\Template\Element\BaseLoopTestor;

use Thelia\Core\Template\Loop\Image;
use Thelia\Model\ProductImageQuery;
use Thelia\Model\CategoryImageQuery;
use Thelia\Model\ContentImageQuery;
use Thelia\Model\FolderImageQuery;

/**
 *
 * @author Etienne Roudeix <eroudeix@openstudio.fr>
 *
 */
class ImageTest extends BaseLoopTestor
{
    public function getTestedClassName()
    {
        return 'Thelia\Core\Template\Loop\Image';
    }

    public function getTestedInstance()
    {
        return new Image($this->container);
    }

    public function getMandatoryArguments()
    {
        return array('source' => 'product', 'id' => 1);
    }

    public function testSearchByProductId()
    {
        $image = ProductImageQuery::create()->findOne();

        $this->baseTestSearchById($image->getId());
    }

    public function testSearchByFolderId()
    {
        $image = FolderImageQuery::create()->findOne();

        $this->baseTestSearchById($image->getId());
    }

    public function testSearchByContentId()
    {
        $image = ContentImageQuery::create()->findOne();

        $this->baseTestSearchById($image->getId());
    }

    public function testSearchByCategoryId()
    {
        $image = CategoryImageQuery::create()->findOne();

        $this->baseTestSearchById($image->getId());
    }

    public function testSearchLimit()
    {
        $this->baseTestSearchWithLimit(1);
    }
}