(function () {
    $( window ).on('load resize', function() {
        var navItems = $('.main-menu .nav-item');
        for (var i = 0; i < navItems.length; i++) {
            if (($(window).width() / 2) < $(navItems[i]).offset().left) {
                if ($(navItems[i]).hasClass('mega-menu')) {
                    $(navItems[i]).find('.dropdown-menu').removeAttr('style');
                } else {
                    $(navItems[i]).addClass('position-relative').find('.dropdown-menu').addClass('dropdown-menu-right');
                }
            } else{
                if ($(navItems[i]).hasClass('mega-menu')) {
                    $(navItems[i]).find('.dropdown-menu').css('top', 'auto');
                } else {
                    $(navItems[i]).removeClass('position-relative').find('.dropdown-menu').removeClass('dropdown-menu-right');
                }
            }
        }
    });
})();

/*| Only Numberic Values |*/
$(document).on('input change', '.isNumeric', function() {
    this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
});

/*| Increment/Decrement Quantity |*/
$(document).on('click', '[wcs-plus], [wcs-minus]', function() {
    var name = $(this).attr('wcs-plus') || $(this).attr('wcs-minus'),
    $input = $('[wcs-quantity="'+name+'"]'), value = Number($input.val());
    if ($(this).is('[wcs-plus]')) {
        ++value;
        if ($input.attr('maxlength')) {
            $input.attr('maxlength') >= value.toString().length ? $input.val(value) : $input.val(--value);
        } else {$input.val(value);}
    } else if (value > 1) {
        $input.val(--value);
    } else {
        $input.val($input.attr('min') ? $input.attr('min') : 0);
    }
});

//Js for quantity input
$('.btn-number').click(function(e){
    e.preventDefault();

    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {

            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
    $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {

    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());

    var name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }


});
$(".input-number").keydown(function (e) {
    // Allow: backspace, delete, tab, escape, enter and .
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
        // Allow: Ctrl+A
        (e.keyCode == 65 && e.ctrlKey === true) ||
        // Allow: home, end, left, right
        (e.keyCode >= 35 && e.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
});