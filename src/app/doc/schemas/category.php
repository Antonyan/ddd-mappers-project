<?php

use OpenApi\Annotations\Schema;
use OpenApi\Annotations\Property;

//TODO: it's just a schema example

/**
 * @Schema(
 *   schema="Category",
 *   description="Category",
 *   type="object",
 *   properties= {
 *     @Property(property="id", description="The category id", type="integer"),
 *     @Property(property="name", description="The category name", type="string"),
 *     @Property(property="parentId", description="The category name", type="integer"),
 * }
 * )
 */

