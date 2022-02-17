<?php

namespace Drupal\my_custom_rest_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "my_custom_rest_api",
 *   label = @Translation("Custom REST API"),
 *   uri_paths = {
 *     "canonical" = "/get-api/v1"
 *   }
 * )
 */
class CustomGetRestAPI extends ResourceBase
{
  /**
   * Responds to GET requests.
   *
   * @return \Drupal\rest\ResourceResponse
   *   The HTTP response object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\HttpException
   *   Throws exception expected.
   */

    public function get()
    {
        $data = 'Your custom REST API is activated.';

        $response = new ResourceResponse($data);
      // In order to generate fresh result every time (without clearing
      // the cache), you need to invalidate the cache.
        $response->addCacheableDependency($data);
        return $response;
    }
}
