<?php
/**
 * @package     jelix
 * @subpackage  jsoap module
 * @author      Laurent Jouanneau
 * @copyright   2009-2018 Laurent Jouanneau
 * @link        http://www.jelix.org
 * @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
 */

class jsoapModuleConfigurator extends \Jelix\Installer\Module\Configurator {

    public function getDefaultParameters() {
        return array(
            'entrypoint' => 'soap'
        );
    }

    public function askParameters() {
        $this->parameters['entrypoint'] = $this->askInformation('Enter the name of the entrypoint dedicated to your soap server', $this->parameters['entrypoint']);
    }

    public function configure() {

        $entrypoint = $this->getParameter('entrypoint');
        if (!$entrypoint) {
            $entrypoint = 'soap';
        }

        if (substr($entrypoint, -4) == '.php') {
            $epFile = $entrypoint;
            $entrypoint = substr($entrypoint, 0, -4);
        }
        else {
            $epFile = $entrypoint.'.php';
        }

        if (!file_exists(jApp::wwwPath($epFile))) {
            $this->copyFile('files/soap.php', jApp::wwwPath($epFile));
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
