    function mainmenu(id)
    {
        // var ids = id.id + '-child';
        // var flg = false;
        // if(document.getElementsByClassName(id.id+"-parent")[0].checked)
        // {
        //     flg=true;
        // }
        // var chk = document.getElementsByClassName(ids);
        // var i=0;
        // for(i=0;i<chk.length;i++)
        // {
        //     chk[i].checked = flg;
        // }

        // var unchk = $(id).data('id');
        // if(unchk)
        // {
        //    $("." + unchk + "-parent").prop("checked", false);
        //    if($("." + unchk + "-parent").data("id")!=null)
        //    {
        //        var pid = $("." + unchk + "-parent").data("id");
        //        $("." + pid + "-parent").prop("checked", false);
        //    }
        // }
        // checkPar(flg,unchk);
        var ids = id.id + '-child';
        var sbck=0;
        if($(id).attr('data-id')>0)
        {
            sbck = $(id).attr('data-id');
        }
        var chk = $(id).is(":checked");
        $("."+ids).prop('checked', chk);
        if(sbck!=0)
        {
            var mck = $('.'+sbck+'-parent').attr('data-id');//second step element of checkbox
            if($('.'+sbck+'-child:checked').length == 0) 
            {
                $("."+sbck+"-parent")[0].checked = false;
                $("."+sbck+"-parent").val(0);
                //$("."+sbck+"-parent").prop('checked', false);
                if($('.'+mck+'-child:checked').length == 0) 
                {
                    $("."+mck+"-parent")[0].checked = false;
                    $("."+mck+"-parent").val(0);
                    //$("."+mck+"-parent").prop('checked', false);
                }
            } 
            else if ($('.'+sbck+'-child:not(:checked)').length == 0) 
            {
                $("."+sbck+"-parent")[0].checked = true;
                $("."+sbck+"-parent").val(1);
                //$("."+sbck+"-parent").prop('checked', true);
                if($('.'+mck+'-child:not(:checked)').length == 0) 
                {
                    $("."+mck+"-parent").val(1);
                    $("."+mck+"-parent")[0].checked = true;
                    //$("."+mck+"-parent").prop('checked', true);
                }
            }
            else
            {
                $("."+sbck+"-parent")[0].checked = true;
                $("."+sbck+"-parent").val(1);
                $("."+mck+"-parent")[0].checked = true;
                $("."+mck+"-parent").val(1);
                //$("."+mck+"-parent").prop('checked', true);
                //$("."+sbck+"-parent").prop('checked', true); 
            }
        }


        // if($('.'+mck+'-child:checked').length == 0) 
        // {
        //     $("."+mck+"-parent").prop('checked', false);
        //     $("."+sbck+"-parent").prop('checked', false);
        // } 
        // else
        // {
        //     $("."+mck+"-parent").prop('checked', true);
        //     if($('.'+sbck+'-child:checked').length == 0) 
        //     {
        //         $("."+mck+"-parent").prop('checked', false);
        //         $("."+sbck+"-parent").prop('checked', false);
        //         // if($('.'+mck+'-child:checked').length == 0) 
        //         // {
        //         //     $("."+mck+"-parent").prop('checked', false);
        //         //     $("."+sbck+"-parent").prop('checked', false);
        //         // }
        //     } 
        //     else if ($('.'+sbck+'-child:not(:checked)').length == 0) 
        //     {
        //         $("."+mck+"-parent").prop('checked', true);
        //         $("."+sbck+"-parent").prop('checked', true);
        //     }
        //     else
        //     {
        //         $("."+mck+"-parent").prop('checked', true);
        //         $("."+sbck+"-parent").prop('checked', true); 
        //     }
        // }
    }


    // function mainmenu(id)
    // {
    //     var ids = id.id + '-child';
    //     var check = $('#'+id.id).is(":checked");
    //     var sbck = $('#'+id.id).attr('data-id');//last step element  of checkbox
    //     var mck = $('.'+sbck+'-parent').attr('data-id');//second step element of checkbox
    //     var suck = $('.'+mck+'-parent').attr('data-id');//first step element of checkbox
    //     // var totalSeen = $('.'+sbck+'-child'+':checked').length;
    //     if($('.'+sbck+'-child:checked').length == 0) 
        // {
        //     $("."+mck+"-parent").removeClass('some_selected').prop('checked', false);
        //     $("."+sbck+"-parent").removeClass('some_selected').prop('checked', false);
        // } 
        // else if ($('.'+sbck+'-child:not(:checked)').length == 0) 
        // {
        //     $("."+mck+"-parent").removeClass('some_selected').prop('checked', true);
        //     $("."+sbck+"-parent").removeClass('some_selected').prop('checked', true);
        // }
        // else
        // {
        //     $("."+mck+"-parent").addClass('some_selected').prop('checked', true);
        //     $("."+sbck+"-parent").addClass('some_selected').prop('checked', true); 
        // }
       
    // }

    function checkPar(flg,unchk)
    {
        var flg1=0;
        var chkp = document.getElementsByClassName(unchk + "-child");
        var j=0;
        for(j=0;j<chkp.length;j++)
        {
            if(chkp[j].checked != flg)
            {
                flg1=1;
                break;
            }
        }
        if(flg1==0)
        {
            $("." + unchk + "-parent").prop("checked", flg);
            if($("." + unchk + "-parent").data("id")!=null)
            {
                var unchk1 = $("." + unchk + "-parent").data("id");
                var flg1=0;
                var chkp = document.getElementsByClassName(unchk1 + "-child");
                var j=0;
                for(j=0;j<chkp.length;j++)
                {
                    if(chkp[j].checked != flg)
                    {
                        flg1=1;
                        break;
                    }
                }
                if(flg1==0)
                {
                    $("." + unchk1 + "-parent").prop("checked", flg);
                }
            }
        }
    }
    // function hideall()
    // {
    //     var hide = document.getElementsByClassName('hideall')[0].checked;
    //     if(hide == true)
    //     {
    //         $('.viewport').hide();
    //         //$('#roletype').val('N/A');
    //     }
    //     else
    //     {
    //         $('.viewport').show();
    //         $(".subdiv").prop("checked", false);
    //     }
    // }
    function Save()
    {
        var flag = 0;
        var dataarray = new Array(256);
        var subdivcnt = document.getElementsByClassName("subdiv");
        // if(document.getElementsByClassName('hideall')[0].checked)
        // {
        //     flag =1;
        // }
        var j=0;
        for(j=0;j<256;j++)
        {
            dataarray[j] = flag;  
        }
        dataarray[0] = 1;
        if(flag == 0)
        {
            var k=0;
            for(k=0;k<subdivcnt.length;k++)
            {
                 
                if(subdivcnt[k].checked)
                {
                     dataarray[subdivcnt[k].id] = 1;
                }
            }
        }
        var binarydata = dataarray.join('');
        var role = $('#roletype').val();
        $.ajax({
            type: "POST",
            url: "loaddata.php",
            data: {action : 'permission', binarycode: binarydata, role: role},
            dataType: 'json',
            success: function(data) {
                if(data.status == 1)
                {
                   //alert('Inserted successfully.');
                   myAlert("Insert @#@ Data has been inserted successfully.@#@success");
                }
                else
                {
                   //alert('Data Not Inserted successfully.');
                   myAlert("Insert Error @#@ Data can not been inserted.@#@danger");
                }
            }
        });
    }
    function viewpermission()
    {
        var role = $('#roletype').val();
        if(role > 0)
        {
              $('.viewport').show();
             // $("#all").prop("disabled", false);
              $.ajax({
                type: "POST",
                url: "loaddata.php",
                data: {action : 'viewpermission', role: role},
                dataType: 'json',
                success: function(data) {
                    if(data.status == 1)
                    {
                        $('input[type=checkbox]').prop('checked', false);
                        var result_data = data.permissioncode;
                        console.log(result_data);
                        var str = result_data.split('');
                        var l=0;
                        for(l=0;l<256;l++)
                        {
                            if(str[l]=='1' && l!=0)
                            {
                               // document.getElementById(l+'').checked = true;
                               $('input[id='+l+']').prop('checked', true);
                                checkPar(true,l);
                            }
                        }
                    }
                    else
                    {
                         $(".subdiv").prop("checked", false);
                    } 
                }
            });
        }
        else
        {
             $('.viewport').hide();
            // $("#all").prop("disabled", true);
        }  
    }