<?php

/**
 * contest add form
 */
class ContestAddControllerTest extends WCFHTTPTest {
	
	public function testRedirectPage() {
		$content = $this->runHTTP('form=ContestAdd');
		$this->assertTrue(strpos($content, 
			'<meta http-equiv="refresh" content="10;URL=index.php?page=Register') !== false,
			"page should include a redirect"
		);
	}
}
?>
