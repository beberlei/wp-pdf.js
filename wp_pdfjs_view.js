(function($, jQuery) {
    jQuery(document).ready(function() {
        $("[data-wp-pdf]").wpPdfjs();
    });

    jQuery.fn.wpPdfjs = function(options) {
        options = jQuery.extend({}, jQuery.fn.wpPdfjs.defaults, options);

        PDFJS.disableWorker = true;

        return $(this).each(function() {
            var elem = $(this);
            var id = $(this).attr('id');
            var scale = $(this).data('wp-pdf-scale');
            var url = $(this).data('wp-pdf');

            var pdfDoc = null,
                pageNum = 1,
                canvas = elem.find('canvas')[0],
                ctx = canvas.getContext('2d');

            function wp_pdfj_renderPage(num) {
                pdfDoc.getPage(num).then(function(page) {
                    var viewport = page.getViewport(scale);
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    var renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };
                    page.render(renderContext);
                });

                elem.find('[data-wp-pdfjs-page-num]').text(pageNum);
                elem.find('[data-wp-pdfjs-page-total]').text(pdfDoc.numPages);

                if (pdfDoc.numPages <= 1) {
                    jQuery('[data-wp-pdfjs-pagination]').hide();
                }

                var width = elem.find('canvas').width();

                elem.css('width', width);
            }

            var wp_pdfjs_goPrevious = function(ev) {
                ev.preventDefault();

                if (pageNum <= 1) {
                    return;
                }
                pageNum--;
                wp_pdfj_renderPage(pageNum);
            };

            var wp_pdfjs_goNext = function(ev) {
                ev.preventDefault();

                if (pageNum >= pdfDoc.numPages) {
                    return;
                }

                pageNum++;
                wp_pdfj_renderPage(pageNum);
            };

            elem.find('[data-wp-pdf-prev]').click(wp_pdfjs_goPrevious);
            elem.find('[data-wp-pdf-next]').click(wp_pdfjs_goNext);
            elem.find('canvas').click(wp_pdfjs_goNext);

            $("body").on('keydown', function(ev) {
                if (ev.keyCode === 37) { // LEFT
                    wp_pdfjs_goPrevious(ev);
                } else if (ev.keyCode === 39) { // RIGHT
                    wp_pdfjs_goNext(ev);
                }
            });

            PDFJS.getDocument({url: url}).then(function(_pdfDoc) {
                pdfDoc = _pdfDoc;
                wp_pdfj_renderPage(pageNum);
            });
        });
    }
    jQuery.fn.wpPdfjs.defaults = {
    }
})(jQuery, jQuery);
