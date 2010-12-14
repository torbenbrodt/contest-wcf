<div id="contestInteractionTable{$contestID}">...</div>
<script type="text/javascript">
function updateContestInteractionTable(id) {
	var links = document.getElementById('contestInteractionPagination' + id).getElementsByTagName('a');
	var ref = function(url, id) {
		return function() {
			new Ajax.Updater('contestInteractionTable' + id, url, { method: 'get', onComplete: function() {
				updateContestInteractionTable(id);
			}});
			return false;
		};
	};

	for(var i=0; i<links.length; i++) {
		links[i].onclick = ref(links[i].href, id);
	}
};
new Ajax.Updater('contestInteractionTable{$contestID}', '/index.php?page=ContestInteraction&contestID={$contestID}' + SID_ARG_2ND, { method: 'get' , onComplete: function() {
	updateContestInteractionTable({$contestID});
}});
</script>
