(function($, $$, undef) {
window.addEvent("domready", function() {
	
$$(".form_dimensions").each(function(dimensions) {
	dimensions.getElements("input").addEvent("change", function(e) {
		var tmp = $(this);
		if(tmp.get("value") == "") {
			e.stop();
			return;
		}
		tmp = tmp.get("name");
		tmp = tmp.match("x") ? "y" : "x";
		tmp = dimensions.getElement("input[id*=" + tmp + "]");
		if(tmp && tmp.get("value") == "") {
			e.stop();
			return;
		}
	});
});
	
});
})(document.id, window.$$);