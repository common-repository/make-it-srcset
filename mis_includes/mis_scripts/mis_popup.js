(function( $, document ) {
    $.fn.mis_popup = function(options) {
        var settings = $.extend({
            body : $('body'),
            overlay_ID : 'mis_overlay',
            imgcloned_class : 'mis_is-cloned',
            show_class : 's-is-visible',
            pop_sizes : '100vw'
        }, options );

        var popup_src = this;

        if(popup_src.length){
            var fragment = document.createDocumentFragment();
            var overlay = $('<div />', {
                id: settings.overlay_ID
            }).appendTo(fragment);

            // Create popup-clones
            popup_src.each(function(){
                var $this = $(this);
                var popped_id = $this.attr('data-misid');

                $this.clone()
                    .addClass(settings.imgcloned_class)
                    .attr('sizes', settings.pop_sizes)
                    .attr('id', popped_id).appendTo(fragment);
            });

            settings.body.append(fragment);
            var popup_elems = $('.' + settings.imgcloned_class).add(overlay);

            popup_src.on('click', function(e){
                var $this = $(this);
                var thisclone = $('#'+ $this.attr('data-misid'));
                var thisclone_width = thisclone.width();
                var thisclone_height = thisclone.height();
                var viewport_height = $(window).height();

                if(thisclone_height > thisclone_width){
                    thisclone.css({
                        'width' : 'auto',
                        'max-height' : (viewport_height - 100) + 'px'
                    });
                }

                overlay.addClass(settings.show_class);
                thisclone.addClass(settings.show_class);

                settings.body.one('keyup', function(e){
                    if (e.keyCode == 27) {
                        popup_elems.removeClass(settings.show_class);
                        e.preventDefault(e);
                    }
                });

                e.stopPropagation(e);
                e.preventDefault(e);
            });

            popup_elems.on('click', function(e){
                popup_elems.removeClass(settings.show_class);
                e.preventDefault();
            });

            return this;
        }
    };
}( jQuery, document ));

if($.fn.mis_popup) {
    var popup_src = $('.mis_popup');
    if(popup_src.length){
        popup_src.mis_popup();
    }
}