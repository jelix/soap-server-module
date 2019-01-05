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

    public function configure(\Jelix\Installer\Module\API\ConfigurationHelpers $helpers) {

        $this->parameters['entrypoint'] = $helpers->cli()
            ->askInformation('Enter the name of the entrypoint dedicated to your soap server',
                $this->parameters['entrypoint']);

        $entrypoint = $this->getParameter('entrypoint');
        if (!$entrypoint) {
            $entrypoint = 'soap';
        }

        $helpers->createEntryPoint('files/soap.php', $entrypoint,
                                    $entrypoint.'/config.ini.php',
                                    'soap', 'files/config.ini.php');

        if ($helpers->getConfigIni()->getValue('soap', 'responses') === null) {
            $helpers->getConfigIni()->setValue('soap', "jsoap~jResponseSoap", "responses");
        }
    }
}
