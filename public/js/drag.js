let empty = $('.seat-horizontal')

$(".seat-vertical").on("touchmove",function(event){
    let touch = event.targetTouches[0];
    $(this).css('position', 'fixed');
    $(this).css('top', `${touch.pageY}px`);
    $(this).css('left', `${touch.pageX}px`);
    var drag = $(this);

    console.log(drag.get(0).getBoundingClientRect());

    empty = jQuery.map( empty, function(item) {
        if(
            drag.get(0).getBoundingClientRect().top + drag.offsetWidth / 2 < item.getBoundingClientRect().bottom
        ){
            alert('match')
        }
        // console.log(item.getBoundingClientRect());
    });
});

$(".seat-vertical").on("touchend",function(event){
    $(this).css('position', 'relative');
    $(this).css('top','');
    $(this).css('left', '');
});



 //-------------- DRAG & DROP COLUMN
$('.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-vertical-wide, .seat-horizontal-oven').attr('draggable', "true");
$('.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-vertical-wide, .seat-horizontal-oven').attr('ondragstart', "drag(event)");
$('.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-vertical-wide, .seat-horizontal-oven').attr('ondrop', "drop(event)");
$('.seat-vertical, .seat-horizontal, .seat-vertical-short, .seat-horizontal-wide, .seat-vertical-wide, .seat-horizontal-oven').attr('ondragover', "allowDrop(event)");


function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    var group = ev.target.getAttribute('grup');
    var posisi = ev.target.getAttribute('position');
    var id = ev.target.id;

    var html = $(`#${id}`).html();
    if (html) {
        ev.dataTransfer.setData("grup", String(group));
        ev.dataTransfer.setData("posisi", String(posisi));
        ev.dataTransfer.setData("id", String(id));
    } else {
        alert("Seat Masih Kosong");
    }

}

function drop(ev) {
    ev.preventDefault();
    var grup = ev.dataTransfer.getData("grup", grup);
    var posisi = ev.dataTransfer.getData("posisi", posisi);
    var prevId = ev.dataTransfer.getData("id", posisi);

    var newGrup = ev.target.getAttribute('grup');
    var newPosisi = ev.target.getAttribute('position');
    var newId = ev.target.id;

    var html = String($(`#${newId}`).html()).trim().replace(/^\s+|\s+$/gm,'');

    if (html == '') {
        $.ajax({
            type: "POST",
            url: "/parkir/update_posisi",
            data: {
                grup: grup,
                posisi: posisi,
                newGrup: newGrup,
                newPosisi: newPosisi
            },
            dataType: "json",
            success: function(response) {
                $(`#${prevId}`).html("");
                $(`#${newId}`).html(response.model_code + ' | ' + response.license_plate + ' <br> ' + response.category);
            },
            error: function() {
                location.reload();
            }
        });
    } else {
        alert("data sudah terisi");
    }
}