window.onload = function() {
    // Horizontal
    baron({
        root: '.main__clipper',
        scroller: '.main__scroller',
        bar: '.main__bar',
        scrollingCls: '_scrolling',
        draggingCls: '_dragging',
        direction: 'h',
        impact: 'scroller'
    });

    baron({
        root: '.baron',
        scroller: '.baron__scroller',
        bar: '.baron__bar',
        scrollingCls: '_scrolling',
        draggingCls: '_dragging'
    }).fix({
        elements: '.header__title',
        outside: 'header__title_state_fixed',
        before: 'header__title_position_top',
        after: 'header__title_position_bottom',
        clickable: true
    }).controls({
        // Element to be used as interactive track. Note: it could be different from 'track' param of baron.
        track: '.baron__track',
        forward: '.baron__down',
        backward: '.baron__up'
    });

    var $block = jQuery('.page-logo');
    var $window = jQuery(window);
    jQuery('.baron__root').height($window.height() - ($block.height() + 40));
    jQuery(window).resize(function(){
        jQuery('.baron__root').height($window.height() - ($block.height() + 40));
    });

};