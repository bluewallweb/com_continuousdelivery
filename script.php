<?php
  /**
   * This file defines the installer script to be ran on component installation.
   *
   * @copyright  Copyright 2016 Clay Freeman. All rights reserved.
   * @license    GNU Lesser General Public License v3 (LGPL-3.0).
   */

  use Joomla\CMS\Component\ComponentHelper;
  use Joomla\CMS\Factory;
  use Joomla\CMS\Table\Table;

  /**
   * This class serves to run during the 'postflight' phase of the component's
   * installation so that a deploy key can be randomly generated.
   */
  class com_ContinuousDeliveryInstallerScript {
    /**
     * Contains the parameters for the installed component.
     *
     * @var  Registry
     */
    protected $params = null;

    /**
     * The ID number for the installed component.
     *
     * @var  int
     */
    protected $id     = null;

    /**
     * Responsible for preparing the installer script class for running.
     *
     * This constructor fetches the installed component's parameters and
     * database extension ID.
     */
    protected function construct() {
      $name         = 'com_continuousdelivery';
      // Fetch a reference to the required operational instances
      $this->params = ComponentHelper::getParams($name);
      // Determine this extension's ID number
      $this->id     = ComponentHelper::getComponent($name)->id;
    }

    /**
     * This method is responsible for assigning a randomly generated deploy key
     * to the installed component.
     *
     * This method will not clobber an existing deploy key. To force deploy key
     * regeneration, you should first uninstall the component in Joomla.
     *
     * @param   string  $type    The type of the postflight action.
     * @param   object  $parent  The caller of this script.
     */
    public function postflight($type, $parent) {
      // Give our half-real constructor a chance to prepare the instance
      $this->construct();
      // Check if a deploy key was already generated
      if (!preg_match('/^[a-f0-9]{32}$/', $this->params->get('deployKey')))
        // Assign a random value to the deploy key parameter
        $this->setParam('deployKey', bin2hex(random_bytes(16)));
    }

    /**
     * Cleans the Joomla system cache so that parameter changes can be instantly
     * reflected in the global configuration section.
     *
     * @return  bool  `true` on success, `false` on failure.
     */
    protected function cleanCache() {
      // Fetch the cache path for this Joomla installation
      $default = implode(DIRECTORY_SEPARATOR, array(JPATH_SITE, 'cache'));
      $cache   = Factory::getConfig()->get('cache_path', $default);
      // Define an array of options to pass to JCache::getInstance()
      $options = array('defaultgroup' => '_system', 'cachebase' => $cache);
      // Clear the '_system' cache using the JCache instance
      return JCache::getInstance('callback', $options)->clean('_system');
    }

    /**
     * Assigns the provided name and value pair in the component's configuration
     * parameter registry.
     *
     * @param  string  $name   The name of the parameter to assign.
     * @param  mixed   $value  The value of the parameter to assign.
     *
     * @return bool            `true` on success, `false` on failure.
     */
    protected function setParam($name, $value) {
      // Assign the requested key/value pair to the Registry instance
      $this->params->set($name, $value); $update = $this->params->toString();
      // Fetch this extension's record from the database
      $table = Table::getInstance('extension'); $table->load($this->id);
      // Re-assign the parameters for this extension using our instance values
      return $table->bind(array('params' => $update)) &&
        $table->store() && $this->cleanCache();
    }
  }
