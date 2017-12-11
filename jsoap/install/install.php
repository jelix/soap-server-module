<?php
/**
* @package     jelix
* @subpackage  jsoap module
* @author      Laurent Jouanneau
* @contributor
* @copyright   2009-2017 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class jsoapModuleInstaller extends \Jelix\Installer\ModuleInstaller{

    function installEntrypoint(\Jelix\Installer\EntryPoint $entryPoint) {

        // configure the entry point
        $entrypoint = $this->getParameter('entrypoint');

        if (!$entrypoint) {
            $entrypoint = 'soap';
        }
        if (!file_exists(jApp::wwwPath($entrypoint.'.php'))) {
            $this->copyFile('files/soap.php', jApp::wwwPath($entrypoint.'.php'));
        }

        // setup the configuration
        if (!file_exists(jApp::appConfigPath($entrypoint.'/config.ini.php'))) {
            $this->copyFile('files/config.ini.php', jApp::appConfigPath($entrypoint.'/config.ini.php'));
        }
        
        if ($this->getConfigIni()->getValue('soap', 'responses') === null) {
            $this->getConfigIni()->setValue('soap', "jsoap~jResponseSoap", "responses");
        }

        $this->globalSetup->declareNewEntryPoint($entrypoint, 'soap', $entrypoint.'/config.ini.php');
    }
}