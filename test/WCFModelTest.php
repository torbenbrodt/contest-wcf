<?php

class WCFModelTest extends WCFTest {

	/**
	 * for model tests
	 * 
	 * @var	array<DatabaseObject>
	 */
	protected $deleteArray = array();

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {
		foreach($this->deleteArray as $delete) {
			if(class_exists('UserEditor') && $delete instanceof User) {
				UserEditor::deleteUsers(array($delete->userID));
			} else {
				$delete->delete();
			}
		}
		
		parent::tearDown();
	}
	
	/**
	 * calls all public method without required parameters using reflection api
	 *
	 * @parma	DatabaseObject		$model
	 * @return	integer			returns number of methods being called
	 */
	protected function callAllMethodsWithoutRequiredParameters(DatabaseObject $model) {
		$i = 0;
		$className = get_class($model);
		$refclass = new ReflectionClass($className);
		foreach($refclass->getMethods(ReflectionMethod::IS_PUBLIC) as $refmethod) {
			if($refmethod->getNumberOfRequiredParameters()) {
				continue;
			}
			try {
				if($refmethod->isStatic()) {
					eval($className.'::'.$refmethod->name.'();');
				} else {
					$model->{$refmethod->name}();
				}
				$i++;
			} catch(Exception $e) {
				throw new Exception('error in '.$className.'::'.$refmethod->name.' with message: '.$e->getMessage());
			}
		}
		return $i;
	}
}
?>
