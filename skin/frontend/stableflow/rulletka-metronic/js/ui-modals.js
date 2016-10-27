var UIModals = function () {
    var handleModals = function () {
        jQuery("#draggable").draggable({
            handle: ".modal-header"
        });
    };
    return {
        //main function to initiate the module
        init: function () {
            handleModals();
        }
    };
}(jQuery);

jQuery(document).ready(function() {    
   //UIModals.init();
});