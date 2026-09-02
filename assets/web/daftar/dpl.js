var dtTable = null;
loadTabel();

//datatables, menampilkan data
function loadTabel() {
    dtTable = $('.table-data').DataTable({
        "autoWidth": false,
        "bDestroy": true,
        "processing": false,
        "serverSide": true,
        "lengthMenu": [
            [25, 50, 75, -1],
            ["25", "50", "75", "Semua"]
        ],
        "ajax": {
            "url": vBase_url + "web/read_dpl",
            "dataType": "json",
            "type": "POST",
            "data": function (d) {
                d.idkkn = $("#idkkn").val();
            },
            "dataSrc": function (json) {
                return json.data;
            },
        },
        dom: '<"row"<"col-sm-6"><"col-sm-6"f>> rt <"row"<"col-sm-4"l><"col-sm-4"i><"col-sm-4"p>>',
        buttons: [
            'copy', 'excel', 'print'
        ],
        "order": [
            [4, "asc"],
            [6, "asc"],
        ],
        "columns": [
        {
            "data": "no",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "detdpl",
            "width": "50%",
            "orderable": false,
            "searchable": false
        },
        {
            "data": "kel",
            "width": "10%",
        },
        {
            "data": "detkelompok",
            "width": "35%",
            "orderable": false,
            "searchable": false
        },
        {//4
            "data": "nama",
            "visible": false,
        },
        {//5
            "data": "nip",
            "visible": false,
        },
        {//6
            "data": "namakelompok",
            "visible": false,
        },
        {//7
            "data": "desa",
            "visible": false,
        },
        {
            "data": "email",
            "visible": false,
        },
        ],
        initComplete: function (e) {
            var api = this.api();
            $('#' + e.sTableId + '_filter input').off('.DT').on('keyup.DT', function (e) {
                if (e.keyCode == 13) {
                    api.search(this.value).draw();
                }
            });
        },
    });
}

//fungsi refresh
$(".refreshData").click(function () {
    dtTable.ajax.reload(null, false);
});

// Click badge aktivitas -> buka modal
$(document).on("click", ".btn-modal-aktifitas", function (e) {
    e.preventDefault();
    var idkelompok = $(this).data("idkelompok");
    var namadpl = $(this).data("namadpl") || "DPL";
    var namakelompok = $(this).data("namakelompok");

    $("#modalDplNama").text(namadpl);
    $("#modalDplKelompok").text(namakelompok ? "Kelompok " + namakelompok : "Belum ditentukan kelompok");
    $("#modalKontenAktifitas").empty();
    $("#modalLoading").removeClass("d-none");
    $("#modalTotalAktifitas").text("Memuat...");

    var myModal = new bootstrap.Modal(document.getElementById('modalAktifitasDpl'));
    myModal.show();

    if (!idkelompok) {
        $("#modalLoading").addClass("d-none");
        $("#modalTotalAktifitas").text("0 Aktivitas");
        $("#modalKontenAktifitas").html('<div class="alert alert-light text-center py-4 text-muted"><i class="bi bi-info-circle fs-3 d-block mb-2"></i>DPL belum ditempatkan pada kelompok periode ini.</div>');
        return;
    }

    $.ajax({
        url: vBase_url + "web/read_aktifitas_dpl",
        type: "POST",
        dataType: "json",
        data: { idkelompok: idkelompok },
        success: function (res) {
            $("#modalLoading").addClass("d-none");
            if (res.status && res.db.length > 0) {
                $("#modalTotalAktifitas").text(res.db.length + " Aktivitas");
                var html = '<div class="timeline-activity">';
                $.each(res.db, function (i, item) {
                    var fotoHtml = "";
                    if (item.path) {
                        fotoHtml = '<div class="mt-2 mb-2">' +
                            '<a href="' + vBase_url + item.path + '" target="_blank">' +
                            '<img src="' + vBase_url + item.path + '" class="img-fluid rounded border" style="max-height: 220px; object-fit: cover;">' +
                            '</a>' +
                            '</div>';
                    }

                    var mapsHtml = "";
                    if (item.latitude && item.longitude) {
                        mapsHtml = '<div class="mt-1">' +
                            '<a href="https://www.google.com/maps?q=' + item.latitude + ',' + item.longitude + '" target="_blank" class="btn btn-sm btn-outline-secondary rounded-pill">' +
                            '<i class="bi bi-geo-alt-fill text-danger"></i> Lihat Koordinat Peta' +
                            '</a>' +
                            '</div>';
                    }

                    html += '<div class="card mb-3 border shadow-none bg-light">' +
                        '<div class="card-body p-3">' +
                        '<div class="d-flex justify-content-between align-items-center mb-2">' +
                        '<span class="badge bg-secondary"><i class="bi bi-calendar3"></i> ' + (item.waktu || item.created) + '</span>' +
                        '</div>' +
                        '<div class="text-dark mb-2" style="white-space: pre-line;">' + item.uraian + '</div>' +
                        fotoHtml +
                        mapsHtml +
                        '</div>' +
                        '</div>';
                });
                html += '</div>';
                $("#modalKontenAktifitas").html(html);
            } else {
                $("#modalTotalAktifitas").text("0 Aktivitas");
                $("#modalKontenAktifitas").html('<div class="alert alert-light text-center py-4 text-muted"><i class="bi bi-inbox fs-3 d-block mb-2"></i>Belum ada aktivitas yang dicatat untuk periode ini.</div>');
            }
        },
        error: function () {
            $("#modalLoading").addClass("d-none");
            $("#modalTotalAktifitas").text("Error");
            $("#modalKontenAktifitas").html('<div class="alert alert-danger text-center py-3">Gagal memuat data aktivitas. Silakan coba lagi.</div>');
        }
    });
});