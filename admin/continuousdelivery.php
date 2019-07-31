<?php
  /**
   * Joomla's designated entrypoint into this component.
   *
   * @copyright  Copyright 2019 Bluewall, LLC. All rights reserved.
   * @license    GNU Lesser General Public License v3 (LGPL-3.0).
   */

  use \Joomla\CMS\Factory;
  use \Joomla\CMS\Router\Route;

  // Redirect to the administrator site root
  Factory::getApplication()->redirect(Route::_('/administrator'));
