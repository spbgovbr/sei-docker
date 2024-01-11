// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict';
    window.addEventListener('load', function () {
        $('input[type="range"]').on('input', function () {

            var control = $(this),
                controlMin = control.attr('min'),
                controlMax = control.attr('max'),
                controlVal = control.val(),
                controlThumbWidth = control.data('thumbwidth');

            var range = controlMax - controlMin;
            var position = ((controlVal - controlMin) / range) * 100;
            var positionOffset = Math.round(controlThumbWidth * position / 100) - (controlThumbWidth / 2);
            var output = control.next('output');

            output
                .css({
                    'left': 'calc(' + position + '% - ' + positionOffset + 'px)',
                    'visibility': 'visible'
                })
                .text(controlVal);
            control.nextAll('.slider-selection').css('width', position + '%');
        });

        //multi-range
        function getVals() {
            // Get slider values
            let parent = this.parentNode;
            let control = $(this);
            let selection = control.nextAll('.multi-slider-selection');

            let controlMax = parseFloat(control.attr('max'));
            let controlMin = parseFloat(control.attr('min'));

            let slides = parent.getElementsByTagName("input");
            let slide1 = parseFloat(slides[0].value);
            let hasSlide2 = slides[1];
            let displayElement = parent.getElementsByClassName("rangeValues")[0];
            let width = null;

            const maxInterval = (controlMax - controlMin);

            let isMultiple = typeof hasSlide2 !== 'undefined';

            if (isMultiple) {
                var slide2 = parseFloat(slides[1].value);

                if (slide1 > slide2) {
                    var tmp = slide2;
                    slide2 = slide1;
                    slide1 = tmp;
                }

                displayElement.innerHTML = slide1 + ' - ' + slide2;
                const left = ((slide1 - controlMin) / maxInterval) * 100;
                const inputInterval = slide2 - slide1;
                width = (inputInterval / maxInterval) * 100;

                selection.css('left', left + '%')

            } else {
                width = ((slide1 - controlMin) / maxInterval) * 100;
                selection = control.nextAll('.slider-selection');

                displayElement.innerHTML = slide1;
            }

            selection.css('width', width + '%')
        }

        // Initialize Sliders
        var sliderSections = document.getElementsByClassName("range-slider");
        for (var x = 0; x < sliderSections.length; x++) {
            var sliders = sliderSections[x].getElementsByTagName("input");
            for (var y = 0; y < sliders.length; y++) {
                if (sliders[y].type === "range") {
                    sliders[y].oninput = getVals;
                    //Manually trigger event first time to display values
                    sliders[y].oninput();
                }
            }
        }
    });
})();