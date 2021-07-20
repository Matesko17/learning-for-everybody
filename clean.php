<?php
/**
 * utilita na promazavani temp cache vytvorene php
 *
 * @author  geniv
 */

// globalni adresare
removeContent('temp/cache');
removeContent('temp/proxies');

/**
 * mazani souboru a adresaru
 * @param $path
 * @param null $filter
 */
function removeContent($path, $filter = null)
{
    if (file_exists($path)) {
        $items = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
        foreach ($items as $item) {
            $file = $item->getFilename();
            if ($file != '..') {
                if ($item->isFile()) {  // detekce souboru

                    if ($filter) {
                        if (in_array($item->getExtension(), $filter) && unlink($item->getPathname())) {
                            echo 'smazan soubor: ' . $item->getPathname() . ' podle filtru: ' . $item->getExtension() . '<br />';
                        }
                    } else {
                        if (unlink($item->getPathname())) {
                            echo 'smazan soubor: ' . $item->getPathname() . '<br />';
                        }
                    }
                }
                if ($item->isDir()) {
                    if ($path != $item->getPath() && @rmdir($item->getPath())) { // detekce adresaru
                        echo 'smazan adresar: ' . $item->getPath() . '<br />';
                    }
                }
            }
        }
    }
}
