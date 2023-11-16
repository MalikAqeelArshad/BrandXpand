/*
Developer: Aqeel Malik;
WhatsApp : +92 302 6262164;
Email ID : Malik.AqeelArshad@gmail.com;
*/
$(function () {
    /* Toggle Sidebar */
    $(document).on('click', '#sidebarCollapse', function () {
        $('body').toggleClass('collapse-sidebar');
        $(this).children('svg').toggleClass('fa-bars fa-align-left');
        if(localStorage.getItem("collapse-sidebar")) {
            localStorage.removeItem("collapse-sidebar");
            $(".sidebar .dropdown-toggle").parent('li.focus').find('.collapse').collapse('show');
        } else {
           localStorage.setItem("collapse-sidebar",true);
            $(".sidebar .dropdown-toggle").parent('li').find('.collapse').collapse('hide');
       }
    }).ready(function () {
        if(localStorage.getItem("collapse-sidebar")) {
            $(".sidebar .dropdown-toggle").parent('li').find('.collapse').collapse('hide');
        }
    });

    /* Sidebar Menu Mouse Hover (in, out) */
    $(".sidebar .dropdown-toggle").parent('li').hover( function() {
        //$(this).siblings().not('.active').find('.collapse').collapse('hide');
        $(this).siblings(':not(.focus)').removeClass('focus').find('.collapse').collapse('hide');
        $(this).find('.collapse').collapse('show');
    }, function() {
        $(this).closest('li:not(.focus)').find('.collapse').collapse('hide');
    });

    /*| Scrollspy onlick scroll to target section/div |*/
    $('[data-scroll] a').on('click', function() {
        var target = $(this.hash);
        var container = $('[data-target="#'+$(this).closest('[data-scroll]').attr('id')+'"]');
        $(container).animate({
            scrollTop: container.scrollTop() + target.offset().top - container.offset().top
        }, 'linear'); return false;
    });

    /* Webicosoft Re-Initialize Functions */
    $.fn.WCS_REINIT = function($this){
        /* Bootstrap Popover, Tooltip */
        $('[data-toggle="popover"]').popover();
        $('[data-hover="tooltip"]').tooltip({trigger: "hover"});

        /* Bootsrap Datepicker */
        if($('[data-provide="datepicker"]').length) {
            $('[data-provide="datepicker"]').each(function() {
            $(this).datepicker({
                autoclose: true,
                todayHighlight: true,
                //format: 'yyyy-mm-dd'
                });
            });
        }

        /* Select2 Plugin */
        if($('.select2').length) {
            $('.select2').each(function() {
                $(this).select2({
                    // tags: true,
                    width: "100%",
                    dropdownParent: $(this).parent(),
                    placeholder: $(this).attr('placeholder')
                });
            });
        }
    }; $.fn.WCS_REINIT();

    /* Laravel Ajax Call for Bootsrap Modal */
    $.fn.WCS_AJAX_MODAL = function($this){
        var $modal = $($this.data('target'));
        var action = $this.data('action') || $this.closest('form').attr('action');
        var modal_action = $modal.find('form').attr('action');
        if($this.data('tab')) { $modal.find('.modal-tab').val($this.data('tab')); }
        if($this.data('title')) { $modal.find('.modal-title').text($this.data('title')); }
        if(! modal_action || modal_action != action) { $modal.find('form').attr('action',action); }
        if($this.data('target') && ! $modal.find('#dynamic-content').length) { return false; }
        $modal.find('#dynamic-content').html('<h1 class="text-center p-5"><i class="fa fa-spinner fa-spin"></i></h1>');
        var data = $this.find('form').length ? $this.find('form').serialize() : $this.attr('name') +"="+ ($this.prop('checked') ? 1 : 0);
        /* Ajax Call Function */
        $.ajax({
            type: "GET",
            url: action,
            data: data,
            dataType: "html",
            success: function (data) {
                // console.log(data);
                $modal.find('#dynamic-content').removeClass('p-5').html(data);
                $.fn.WCS_REINIT();
                if ($this.hasClass('dynamic-switch')) {
                    $.notify({message: 'Changes successfully'}, {type:'success'});
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
                $modal.find('#dynamic-content').addClass('p-5').html(errorThrown);
                if ($this.hasClass('dynamic-switch')) {
                    $this.prop('checked', $this.is(':checked') ? false : true);
                    $.notify({message: errorThrown}, {type:'danger'});
                }
            }
        });
    };
    $(document).on('click', '.dynamic-modal', function () {
        $.fn.WCS_AJAX_MODAL($(this));
    }).on('change', 'input.dynamic-switch', function () {
        $.fn.WCS_AJAX_MODAL($(this));
    }).on('hidden.bs.modal', '.modal', function () {
        $('.modal:visible').length && $(document.body).addClass('modal-open');
    });
});

$(function () {
    /*| Change Avatar |*/
    $.fn.readURL = function(input, unique) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("[wcs-file-image="+unique+"]").attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).on('change','[wcs-file-input]', function(){
        $.fn.readURL(this, $(this).attr('wcs-file-input'));
    });
    $(document).on('click','[wcs-file-button]', function() {
        var value = $(this).attr('wcs-file-button');
        $("[wcs-file-input="+value+"]").click();
    });

    /*| Show/Hide Target Section/Tag Using Radio Buttons |*/
    $.fn.WCS_RADIO = function($this) {
        $('[wcs-collapse*="'+$this.attr('name')+'-'+'"]').slideUp();
        $('[wcs-collapse="'+$this.attr('name')+'-'+$this.attr('wcs-radio')+'"]').slideDown();
    };
    $('[wcs-radio]').on('change', function() {
        $.fn.WCS_RADIO($(this));
    }).ready($.fn.WCS_RADIO($('[wcs-radio]:checked')));

    /*| Show/Hide Target Section/Tag Using Checkbox Buttons |*/
    $.fn.WCS_CHECKBOX = function($this) {
        var $checkbox = $('[wcs-toggle="'+ ($this.attr('wcs-checkbox') || $this.val()) +'"]');
        $this.is(':checked') ? $checkbox.slideDown() : $checkbox.slideUp();
    };
    $(document).on('change', '[wcs-checkbox]', function() {
        $.fn.WCS_CHECKBOX($(this));
    }).ready( $('[wcs-checkbox]').each(function(){$.fn.WCS_CHECKBOX($(this));}) );

    /*| Show/Hide Target Section/tag Using Select Options |*/
    $.fn.WCS_SELECT = function($this) {
        $this.children().each(function() {
            if($(this).val() == $this.val()) {
                $('[wcs-collapse="opt-'+$(this).val()+'"]').slideDown();
            } else {
                $('[wcs-collapse="opt-'+$(this).val()+'"]').slideUp();
            }
        });
    };
    $('[wcs-select]').on('change', function() {
      $.fn.WCS_SELECT($(this));
    }).ready($.fn.WCS_SELECT($('[wcs-select]')));

    /*| Copy and Paste with Section |*/
    $.fn.WCS_CLONE = function($this, attr) {
        var $clone = $('['+attr+'="'+$this.attr('wcs-more')+'"]').last().clone();
        $('[wcs-parent-paste="'+$this.attr('wcs-parent')+'"]').append($clone);
        $clone.slideDown(); $.fn.WCS_CLONE_ROW($this);
    };
    $(document).on('click', '[wcs-more]', function() {
        $.fn.WCS_CLONE($(this), 'wcs-clone');
    });
    $.fn.WCS_DELETE_CLONE = function($this, attr) {
        $this.closest('['+attr+'="'+$this.attr('wcs-delete')+'"]').slideUp(function() {
            $(this).remove(); $.fn.WCS_CLONE_ROW($this);
        });
    };
    $(document).on('click', '[wcs-delete]', function() {
        $.fn.WCS_DELETE_CLONE($(this), 'wcs-clone');
    });

    $.fn.WCS_CLONE_ROW = function($this) {
        var attr = $this.is('[wcs-more]') ? $this.attr('wcs-more') : $this.attr('wcs-delete');
        var rows = $('[wcs-parent-paste="'+ attr +'"]').find('[wcs-row*="'+ attr +'"]:visible');
        if (rows.length) {
            $.each(rows, function (i, row) {
                $.each($(this).find('[wcs-field]'), function (j, field) {
                    if (txt = $(this).attr('wcs-increment')) { $(this).val(txt + ' ' + (i+1)); }
                    $(this).attr('name', attr + '['+ i +']' + '['+ $(this).attr('wcs-field') +']');
                });
            });
        }
    };

    /*| Table Sorting In Ascending/Cescending |*/
    $.fn.WCS_SORTING_TABLE = function(order, index, $table) {
        var $rows = $table.find('tbody').find('tr').get();

        $rows.sort(function(a, b) {
            var A = get(a), B = get(b);
            if(A < B) {return -1*order;} else {return 1*order;}
            return 0;
        });

        function get(elm){
            var v = $(elm).children('td').eq(index).text().toUpperCase();
            if($.isNumeric(v)){v = parseInt(v,10);} return v;
        }

        $.each($rows, function(index, row) { $table.children('tbody').append(row); });
    };
    // table sorting ascending/descending order onclick
    var sortOrder = 1;
    $(document).on('click', '[wcs-sorting-table] th, [wcs-sorting-table] .sorted:not(th)', function() {
        sortOrder *= -1;
        if ($(this).hasClass('not-sorted')) {return false;}
        var index = $(this).prevAll().length;
        var $table = $(this).closest('[wcs-sorting-table]');
        var thead = $table.children('thead');
        $.fn.WCS_SORTING_TABLE(sortOrder, index, $table);
        // change the caret icon
        $table.find('thead svg').not($(this).find('svg')).removeClass('fa-caret-up').addClass('fa-caret-down');
        $(this).children('svg').toggleClass('fa-caret-up fa-caret-down');
    });

    /*| Checked/Unchecked Table Row(s) |*/
    $(document).on('change', 'table input', function() {
        var isAllChecked = true, $table = $(this).closest('table');
        if ($(this).hasClass('checkAll')) {
            if (this.checked) {
                $table.find('tbody input').each(function() {this.checked = true;});
            } else {
                $table.find('tbody input').each(function() {this.checked = false;});
            }
        } else {
            $table.find('tbody input').each(function() { if (!this.checked) {isAllChecked = false;} });
            if (isAllChecked) {
                $table.find('.checkAll').prop('checked', true);
            } else {
                $table.find('.checkAll').prop('checked', false);
            }
        }
    });

    /*| Textarea Count Characters |*/
    $.fn.WCS_COUNTER = function() {
        if (!$(this).length) { return false; }
        $('[wcs-counter-'+$(this).attr('wcs-counter')+']').text($(this).val().length || 0);
    }
    $(document).on('keyup focus', '[wcs-counter]', function() {
        $(this).WCS_COUNTER();
    }).ready($('[wcs-counter]').WCS_COUNTER());

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
        } else {$input.val('');}
    });

    /*| Checkbox/Radio Button Toggle |*/
    $(document).on('click', '.btn-toggle', function() {
        $(this).find('.btn').toggleClass('active');
        $(this).find('.btn.active').children('input').prop('checked', true);
    });

    /*| Get all dates between range |*/
    $.fn.WCS_GET_DATES = function(startDate, endDate) {
        var dates = [], currentDate = new Date(startDate);
        while (currentDate <= endDate) {
            dates.push(new Date(currentDate));
            currentDate.setDate(currentDate.getDate() + 1);
        }
        return dates;
    };

    // /*| Set the date format |*/
    $.fn.WCS_DATE_FORMAT = function (date, format = '-') {
        var date = new Date(date),
        month = ("0" + (date.getMonth()+1)).slice(-2),
        day   = ("0" + date.getDate()).slice(-2);
        return [ date.getFullYear(), month, day ].join(format);
    }

    // /*| Get profit, discount price |*/
    $.fn.WCS_PROFIT = function (purchase, sale) {
        return parseFloat(sale - purchase).toFixed(2);
    }
    $.fn.WCS_DISCOUNT = function (price, discount = null) {
        var discount_price = discount && discount <= 100 ? price * (1 - discount / 100) : price;
        return parseFloat(discount_price).toFixed(2);
    }
    $(document).on('focus keyup', '[wcs-price], [wcs-discount]', function() {
        if ($(this).is('[wcs-price]')) {
            var attr = $(this).attr('wcs-price'), price = $(this).val(); discount = $('[wcs-discount="'+ attr +'"]').val();
        }
        if ($(this).is('[wcs-discount]')) {
            if ($(this).val() > 100) { $(this).val(0); }
            var attr = $(this).attr('wcs-discount'), discount = $(this).val(); price = $('[wcs-price="'+ attr +'"]').val();
        }
        if (!price && !discount) { return false; }
        var discount_price = $.fn.WCS_DISCOUNT(price, discount) || '-';
        $('[wcs-discount-price*="'+ attr +'"]').html(discount_price).val(discount_price);
    });

    $.fn.WCS_USD = function ($this) {
        var usd = parseFloat($this.val()).toFixed(3);
        if (isNaN(usd) || usd > 1) { return $this.val(''); }
        var rate = usd == 0 ? '-' : (1 / usd).toFixed(3);
        $('[wcs-uae]').html(rate).val(rate);
    }
    $(document).on('focus keyup', '[wcs-usd]', function() {
        $.fn.WCS_USD($(this));
    }).on('ready', $.fn.WCS_USD($('[wcs-usd]')));
});