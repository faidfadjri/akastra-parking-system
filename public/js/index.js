

//------ Modal Click
// $(document).on('click', '.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-horizontal-oven, .seat-vertical-wide', function () { 
//     //----- Set hidden Key from attribute
//     var grup     = $(this).attr('grup');
//     var position = $(this).attr('position');
//     var seatId   = $(this).attr('id');
//     var parking  = $(this).attr('parking-name');

//     const date   = $('#current-date').val();

//     $("#parking-form").trigger("reset");
//     $("#parking-grup").val(grup);
//     $("#parking-position").val(position);
//     $("#seat-id").val(seatId);
//     $("#parking-name").val(parking);
    
//     $("#addModal").modal('show');

//     $.ajax({
//         type: "POST",
//         url: "/parkir/get_detail",
//         data: {
//             grup   : grup,
//             posisi : position,
//             date   : date
//         },
//         dataType: "json",
//         success: function (response) {
//             if(response.code === 200){
//                 const label = ['parking-id','parking-license-plate', 'parking-model', 'parking-other', 'parking-status', 'parking-job', 'parking-technician'];
//                 const field = ['id','license_plate', 'model_code', 'others', 'status', 'category', 'technician'];
//                 const detail = response.data;
//                 if(detail != null){
//                     $("#parking-id").prop('disabled', false);
//                     $(".btn-delete").removeClass('d-none');
//                     label.forEach((element,index) => {
//                         $(`#${element}`).val(detail[field[index]]);
//                     });

//                     if(detail.others){
//                         $("#other-wrap").removeClass("d-none");
//                     } else {
//                         $("#other-wrap").addClass("d-none");
//                     }
//                 }
//             } else {
//                 $("#parking-id").prop('disabled', true);
//                 $("#other-wrap").addClass("d-none");
//                 $(".btn-delete").addClass('d-none');
//             }
//         },
//         error : function (err) { 
//             console.log(err);
//          }
//     });
// })

//----- Button Delete
$(document).on('click', '.btn-delete', function (event) { 
    var posisi = $("#parking-position").val();
    var grup   = $("#parking-grup").val();
    var seatId = $("#seat-id").val();

    if(confirm("Yakin Hapus Data Ini ?")){

        var button = $(this);
        button.html('Please Wait <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Mohon Tunggu</span></div>');
        button.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: "/parkir/delete",
            data: {
                posisi : posisi,
                grup  : grup
            },
            dataType: "json",
            success: function (response) {
                if(response.code === 200){
                    $("#addModal").modal('hide');
                    $(`#${seatId}`).html('');
                } else {
                    alert('Sistem Error');
                    location.reload();
                }
                button.html('Hapus <span class="material-icons">remove_circle</span>');
                button.prop('disabled', false);
            }, error: function(){
                location.reload();
            }
        });
    }
})


//----- Form Submit
$(document).ready(function () {
    $('#parking-form').submit(function (e) { 
        e.preventDefault();
        var form        = $(this);
        var actionUrl   = form.attr('action');
        var seatId      = $("#seat-id").val();

        var button      = $(".btn-submit");
        button.html('Please Wait <div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Mohon Tunggu</span></div>');
        button.prop('disabled', true);

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            dataType: "json",
            success: function (response) {
                if(response.code == 200){
                    var data = response.data;
                    var html = `${data.model_code} | ${data.license_plate}`;
                    if(data.category != 'Tidak Servis'){
                        html += ` <br> ${data.category}`;
                    }

                    $("#addModal").modal('hide');
                    $(`#${seatId}`).html(html);


                    var prevPos  = response.prevPos;
                    var prevGrup = response.prevGrup;
                    
                    // if(prevPos != 0 && prevGrup != 0){
                    //     $(`[position = ${prevPos}][grup = ${prevGrup}]`).html("");
                    // }

                    console.log(response);


                    if(response.redirect) {
                        location.href = response.redirect_url;
                    }

                } else if(response.code == 403) {
                    alert("Duplikasi Data")
                }
                button.html('Simpan <span class="material-icons">save</span>');
                button.prop('disabled', false);
            },
            error : function (err) { 
                button.html('Simpan <span class="material-icons">save</span>');
                button.prop('disabled', false);
                alert("Maaf terjadi kesalahan pada aplikasi")
             }
        });

    });
});


//----- Others Model Condition
$(document).on('change', '#parking-model', function (e) { 
    let model = $(this).val();
    if(model === "OT" || model === "MRL"){
        $("#other-wrap").removeClass("d-none");
    } else {
        $("#other-wrap").addClass("d-none");
        $("#parking-other").val('');
    }
});

//----- Legend
let state = false;

$('.legend').hover(function () {
        $(this).css('opacity', 1);
    }, function () {
        $(this).css('opacity', 0.2);
    }
);

$('.legend').click(function (e) { 
    e.preventDefault();
    if(state == false){
        state = true;
    } else {
        state = false;
    }

    if(state){
        $(this).css('opacity', 1);
    } else {
        $(this).css('opacity', 0.2);
    }
});