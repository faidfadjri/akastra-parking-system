// Hitung persentase dan tentukan class status
const hitungPersentase = (usage, capacity, divisi = null) => {
    const result = {};
    const persentase = parseInt(usage) / parseInt(capacity) * 100;

    if (divisi) {
        const u = parseInt(usage);
        if (divisi === 'akm') {
            result.class = u <= 10 ? '' : u <= 12 ? 'bg-warning' : 'bg-danger';
        } else if (divisi === 'bp') {
            result.class = u <= 46 ? '' : u <= 75 ? 'bg-warning' : 'bg-danger';
        } else { // default = gr
            result.class = u <= 34 ? '' : u <= 50 ? 'bg-warning' : 'bg-danger';
        }
    }

    result.persentase = parseInt(persentase);
    return result;
};

// Inisialisasi saat dokumen siap
$(document).ready(function () {
    // Hitung progress kendaraan
    const GR = $("#GRvehicle").val();
    const BP = $("#BPvehicle").val();
    const AKM = $("#AKMvehicle").val();
    const usage = $("#usage").val();
    const capacity = $("#capacity").val();
    const GRcapacity = $("#GRcapacity").val();
    const BPcapacity = $("#BPcapacity").val();
    const AKMcapacity = $("#AKMcapacity").val();

    const overall = hitungPersentase(usage, capacity);
    const gr = hitungPersentase(GR, GRcapacity, 'gr');
    const bp = hitungPersentase(BP, BPcapacity, 'bp');
    const akm = hitungPersentase(AKM, AKMcapacity, 'akm');

    $("#overall-progress").css('width', `${overall.persentase}%`);
    $("#gr-progress").css('width', `${gr.persentase}%`).addClass(gr.class);
    $("#bp-progress").css('width', `${bp.persentase}%`).addClass(bp.class);
    $("#akm-progress").css('width', `${akm.persentase}%`).addClass(akm.class);

    // Notifikasi GR
    if (gr.class === 'bg-warning') {
        $("#gr-list").html(`<div class="alert alert-warning mt-4" role="alert">Atur Booking! Kendaraan sudah cukup banyak</div>`);
    } else if (gr.class === 'bg-danger') {
        $("#gr-list").html(`<div class="alert alert-danger mt-4" role="alert">Stop Penerimaan! Flow kendaraan tidak terkendali</div>`);
    }

    // Notifikasi BP
    if (bp.class === 'bg-warning') {
        $("#bp-list").html(`<div class="alert alert-warning mt-4" role="alert">Atur Booking! Kendaraan sudah cukup banyak</div>`);
    } else if (bp.class === 'bg-danger') {
        $("#bp-list").html(`<div class="alert alert-danger mt-4" role="alert">Stop Penerimaan! Flow kendaraan tidak terkendali</div>`);
    }

    // Handle pencarian kendaraan
    $("#search-form").submit(function (e) {
        e.preventDefault();

        const form = $(this);
        const actionUrl = form.attr('action');

        let keyword = $("#search-keyword").val();
        if(keyword == '') {
            $("#list-wrap").addClass('d-none');
            return
        }

        $.ajax({
            type: "POST",
            url: actionUrl,
            data: form.serialize(),
            dataType: "json",
            beforeSend: function () {
                $(".btn-search").html(`
                    <div class="spinner-border spinner-border-sm text-base" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                `);
            },
            success: function (response) {
                let html = '';
                if (response.code == 200) {
                    response.result.forEach(element => {
                        html += `
                            <a href="/parkir/${element.lokasi.toLowerCase()}/${element.created_at}/${element.id}" class="no-decoration mb-1">
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <span>${element.model_code} | ${element.license_plate}</span>
                                        <span>${element.created_at}</span>
                                    </div>
                                </li>
                            </a>`;
                    });
                } else {
                   html = `<li class="list-group-item text-muted">🥲 Nggak ketemu nih, coba cek lagi ya!</li>`;
                }

                $("#list-wrap").removeClass('d-none');
                $("#list-wrap").html(html);
                $(".btn-search").html('<span class="material-icons">search</span>');
            },
            error: function () {
                $(".btn-search").html('<span class="material-icons">search</span>');
                $("#list-wrap").addClass('d-none');
            }
        });
    });

    $(document).on('click', '.btn-history', function () {
        $("#historyModal").modal('show');
    });
});
