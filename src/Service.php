<?php

namespace OffbeatWP\ReSmush;

use OffbeatWP\ReSmush\Helpers\General;
use OffbeatWP\Services\AbstractService;
use OffbeatWP\ReSmush\Helpers\Smushers\Resmush;
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

        if (setting('re_smush_enabled') == true) {
            add_filter('delete_attachment', [$this, 'deleteOriginal']);
            add_filter('wp_handle_upload', [$this, 'handleUpload'], 10, 2);
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
            $newfile = $image['file'] . '.original';

            if (!copy($file, $newfile)) {
                error_log("failed to copy $file...\n");
            }
        }

        return $image;
    }

    public function handleThumbnails($image, $key)
    {

        $this->restoreOriginal($image);

        $this->smushDimensions($image, $key, 'thumbnail');
        $this->smushDimensions($image, $key, 'medium_large');
        $this->smushDimensions($image, $key, 'medium');
        $this->smushDimensions($image, $key, 'large');
        $this->smushDimensions($image, $key, 'hero');

        $this->smushOriginal($image, $key);

        return $image;
    }

    public function restoreOriginal($image)
    {
        $file = $this->getBaseDir() . $image['file'] . '.original';
        $newfile = $this->getBaseDir() . $image['file'];

        if (!copy($file, $newfile)) {
            error_log("failed to copy $file...\n");
        }
    }

    public function smushOriginal($image, $key)
    {
        $type = get_post_mime_type($key);

        $apiCall = new Resmush($type, $this->getBaseDir() . $image['file']);
        $apiCall->setQuality($this->defaultQuality);
        $apiCall->execute();
    }

    public function deleteOriginal($postId)
    {
        $image = wp_get_attachment_metadata($postId);
        $imageType = get_post_mime_type($postId);

        if (General::hasAllowedType($imageType) == true) {
            if (file_exists($this->getOriginal($image))) {
                unlink($this->getOriginal($image));
            }
        }

        return $postId;
    }

    public function smushDimensions($image, $key, $size)
    {
        if ($this->getFile($image, $size)) {
            $apiCall = new Resmush(get_post_mime_type($key), $this->getFile($image, $size));
            $apiCall->setQuality($this->defaultQuality);
            $apiCall->execute();
        }
    }

    public function getBasePath($image)
    {
        return substr($image["file"], 0, strrpos($image["file"], '/'));
    }

    public function getFile($image, $size = 'thumbnail')
    {
        if (empty($image["sizes"][$size]["file"])) {
            return false;
        }

        return $this->getBaseDir() . $this->getBasePath($image) . '/' . $image["sizes"][$size]["file"];
    }

    public function getOriginal($image)
    {
        return $this->getBaseDir() . $image['file'] . '.original';
    }

    public function getBaseDir()
    {
        return wp_upload_dir()['basedir'] . '/';
    }
}