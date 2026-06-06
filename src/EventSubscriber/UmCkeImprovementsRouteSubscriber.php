<?php

declare(strict_types=1);

namespace Drupal\um_cke_improvements\EventSubscriber;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Route subscriber.
 */
final class UmCkeImprovementsRouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection): void {
    if ($route = $collection->get('ckeditor5.upload_image')) {
      $route->setDefault('_controller', '\Drupal\um_cke_improvements\Controller\CKEditor5ImageController::upload');
    }
  }

}
