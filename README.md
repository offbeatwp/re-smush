# Re-smush service for offbeatWP

This is a re-smush service package for [offbeatWP](https://github.com/offbeatwp). After loading the service into offbeatWP it's going to send images to the [resmush.it](https://resmush.it) API every time an image is uploaded. When the API has smushed the image, the service will fetch the image and replace the original by it.

### By default
- The images are optimized to 90% of the quality of the original
- If the API is offline or giving an error code you can find this error in the debug log [(if enabled)](https://wordpress.org/support/article/debugging-in-wordpress/).
- If the API is offline it will ignore the call and just only upload the images
- If the size of the image is > 5MB than the image will be ignored

![example](https://github.com/offbeatwp/re-smush/blob/master/example.png)

### Workflow

After uploading, a copy of the original file is made. The thumbnails are created after this. These are optimized based on the original (non-optimized) image. The original image is also optimized in this hook. The original file can be found in the upload folder 'filename.extension. original ' without compression. If you generate thumbnails again, it will use the (non-optimized) image. 

## Installation
Install the package using [Composer](https://getcomposer.org/) (**First you need to go to the OffbeatWP theme folder**)

```
composer require offbeatwp/re-smush
```

Then you need to add the re-smush as service. You can do this by adding the service in the `config/service.php` file.
```
OffbeatWP\ReSmush\Service::class,
```

