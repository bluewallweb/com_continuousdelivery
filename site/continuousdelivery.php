<?php
  /**
   * Joomla's designated entrypoint into this component.
   *
   * @copyright  Copyright 2016 Clay Freeman. All rights reserved.
   * @license    GNU Lesser General Public License v3 (LGPL-3.0).
   */

  (function() {
    // Fetch an instance of this component's controller
    $controller = JControllerLegacy::getInstance('ContinuousDelivery');
    // Execute the 'run' method on the controller
    $controller->execute('run');
  })();
