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
        $form->addTab('re_smush_tab', 'OffbeatWP Image optimiser');

        $imageQualities = \OffbeatWP\Form\Fields\Select::make('re_smush_image_quality', 'Image Quality');

        $imageQualities->addOptions(\OffbeatWP\ReSmush\Data\General::imageQualities());

        $reSmushEnabled = \OffbeatWP\Form\Fields\TrueFalse::make('re_smush_enabled', 'optimize images');

        $form->addField($reSmushEnabled);
        $form->addField($imageQualities);

        return $form;
    }

}