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

namespace Thelia\Form\Brand;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\True;
use Thelia\Core\Translation\Translator;
use Thelia\Form\BaseForm;
use Thelia\Model\Lang;

/**
 * Class BrandCreationForm
 * @package Thelia\Form\Brand
 * @author  Franck Allimant <franck@cqfdev.fr>
 */
class BrandCreationForm extends BaseForm
{
    protected function buildForm()
    {
        $this->formBuilder
            // Brand title
            ->add(
                'title',
                'text',
                [
                    'constraints' => [ new NotBlank() ],
                    'required'    => true,
                    'label'       => Translator::getInstance()->trans('Brand name'),
                    'label_attr'  => [
                        'for'         => 'title',
                        'help'        => Translator::getInstance()->trans(
                            'Enter here the brand name in the default language (%title%)',
                            [ '%title%' => Lang::getDefaultLanguage()->getTitle()]
                        ),
                    ],
                    'attr' => [
                        'placeholder' => Translator::getInstance()->trans('The brand name or title'),
                    ]
                ]
            )
            // Current locale
            ->add(
                'locale',
                'hidden',
                [
                    'constraints' => [ new NotBlank() ],
                    'required'    => true,
                ]
            )
            // Is this brand online ?
            ->add(
                'visible',
                'checkbox',
                [
                    'constraints' => [ ],
                    'required'    => true,
                    'label'       => Translator::getInstance()->trans('This brand is online'),
                    'label_attr' => [
                        'for' => 'visible_create'
                    ]
                ]
            );
    }

    public function getName()
    {
        return 'thelia_brand_creation';
    }
}
