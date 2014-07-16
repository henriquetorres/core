<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace Thelia\ImportExport;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Thelia\Core\FileFormat\Formatting\FormatterData;

/**
 * Class ImportHandler
 * @package Thelia\ImportExport
 * @author Benjamin Perche <bperche@openstudio.fr>
 */
abstract class ImportHandler
{
    protected $container;

    /**
     * @param ContainerInterface $container
     *
     * Dependency injection: load the container to be able to get parameters and services
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    /**
     * @param \Thelia\Core\FileFormat\Formatting\FormatterData
     * @return string|array error messages
     *
     * The method does the import routine from a FormatterData
     */
    abstract public function retrieveFromFormatterData(FormatterData $data);

    /**
     * @return string|array
     *
     * Define all the type of import/formatters that this can handle
     * return a string if it handle a single type ( specific exports ),
     * or an array if multiple.
     *
     * Thelia types are defined in \Thelia\ImportExport\Export\ExportType
     *
     * example:
     * return array(
     *     ExportType::EXPORT_TABLE,
     *     ExportType::EXPORT_UNBOUNDED,
     * );
     */
    abstract public function getHandledType();
} 