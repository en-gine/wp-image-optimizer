<?php
/*
Plugin Name: ImageOptimizeEngine
Description: PNGとJpegをpngquantとJpegoptimで圧縮しwebpに変換
Author: (株)en-gine
Version: 0.8
*/
if (!class_exists('ImageOptimizeEngine'))
{
    class ImageOptimizeEngine
    {
        function __constructior()
        {
            add_filter('wp_handle_upload', array(
                $this,
                'image_optimize'
            ));
        }

        function image_optimize($upload)
        {
			preg_match('/.*(\/wp\-content\/uploads\/.*)/', $upload['url'], $match);

            if (count($match) > 0)
            {
                $filepath = ABSPATH . $match[1];
                switch ($upload['type'])
                {
                    case 'image/jpeg':
                        $compresse_cmd = shell_exec('jpegoptim --strip-all  -m50 ' . escapeshellarg($filepath));
                        exec($compresse_cmd);
                    break;
                    case 'image/png':
                        $compresse_cmd = shell_exec('pngquant -f --ext .png --quality=60 ' . escapeshellarg($filepath));
                        exec($compresse_cmd);
                    break;
                }
                $compresse_cmd = shell_exec('cwebp -q 90 ' . escapeshellarg($filepath) . ' -o ' . escapeshellarg($filepath) . '.webp');
                exec($compresse_cmd);
            }
            return $upload;
        }
    }
    return new ImageOptimizeEngine();
}


