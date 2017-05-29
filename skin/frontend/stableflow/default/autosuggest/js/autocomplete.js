

if (Varien && typeof(Varien) === "object" && "searchForm" in Varien) {
    Varien.searchForm.prototype.initAutocomplete = function(){}
}
 
var autoSearchForm = Class.create();
autoSearchForm.prototype = {
    initialize : function(form, field, emptyText) {
        this.form = $(form);
        this.field = $(field);
        this.emptyText = emptyText;
        this.url = null;
        this.destinationElement = null;
        this.length = 0;

        Event.observe(this.form, 'submit', this.submit.bind(this));
        Event.observe(this.field, 'focus', this.focus.bind(this));
        Event.observe(this.field, 'blur', this.blur.bind(this));
        this.blur();
    },

    submit : function(event) {
        if (this.field.value == this.emptyText || this.field.value == '') {
            Event.stop(event);
            return false;
        }
        return true;
    },

    focus : function(event) {
        if (this.field.value == this.emptyText) {
            this.field.value = '';
        }

    },

    blur : function(event) {
        if (this.field.value == '') {
            this.field.value = this.emptyText;
        }
    },

    initAutocomplete : function(url, destinationElement, wcount) {
        new Ajax.Autocompleter(
            this.field,
            destinationElement,
            url,
            {
                paramName: this.field.name,
                method: 'get',
                minChars: wcount,
                updateElement: this._selectAutocompleteItem.bind(this),
                indicator: 'auto_search_loader',
                onShow : function(element, update) {
                    if(!update.style.position || update.style.position=='absolute') {
                        update.style.position = 'absolute';
                        Position.clone(element, update, {
                            setHeight: false,
                            offsetTop: element.offsetHeight
                        });
                    }
                    Effect.Appear(update,{duration:0});
                }
            }
        );
    },

    _selectAutocompleteItem : function(element) {
        if (element.title) {
            this.field.value = element.title;
        }
        this.form.submit();
    }
};