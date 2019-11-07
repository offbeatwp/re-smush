<?php

namespace OffbeatWP\ReSmush;

class AddSettings
{

    const ID = 're-smush';

    const PRIORITY = 1;

    public function title()
    {
        return __('OffbeatWP image optimiser', 'raow');
    }

    public function form()
    {
        $form = new \OffbeatWP\Form\Form();

//          ----------  Add name of service in Sitesettings  ----------

        $form->addTab('smush_tab', 'OffbeatWP Image optimiser');

//         ----------  Add the quality selection function  ----------

        $imageQualities = \OffbeatWP\Form\Fields\Select::make('smush_image_quality', 'Image Quality');

//       ----------  Load list of qualities using static function  ----------

        $imageQualities->addOptions(\OffbeatWP\ReSmush\Data\General::imageQualities());

//        ----------  Add function to select a smusher  ----------

        $imageSmusher = \OffbeatWP\Form\Fields\Select::make('smusher_used', 'Smusher');

//            ----------  Load smusher list  ----------

        $imageSmusher->addOptions(\OffbeatWP\ReSmush\Data\General::smusher());

//       ----------  Add smusher list to sitesettings  ----------

        $form->addField($imageSmusher);

//     ----------  Add TrueOrFalse statement to check if it needs to be enabled or not

        $smushEnabled = \OffbeatWP\Form\Fields\TrueFalse::make('smush_enabled', 'Optimize images');

//      ----------  Add TrueOrFalse enabled to SiteSettings  ----------

        $form->addField($smushEnabled);

//     ----------  Add quality of images in SiteSettings  ----------

        $form->addField($imageQualities);

        return $form;
    }

}