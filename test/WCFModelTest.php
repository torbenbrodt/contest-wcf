<?php

class WCFModelTest extends WCFTest {
	
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
