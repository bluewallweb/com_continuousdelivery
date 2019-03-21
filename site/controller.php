<?php
  /**
   * This file defines the controller for the Continuous Delivery component.
   *
   * @copyright  Copyright 2016 Clay Freeman. All rights reserved.
   * @license    GNU Lesser General Public License v3 (LGPL-3.0).
   */

  use Joomla\CMS\Factory;
  use Joomla\CMS\Filesystem\File;
  use Joomla\CMS\Installer\Installer;
  use Joomla\CMS\Installer\InstallerHelper;
  use Joomla\CMS\MVC\Controller\BaseController;

  /**
   * This class serves as the primary controller for the Continuous Delivery
   * Joomla component.
   */
  class ContinuousDeliveryController extends BaseController {
    /**
     * A reference to Joomla's application object.
     *
     * @var  Joomla\CMS\Application\CMSApplication
     */
    protected $app    = null;

    /**
     * Holds various configuration values for Joomla.
     *
     * @var  Registry
     */
    protected $config = null;

    /**
     * The deploy key for this component.
     *
     * @var  string
     */
    protected $key    = null;

    /**
     * An input instance for uploaded files.
     *
     * @var  Joomla\CMS\Input\Files
     */
    protected $files  = null;

    /**
     * This component's configuration parameters.
     *
     * @var  Registry
     */
    protected $params = null;

    /**
     * Responsible for preparing the Continuous Delivery controller class.
     *
     * This method fetches various operational instaces of objects required for
     * the class to function correctly.
     */
    public function __construct() {
      // Run the parent constructor to setup this instance
      parent::__construct();
      // Fetch a reference to the required operational instances
      $this->app    = Factory::getApplication();
      $this->config = Factory::getConfig();
      $this->files  = $this->input->files;
      $this->params = $this->app->getParams();
      // Fetch the component's configured deploy key
      $this->key    = $this->params->get('deployKey', false);
      if (!is_string($this->key) || strlen($this->key) === 0)
        $this->key = false;
    }

    /**
     * Attempts to install the uploaded package file as a Joomla extension.
     *
     * This method expects a file upload input field with the name 'package' and
     * a file type compatible with the `JInstallerHelper::unpack()` method.
     *
     * This method makes use of the `deployKey` configuration parameter for
     * authentication. It is suggsted that your site force HTTPS to maintain
     * confidentiality of the authentication method.
     *
     * If the deploy key needs to be regenerated, you must fully uninstall this
     * component from Joomla, then a new key will be generated on the next
     * installation of the component.
     */
    public function run() {
      // Attempt to fetch an uploaded file from the 'package' field
      $package = $this->files->get('package', false, 'raw');
      // Determine if a package file was provided
      if (is_array   ($package) && is_file($package['tmp_name']) &&
          is_readable($package['tmp_name'])) {
        // Ensure that the provided deploy key matches the configured key
        if ($this->key && $this->input->get('deployKey') === $this->key) {
          // Move the file into Joomla's temporary directory
          jimport('joomla.filesystem.file');
          $tmp_dest  = implode(DIRECTORY_SEPARATOR,
            array($this->config->get('tmp_path'), $package['name']));
      		File::upload($package['tmp_name'], $tmp_dest, false, true);
          // Attempt to unpack the uploaded file using `JInstallerHelper`
          $package   = InstallerHelper::unpack($tmp_dest, true);
          // Attempt to install the unpacked extension using `JInstaller`
          $installer = Installer::getInstance();
          $result    = $installer->install($package['dir']);
          // Clean up the temporary files (uploaded package, unpacked directory)
          InstallerHelper::cleanupInstall(
            $package['packagefile'], $package['extractdir']);
          // Print a response detailing the result of the installation
          if ($result === true) echo json_encode(array('success' => true));
          else echo json_encode(array('error' => JText::_(
            'COM_CONTINUOUSDELIVERY_INSTALL_ERROR')));
        } else echo json_encode(array('error' => JText::_(
          'COM_CONTINUOUSDELIVERY_INVALID_DEPLOY_KEY')));
      } else echo json_encode(array('error' => JText::_(
        'COM_CONTINUOUSDELIVERY_UPLOAD_ERROR')));
      // It's not alright to die(); we're using the API!
      $this->app->close();
    }
  }
