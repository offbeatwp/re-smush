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
        $form->addTab('smush_tab', 'OffbeatWP Image optimiser');

        $imageQualities = \OffbeatWP\Form\Fields\Select::make('smush_image_quality', 'Image Quality');

        $imageQualities->addOptions(\OffbeatWP\ReSmush\Data\General::imageQualities());

        $smushEnabled = \OffbeatWP\Form\Fields\TrueFalse::make('smush_enabled', 'Optimize images');

        $form->addField($smushEnabled);
        $form->addField($imageQualities);

        return $form;
    }

}