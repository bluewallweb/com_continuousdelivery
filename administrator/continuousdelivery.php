<?php
  /**
   * Joomla's designated entrypoint into this component.
   *
   * @copyright  Copyright 2016 Clay Freeman. All rights reserved.
   * @license    GNU Lesser General Public License v3 (LGPL-3.0).
   */

  // Redirect to the administrator site root
  JFactory::getApplication()->redirect(JRoute::_('/administrator'));
