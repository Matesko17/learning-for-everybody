<?php
namespace App\Extensions;

use Kdyby\Translation\DI\ITranslationProvider;
use Nette\DI\CompilerExtension;
use Nette\Utils\Finder;

class TranslationProviderExtension extends CompilerExtension implements ITranslationProvider
{

    /**
     * Return array of directories, that contain resources for translator
     * @return string[]
     */
    public function getTranslationResources()
    {
        $config = $this->getConfig();
        $folders = [];

        if(isset($config['dir'])) {
            foreach($config['dir'] as $dir) {
                foreach(Finder::findDirectories('locale')->from($dir)->exclude($config['excluded']) as $folder){
                    $folders[] = $folder->getRealPath();
                }
            }
        }

        return $folders;
    }

}
