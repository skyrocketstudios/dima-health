(function($) {

	var listingsSelect = {};
	qode.modules.listingsSelect = listingsSelect;
	listingsSelect.qodeOnDocumentReady = qodeOnDocumentReady;
	listingsSelect.qodeOnWindowLoad = qodeOnWindowLoad;
	listingsSelect.qodeOnWindowResize = qodeOnWindowResize;
	listingsSelect.qodeOnWindowScroll = qodeOnWindowScroll;

	$(document).ready(qodeOnDocumentReady);
	$(window).load(qodeOnWindowLoad);
	$(window).resize(qodeOnWindowResize);
	$(window).scroll(qodeOnWindowScroll);

	listingsSelect.qodeSelect2Fields = qodeSelect2Fields;
	listingsSelect.qodeInitSelect2Field = qodeInitSelect2Field;


	function qodeOnDocumentReady() {
        qodeSelect2Fields();
	}
	function qodeOnWindowLoad() {}
	function qodeOnWindowResize() {}
	function qodeOnWindowScroll() {}

	function qodeSelect2Fields(){

		var defaultSelectFields = $(
			'.qode-ls-adv-search-holder select, ' +
			'.qode-ls-main-search-holder-part select, ' +
			'.qode-ls-archive-holder select, ' +
			'.qode-ls-single-comments .qode-ls-single-sort, ' +
			'.qode-membership-dashboard-page select'

		);
		if(defaultSelectFields.length){
			defaultSelectFields.each(function(){
                qodeInitSelect2Field($(this));
			});
		}

	}

	function qodeInitSelect2Field(field){
		if(qode.modules.listings.qodeIsValidObject(field)){
            field.select2({

			});
        }
	}

})(jQuery);