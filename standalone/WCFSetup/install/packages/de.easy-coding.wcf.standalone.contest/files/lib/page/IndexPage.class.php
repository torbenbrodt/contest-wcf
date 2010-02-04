<?php
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

class IndexPage extends AbstractPage {
	/**
	 * @see Page::show()
	 */
	public function show() {
		require_once(WCF_DIR.'lib/page/ContestOverviewPage.class.php');
		new ContestOverviewPage();
		exit;
	}
}
?>
