(function($){

	var toggleFilters = false;

	function initLoader() {
		$('.mkmage-overlay').fadeIn(200, function(){
			$('.mkmage-loader').show();
		});
	}
	
	function killLoader() {
		$('.mkmage-loader').hide();
		$('.mkmage-overlay').fadeOut(200);
	}

	function filter(){	
		var origMinPriceFilter = parseFloat($('#original-price-filter-min').val()),
			origMaxPriceFilter = parseFloat($('#original-price-filter-max').val()),
			newMinPriceFilter = parseFloat($('#price-filter-min').val()),
			newMaxPriceFilter = parseFloat($('#price-filter-max').val());
			
		$("#slider-range").slider({
			range: true,
			min: ((origMinPriceFilter) < newMinPriceFilter) ? (origMinPriceFilter) : newMinPriceFilter,
			max: ((origMaxPriceFilter) > newMaxPriceFilter) ? (origMaxPriceFilter) : newMaxPriceFilter,
			values: [ parseFloat($('#price-filter-min').val()), parseFloat($('#price-filter-max').val()) ],
			step:0.01,
			stop: function( event, ui ) {				
				var minvalue = ui.values[ 0 ];
				var maxvalue = ui.values[ 1 ];
				
				$( "#price_from" ).html( "$" + minvalue);
				$( "#price_to" ).html( "$" + maxvalue);
			
				var currentUrl = $('#price-filter-url').val();
				var newUrl = ( currentUrl.indexOf("?") != -1 ) ? 
					( currentUrl + 'price=' + minvalue + '-' + maxvalue ) : ( currentUrl + 'price=' + minvalue + '-' + maxvalue );
				//console.log(newUrl);
				filterAjax(newUrl);					
			}
		});
		
		$( "#price_from" ).html( "$" + $( "#slider-range" ).slider( "values", 0 ));
		$( "#price_to" ).html( "$" + $( "#slider-range" ).slider( "values", 1 ));

		$('.toolbar a').each(function(index){
			$(this).click(function(){
				filterAjax($( this ).attr('href'));           
				//console.log( index + ": " + $( this ).text() ); 
				return false;
			});
	  
		});
		$('.toolbar select').each(function(index){
			$(this).removeAttr('onchange');
			$(this).change(function(){
				filterAjax($( this ).val());
				//console.log( index + ": " + $( this ).text() ); 
				return false;
			});
	  
		});
		$('.block-layered-nav a').each(function(index){
			$(this).click(function(){         
				filterAjax($( this ).attr('href'));
				//console.log( index + ": " + $( this ).text() ); 
				return false;
			});
	  
		});		

	}
	
	function updatePage(data) {
		//console.log(data);
		var layeredBlock = $('.block-layered-nav').parent();
		var listBlock = $('.category-products').parent();
		$('.category-products').remove();
		listBlock.append(data.page);                   
		$('.block-layered-nav').remove();
		layeredBlock.prepend(data.block);				
		$('body').append(data.js);
		$('#loader').hide();
	}

	function filterAjax(s_url){ 		
		$.ajax({
			type: "GET",
			data : {is_ajax:1},
			url: s_url,
			beforeSend: function() {
				initLoader();
			},
			success: function(data) {
				killLoader();				
				updatePage(data);
				filter();
		  	},
		  	error: function(jqXHR, textStatus, errorThrown) {
		  		//console.log(errorThrown);
		  	}
		});		
	}
	
	function categoryPageItemsHover() {
		var productItem = $('.products-grid .item .item-image-wrapper');
		productItem.hover(
			function() {
				$(this).find('.image-overlay').stop().fadeIn(250);
			},
			function() {
				$(this).find('.image-overlay').stop().fadeOut(250);
			}
		);
	}	

	function categoryPageFiltersToggle() {
		var filters = $('.catalog-category-view #narrow-by-list .glyphicon-chevron-up');
		filters.click(function(){
			if($(this).hasClass('inactive-filter')) {
				$(this).removeClass('inactive-filter');
				$(this).css('transform','rotateX(0deg)');
				var target = $(this).parent('dt').next().slideDown();
			} else {
				$(this).addClass('inactive-filter');
				$(this).css('transform','rotateX(180deg)');
				var target = $(this).parent('dt').next().slideUp();
			}
		});
	}
	
	function handleFilters() {
		if ($(window).width() < 980) {
			if(!toggleFilters) {
				var filters = $('.catalog-category-view #narrow-by-list .glyphicon-chevron-up');	
				filters.each(function(){
					if(!$(this).hasClass('inactive-filter')) {
						$(this).click();
					}
				});
				toggleFilters = true;
			}
		}		
	}
	
	$(document).ready(function(){
		$(".pfilter").parent().addClass('priceFilterItem');
		filter();		
		categoryPageFiltersToggle();
		//handleFilters();
	});
	
	$(window).resize(function(){
		//handleFilters();	
	});

})(jQuery);