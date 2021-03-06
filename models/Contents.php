<?php
/**
 * radium: lithium application framework
 *
 * @copyright     Copyright 2013, brünsicke.com GmbH (http://bruensicke.com)
 * @license       http://opensource.org/licenses/BSD-3-Clause The BSD License
 */

namespace radium\models;

use radium\data\Converter;

class Contents extends \radium\models\BaseModel {

	/**
	 * Custom type options
	 *
	 * @var array
	 */
	public static $_types = array(
		'plain' => 'Plain text',
		'html' => 'Html Markup',
		'mustache' => 'Mustache',
		'markdown' => 'Markdown',
	);

	/**
	 * Stores the data schema.
	 *
	 * @see lithium\data\source\MongoDb::$_schema
	 * @var array
	 */
	protected $_schema = array(
		'_id' => array('type' => 'id'),
		'name' => array('type' => 'string', 'default' => '', 'null' => false),
		'slug' => array('type' => 'string', 'default' => '', 'null' => false),
		'type' => array('type' => 'string', 'default' => 'plain'),
		'body' => array('type' => 'string'),
		'notes' => array('type' => 'string', 'default' => '', 'null' => false),
		'status' => array('type' => 'string', 'default' => 'active', 'null' => false),
		'created' => array('type' => 'datetime', 'default' => '', 'null' => false),
		'updated' => array('type' => 'datetime'),
		'deleted' => array('type' => 'datetime'),
	);

	/**
	 * Validation rules
	 *
	 * @var array
	 */
	public $validates = array(
		'_id' => array(
			array('notEmpty', 'message' => 'a unique _id is required.', 'last' => true, 'on' => 'update'),
		),
		'name' => array(
			array('notEmpty', 'message' => 'a name is required.'),
		),
		'slug' => array(
			array('notEmpty', 'message' => 'a valid slug is required.', 'last' => true),
			array('slug', 'message' => 'only numbers, small letters and . - _ are allowed.', 'last' => true),
		),
	);


	/**
	 * load a specific contents
	 *
	 * if just given a name, it returns the body() of that record. If you
	 * pass in data, it will be used in the render context.
	 *
	 * @see radium\model\Contents::body()
	 * @param string $name name of configuration to retrieve
	 * @param array $data additional data to be passed into render context
	 * @param array $options an array of options, currently all of
	 *              Contentes::body() are supported, see there.
	 * @return mixed
	 */
	public static function get($name, $data = null, array $options = array()) {
		$defaults = array('default' => '', 'status' => 'active');
		$options += $defaults;
		$entity = static::load($name);
		if (!$entity || $entity->status != $options['status']) {
			return $options['default'];
		}
		return $entity->body($data, $options);
	}

	/**
	 * returns parsed content of Contents body
	 *
	 * @see radium\data\Converter::get()
	 * @param object $content instance of current record
	 * @param array $data additional data to be passed into render context
	 * @param array $options additional options to be passed into `Converter::get()`
	 * @return array parsed content of Contents body
	 */
	public function body($content, $data = array(), array $options = array()) {
		return Converter::get($content->type, $content->body, $data, $options);
	}

}

?>