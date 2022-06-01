
<script src="dist/js/custom.min.js"></script>
<script src="dist/js/sidebarmenu.js"></script>
<script src="dist/js/pages/toastr.js"></script>
<script src="dist/js/pages/validation.js"></script>
<script src="dist/js/perfect-scrollbar.jquery.min.js"></script>

<script src="../assets/node_modules/popper/popper.min.js"></script>
<script src="../assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
<script src="../assets/node_modules/nestable/jquery.nestable.js"></script>
<script src="../assets/node_modules/sweetalert/sweetalert.min.js"></script>
<script src="../assets/node_modules/toast-master/js/jquery.toast.js"></script>
<script src="../assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
<script src="../assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="../assets/node_modules/sweetalert/jquery.sweet-alert.custom.js"></script>
<script src="../assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
<script src="../assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="../assets/node_modules/multiselect/js/jquery.multi-select.js"></script>
<script src="../assets/node_modules/datatables/datatables.min.js"></script>
<script src="../assets/node_modules/bootstrap-switch/bootstrap-switch.min.js"></script>
<script src="../assets/node_modules/dropify/dist/js/dropify.min.js"></script>
<script src="../assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
<script src="dist/js/validation.js"></script>
<script src="dist/js/perfect-scrollbar.jquery.min.js"></script>

<script>
    function myAlert(data)
    {
        // var msg = (JSON.stringify(data)).split('\":\"')[1].split('\"')[0].split("@#@");
        var msg = data.split("@#@");
           
                switch(msg[2])
                  {
                    case "success":
                         $.toast({
                        heading: msg[0],
                        text: msg[1],
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'success',
                        hideAfter: 3500
                        //stack: 6
                      });
                     
                        break;
                    case "warning":
                        $.toast({
                        heading: msg[0],
                        text: msg[1],
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'warning',
                        hideAfter: 3500, 
                       // stack: 6
                      });
                        break;
                    case "danger":
                        $.toast({
                        heading: msg[0],
                        text: msg[1],
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'error',
                        hideAfter: 3500
                        
                      });
                        
                        break;
                    case "info":
                         $.toast({
                        heading: msg[0],
                        text: msg[1],
                        position: 'top-right',
                        loaderBg:'#ff6849',
                        icon: 'info',
                        hideAfter: 3000, 
                       // stack: 6
                      });
                        
                        break;
                  }
    
}
 
    <?php include('tosteralert.js'); ?>
</script>
<script>
    $(function () {

        // Switchery

        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));

        $('.js-switch').each(function () {

            new Switchery($(this)[0], $(this).data());

        });

        // For select 2

        $(".select2").select2();

        $('.selectpicker').selectpicker();

        //Bootstrap-TouchSpin

        $(".vertical-spin").TouchSpin({

            verticalbuttons: true

        });

        var vspinTrue = $(".vertical-spin").TouchSpin({

            verticalbuttons: true

        });

        if (vspinTrue) {

            $('.vertical-spin').prev('.bootstrap-touchspin-prefix').remove();

        }

        $("input[name='tch1']").TouchSpin({

            min: 0,

            max: 100,

            step: 0.1,

            decimals: 2,

            boostat: 5,

            maxboostedstep: 10,

            postfix: '%'

        });

        $("input[name='tch2']").TouchSpin({

            min: -1000000000,

            max: 1000000000,

            stepinterval: 50,

            maxboostedstep: 10000000,

            prefix: '$'

        });

        $("input[name='tch3']").TouchSpin();

        $("input[name='tch3_22']").TouchSpin({

            initval: 40

        });

        $("input[name='tch5']").TouchSpin({

            prefix: "pre",

            postfix: "post"

        });

        // For multiselect

        $('#pre-selected-options').multiSelect();

        $('#optgroup').multiSelect({

            selectableOptgroup: true

        });

        $('#public-methods').multiSelect();

        $('#select-all').click(function () {

            $('#public-methods').multiSelect('select_all');

            return false;

        });

        $('#deselect-all').click(function () {

            $('#public-methods').multiSelect('deselect_all');

            return false;

        });

        $('#refresh').on('click', function () {

            $('#public-methods').multiSelect('refresh');

            return false;

        });

        $('#add-option').on('click', function () {

            $('#public-methods').multiSelect('addOption', {

                value: 42,

                text: 'test 42',

                index: 0

            });

            return false;

        });

        $(".ajax").select2({

            ajax: {

                url: "https://api.github.com/search/repositories",

                dataType: 'json',

                delay: 250,

                data: function (params) {

                    return {

                        q: params.term, // search term

                        page: params.page

                    };

                },

                processResults: function (data, params) {

                    // parse the results into the format expected by Select2

                    // since we are using custom formatting functions we do not need to

                    // alter the remote JSON data, except to indicate that infinite

                    // scrolling can be used

                    params.page = params.page || 1;

                    return {

                        results: data.items,

                        pagination: {

                            more: (params.page * 30) < data.total_count

                        }

                    };

                },

                cache: true

            },

            escapeMarkup: function (markup) {

                return markup;

            }, // let our custom formatter work

            minimumInputLength: 1,

            //templateResult: formatRepo, // omitted for brevity, see the source of this page

            //templateSelection: formatRepoSelection // omitted for brevity, see the source of this page

        });

    });
</script>
<script>
    
    jQuery('.mydatepicker, #datepicker').datepicker();

    jQuery('#datepicker-autoclose').datepicker({

        autoclose: true,

        todayHighlight: true

    });

    jQuery('#date-range').datepicker({

        toggleActive: true

    });

    jQuery('#datepicker-inline').datepicker({

        todayHighlight: true

    });

     $(document).ready(function() {

        // Nestable

        var updateOutput = function(e) {

            var list = e.length ? e : $(e.target),

                output = list.data('output');

            if (window.JSON) {

                output.val(window.JSON.stringify(list.nestable('serialize'))); //, null, 2));

            } else {

                output.val('JSON browser support required for this demo.');

            }

        };



        $('#nestable').nestable({

            group: 1

        }).on('change', updateOutput);



        updateOutput($('#nestable').data('output', $('#nestable-output')));


        $('#nestable-menu').on('click', function(e) {

            var target = $(e.target),

                action = target.data('action');

            if (action === 'expand-all') {

                $('.dd').nestable('expandAll');

            }

            if (action === 'collapse-all') {

                $('.dd').nestable('collapseAll');

            }

        });


        $('#nestable-menu').nestable();

    });

  
</script>




