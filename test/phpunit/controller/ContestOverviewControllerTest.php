<?php

/**
 * searches for all xml files and tries to parse xml
 */
class ContestControllerOverviewTest extends WCFHTTPTest {
	
	public function testContestOverview() {
		$this->runHTTP('page=ContestOverview');
	}
	
	public function testContestOverviewFeedPage() {
		$this->runHTTP('page=ContestOverviewFeed');
	}
}
?>
