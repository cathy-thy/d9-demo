<?php

namespace Drupal\my_custom_rest_api\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\node\Entity\Node;

//Version 2 for user to get node conten

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "my_custom_rest_api_for_node",
 *   label = @Translation("Custom REST API to get node details"),
 *   uri_paths = {
 *     "canonical" = "/get-api/{contenttype}/v2"
 *   }
 * )
 */
class CustomGetRestAPIv2 extends ResourceBase
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

    public function get($contenttype = null)
    {
        $data = [];

        //write query to extract node ids for required content type
        //entityQuery is useful for getting different information (more details can be found on Drupal document)

        $nids = \Drupal::entityQuery('node')->condition('type', $contenttype)->execute();
        if ($nids) {
            $nodes = Node::loadMultiple($nids);

            foreach ($nodes as $node) {
                //you can use this line to get node details
                //echo "<pre>";print_r($node);exit;

                $data[] = ['node_id' => $node->id(),
                            'node_title' => $node->getTitle(),
                            'node_content' => $node->get('body')->getValue()
                ];
            }
        }

        $response = new ResourceResponse($data);
        // In order to generate fresh result every time (without clearing
        // the cache), you need to invalidate the cache.
        $response->addCacheableDependency($data);
        return $response;
    }
}
