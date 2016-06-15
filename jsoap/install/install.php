<?php
/**
* @package     jelix
* @subpackage  jsoap module
* @author      Laurent Jouanneau
* @contributor
* @copyright   2009-2015 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class jsoapModuleInstaller extends jInstallerModule {

    function install() {

        // configure the entry point
        $entrypoint = $this->getParameter('entrypoint');

        if (!$entrypoint) {
            $entrypoint = 'soap';
        }
        if (!file_exists(jApp::wwwPath($entrypoint.'.php'))) {
            $this->copyFile('files/soap.php', jApp::wwwPath($entrypoint.'.php'));
        }

        // setup the configuration
        if (!file_exists(jApp::configPath($entrypoint.'/config.ini.php'))) {
            $this->copyFile('files/config.ini.php', jApp::configPath($entrypoint.'/config.ini.php'));
        }
        
        if ($this->entryPoint->getMainConfigIni()->getValue('soap', 'responses') === null) {
            $this->entryPoint->getMainConfigIni()->setValue('soap', "jsoap~jResponseSoap", "responses");
        }

        $this->declareNewEntryPoint($entrypoint, 'soap', $entrypoint.'/config.ini.php');
    }
}