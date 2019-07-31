<?php
  /**
   * Joomla's designated entrypoint into this component.
   *
   * @copyright  Copyright 2019 Bluewall, LLC. All rights reserved.
   * @license    GNU Lesser General Public License v3 (LGPL-3.0).
   */

  use \Joomla\CMS\MVC\Controller\BaseController;

  // Fetch an instance of this component's controller
  $controller = BaseController::getInstance('ContinuousDelivery');
  // Execute the 'run' method on the controller
  $controller->execute('run');
