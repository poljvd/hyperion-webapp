/**
 * General Scripts - ColourPicker webapp for Hyperion
 */

var durations = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55, 60, 120, 180, 240, 300, 360, 420,
    480, 540, 600, 1200, 1800, 2400, 3000, 3600, 7200, 10800, 14400, 18000];
var duration_views = ['1 second', '2 seconds', '3 seconds', '4 seconds', '5 seconds', '6 seconds', '7 seconds',
    '8 seconds', '9 seconds', '10 seconds', '15 seconds', '20 seconds', '25 seconds', '30 seconds', '35 seconds',
    '40 seconds', '45 seconds', '50 seconds', '55 seconds', '1 minute', '2 minutes', '3 minutes', '4 minutes',
    '5 minutes', '6 minutes', '7 minutes', '8 minutes', '9 minutes', '10 minutes', '20 minutes', '30 minutes',
    '40 minutes', '50 minutes', '1 hour', '2 hours', '3 hours', '4 hours', '5 hours'];

$(document).ready(function() {
    $('.colourCode').css('backgroundColor', currentColour);
    $('#color').val(currentColour.toUpperCase());

    $('#color-picker').farbtastic('#color');

    $('#effect-switch').click(function (e) {
        e.preventDefault();

        if (!$('#effect-display').is(':visible')) {
            $(this).text('Close Effects');
        } else {
            $(this).text('Effects');
        }

        $('#effect-display').toggle(0, function() {
            $('html, body').animate({scrollTop: $('#effect-switch').offset().top}, 0);
        });
    });

    $('.effect').click(function () {
        $('#effect').val($(this).val());
        $(this).val('Loading Effect...');
        $('#form').submit();
    });

    $('#priority-slider').slider({
        'min': 0,
        'max': 500,
        'step': 10,
        'value': currentPriority,
        slide: function(event, ui) {
            $('#priority-slider-display').find('span').text(ui.value);
            $('#priority').val(ui.value);
        }
    });

    $('#duration-display').click(function (){
        $('#duration-holder').toggle();
        if ($('#duration-holder').is(':visible')) {
            $('#duration').val($('#duration-slider').slider('value'));
            $('#duration-display-label').removeClass('blue').addClass('orange');
        } else {
            $('#duration').val('');
            $('#duration-display-label').removeClass('orange').addClass('blue');
        }
    });

    $('#duration-display').button({
        create: function(event, ui) {
            var durationEle = $('label[for="' + $(this).attr('id') + '"]');
            durationEle.attr('id', 'duration-display-label').addClass('button').addClass('large').addClass('blue');
            var text = durationEle.find('span').text();
            durationEle.find('span').remove();
            durationEle.text(text);
        }
    });

    $('#duration-slider').slider({
        'min': 0,
        'max': 37,
        'step': 1,
        'value': 1,
        slide: function(event, ui) {
            $('#duration-slider-display').find('span').text(duration_views[ui.value]);
            $('#duration').val(durations[ui.value]);
        }
    });
});