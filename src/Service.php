<?php

namespace OffbeatWP\ReSmush;

use OffbeatWP\ReSmush\Helpers\General;
use OffbeatWP\Services\AbstractService;
use OffbeatWP\ReSmush\Helpers\SmushImage;
use OffbeatWP\Contracts\SiteSettings;

class Service extends AbstractService
{

    protected $settings;

    public function register(SiteSettings $settings)
    {
        $settings->addPage(AddSettings::class);

        add_action('init', [$this, 'init']);
    }

    public function init()
    {
        // --------------------- Add settings page ---------------------

        // --------------------- WP Filters ---------------------

        add_filter('delete_attachment', [$this, 'deleteOriginal']);
        add_filter('image_size_names_choose', [$this, 'addImageSize']);

        add_filter('wp_handle_upload', [$this, 'handleUpload'], 10, 2);

        if (setting('re_smush_enabled_thumbnails') == true) {
            add_filter('wp_generate_attachment_metadata', [$this, 'handleThumbnails'], 10, 2);
        }

        // --------------------- Set default quality ---------------------

        if (setting('re_smush_image_quality') != null && setting('re_smush_image_quality') != '') {
            $this->defaultQuality = setting('re_smush_image_quality');
        } else {
            $this->defaultQuality = 90;
        }
    }

    public function handleUpload($image)
    {
        if (General::hasAllowedType($image['type']) == true && General::hasAllowedSize($image['file']) == true) {
            $file = $image['file'];
            $newfile = $image['file'] . '.orginal';

            if (!copy($file, $newfile)) {
                error_log("failed to copy $file...\n");
            }

//            orginele wordt nu niet kleiner gemaakt

            $this->smushOrginal($image);
        }

        return $image;
    }

    public function handleThumbnails($image, $key)
    {
        $this->restoreOriginal($image);

        $this->smushDemention($image, $key, 'thumbnail');
        $this->smushDemention($image, $key, 'medium_large');
        $this->smushDemention($image, $key, 'medium');
        $this->smushDemention($image, $key, 'large');
        $this->smushDemention($image, $key, 'hero');
        $this->smushOrginal($image);

        return $image;
    }

    public function restoreOriginal($image)
    {
        $file = wp_upload_dir()['basedir'] . '/' . $image['file'] . '.orginal';
        $newfile = wp_upload_dir()['basedir'] . '/' . $image['file'];

        if (!copy($file, $newfile)) {
            error_log("failed to copy $file...\n");
        }
    }

    public function smushOrginal($image)
    {
        $apiCall = new SmushImage($image['type'], $image['file']);
        $apiCall->setQuality($this->defaultQuality);
        $apiCall->execute();
    }

    protected function smushDemention($image, $key, $size)
    {
        $apiCall = new SmushImage(get_post_mime_type($key), $this->getFile($image, $size));
        $apiCall->setQuality($this->defaultQuality);
        $apiCall->execute();
    }

    protected function getBasePath($image)
    {
        return substr($image["file"], 0, strrpos($image["file"], '/'));
    }

    protected function getFile($image, $size = 'thumbnail')
    {
        return wp_upload_dir()['basedir'] . '/' . $this->getBasePath($image) . '/' . $image["sizes"][$size]["file"];
    }

    public function deleteOriginal($postId)
    {

        $image = wp_get_attachment_metadata($postId);

        if (General::hasAllowedType($image['type']) == true && General::hasAllowedSize($image['file']) == true) {
            unlink($image['file']);
        }

        return $postId;
    }
}