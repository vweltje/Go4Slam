// Ajax Class | Go4Slam App
// Design and development by
// Vincent Weltje en Jules Peeters

var ajaxClass = {
	url: '',
	method: 'POST',
	data: {},
	result: '',
	init: function() {
		this.doCall();
		return this.result;
	},
	doCall: function() {
		var ajax = $.ajax({
		  method: this.method,
		  url: this.url,
		  data: this.data,
		  async: false
		});
		var ajaxClass = this;
		ajax.success(function(response) {
			ajaxClass.getResult(response);
		});
	},
	getResult: function(result) {
		this.result = result;
	}
}
