dojo.require("dojo._base.connect");
dojo.require("dijit.Dialog");

dojo.addOnLoad(function() {
	// get dialog
	var butWaitDialog = new dijit.Dialog({title: 'special text'}, dojo.byId('butWait'));

	// prepare buttons
	dojo.connect(dojo.byId('butWait_close'), 'onclick', butWaitDialog, function() {
		// hide dialog
		this.hide();
	});

	dojo.connect(document, 'onmouseout', butWaitDialog, function(e) {
		if(e.target == document.documentElement && !e.relatedTarget) {
			// show dialog
			this.show();
		}
	});
});