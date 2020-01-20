<?php
namespace App\ORM;


use CrEOF\Spatial\ORM\Query\AST\Functions\AbstractSpatialDQLFunction;
use CrEOF\Spatial\ORM\Query\AST\Functions\ReturnsGeometryInterface;

/**
 * AsText DQL function
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class STAsText extends AbstractSpatialDQLFunction implements ReturnsGeometryInterface
{
	protected $platforms = array('mysql');

	protected $functionName = 'ST_AsText';

	protected $minGeomExpr = 1;

	protected $maxGeomExpr = 1;
}
