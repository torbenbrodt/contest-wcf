<?php

/**
 * searches for all xml files and tries to parse xml
 */
class ContestOverviewTest extends WCFHTTPTest {
	
	public function testBasic() {
		$this->runHTTP('index.php');
	}
}
?>
