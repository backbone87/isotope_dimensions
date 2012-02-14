(function($, $$, undef) {

var changeEvent = function(e) {
	var tmp = $(this);
	if(tmp.get("value") == "") {
		e.stop();
		return;
	}
	tmp = tmp.getParent(".form_dimension_2d").getElement("input[id*=" + (tmp.get("name").match("x") ? "y" : "x") + "]");
	if(tmp && tmp.get("value") == "") {
		e.stop();
		return;
	}
};
var manipulateChangeEvent = function() {
	$$(".form_dimension_2d").each(function(dimensions) {
		dimensions.getElements("input").removeEvent("change", changeEvent).addEvent("change", changeEvent);
	});
};

window.addEvent("domready", manipulateChangeEvent);
window.addEvent("ajaxready", manipulateChangeEvent);

})(document.id, window.$$);