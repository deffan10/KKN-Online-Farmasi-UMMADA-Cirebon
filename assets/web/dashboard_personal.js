var latitude = null; 
var longitude = null;
var iduser_session=$("#iduser_session").val();
var iduser=$("#iduser").val();
var steps = [];
itsme=false;
if(iduser_session==iduser){
    itsme=true;
}

var map = L.map('map').setView([vLatitude, vLongitude], 13)
L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
    maxZoom: 20,
    attribution: '<a href="https://github.com/cyclosm/cyclosm-cartocss-style/releases" title="CyclOSM - Open Bicycle render">CyclOSM</a> | Map data: &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);
$("#loadMoreLKH").hide();

function getLocation() {
    let iduser=$("#iduser").val();
    let iduser_session=$("#iduser_session").val();
    if(iduser==iduser_session){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else { 
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }
}

function showPosition(position){
    latitude  = position.coords.latitude; 
    longitude = position.coords.longitude;
}

if(itsme)
    getLocation();


function loadtestimoni(vidkelompok){
    let formVal={idkelompok: vidkelompok,hapus:0}
    let vElmt="#daftar_testimoni";
    $(vElmt).empty();
    $("#card_testimoni").hide();
    $("#testimoni").html("0");
    appAjax("api/readtestimoni", formVal).done(function(vRet) {
        if(vRet.status){
            // jQuery.each(vRet.db, function(index, item) {
            //     $(vElmt).append(item.html);
            // });             
            $(vElmt).append(vRet.html);
            $("#testimoni").html(vRet.db.length);
            $("#card_testimoni").show();
        }        
    });
}
$("#loadMoreLKH").click(function(){
    let lastid = $(".rowlkh:last").data("id");
    loadlkh(lastid);
});

if($('#editor').length){
    var quill = new Quill('#editor', {
        theme: 'snow'
    });
}

if($('#proker').length){
    var quill = new Quill('#proker', {
        theme: 'snow'
    });
}

$('.appcurrency').mask('000.000.000.000.000.000', {
    reverse: true
});

function resetform(){
    $("#kegiatankkn")[0].reset();
    $(".ql-editor").html("");
    $("#idaktifitas").val("");
}

function resetform_komentar(){
    $(".komentar").val("");
}

$("#form-profil-posko").submit(function(e){
    e.preventDefault();

    if(confirm("apakah anda yakin akan menyimpan data tersebut?")){

        let idkelompok = $('#idkelompok').val();
        let idposko = $('#idposko').val();
        let alamat=$("#alamat").val();
        let proker=$("#proker .ql-editor").html();
        let file_data = $('#path').get(0).files[0];   
        let vurl=vBase_url+"mahasiswa/penempatan/update_profil_posko";
        
        let formVal = new FormData();                  
        formVal.append('idkelompok', idkelompok);  
        formVal.append('idposko', idposko);  
        formVal.append('alamat', alamat);  
        formVal.append('proker', proker);  
        formVal.append('latitude', latitude);  
        formVal.append('longitude', longitude);
        formVal.append('file', file_data);        
        //console.log(vurl);
        $.ajax({
            url : vurl,
            type : "post",
            dataType : "json",
            data : formVal,                         
            cache : false,
            contentType : false,
            processData : false,
            success : function(vRet){
                showNotification(vRet.status, vRet.pesan);
                if(vRet.status)
                    loadprofil($("#idkelompok").val());
            }
        });
    }   
});

$(document).on("submit",".fkomentar",function(e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    appAjax("dashboard/simpan_komentar", formVal).done(function(vRet) {        
        showNotification(vRet.status, vRet.pesan);
        resetform_komentar();
        refreshlkh();
    });
});

$("#kegiatankkn").submit(function(e){
    e.preventDefault();
    let $btn = $(this).find("button[type='submit']");
    if($btn.prop("disabled")) return false;
    $btn.prop("disabled", true);

    getLocation();
    let formVal = {
        uraian:$(".ql-editor").html(),
        idkkn:$('#idkkn').val(),
        idpenempatan:$('#idpenempatan').val(),
        waktu:$('#waktu').val(),
        grup:$('#grup').val(),
        estbiaya:$('#estimasi').cleanVal(),
        latitude:latitude,
        longitude:longitude,
    };
    appAjax("dashboard/simpan", formVal).done(function(vRet) {        
        $btn.prop("disabled", false);
        showNotification(vRet.status, vRet.pesan);
        if(vRet.status){
            resetform();
            if(vRet.idpenempatan==$("#idpenempatan").val())
                loadlkh(0);
            else
                window.location.replace(vBase_url+"dashboard/personal/"+vRet.idpenempatan);
        }
    }).fail(function(){
        $btn.prop("disabled", false);
    });
});


$('.datepicker').bootstrapMaterialDatePicker({
    weekStart: 0,
    format: 'YYYY-MM-DD HH:mm:ss',
    time: true,
});

$(window).scroll(function() {
    if($(window).scrollTop() + $(window).height() >= $(document).height()) {
        let lastid = $(".rowlkh:last").data("id");
        loadlkh(lastid);
    }
});

loadlkh(0);

$(document).on('click','.gambardet',function(){
    var gbr=$(this).prop('src');
    var modalHtml = `
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="background: transparent; border: none; box-shadow: none;">
                <div class="modal-body p-0 text-center position-relative">
                    <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); z-index: 10;"></button>
                    <img src="${gbr}" class="img-fluid rounded" style="max-height: 85vh; object-fit: contain; box-shadow: 0 4px 24px rgba(0,0,0,0.25);">
                </div>
            </div>
        </div>
    </div>`;
    $('#imagePreviewModal').remove();
    $('body').append(modalHtml);
    var myModal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
    myModal.show();
});

useraktif();

function loadlkh(lastid,limit=null){
    let formVal={
        "modeinput":true,
        "lastid":lastid,
        "limit":limit,
        "vCari": { 
            0: { cond: 'where', val: $("#idpenempatan").val(), fld: 'a.idpenempatan' },
        },
    }

    if(lastid<1){
        $("#daftarlkh").empty();
    }

    if(lastid>=0)
        appAjax("dashboard/loadlkh", formVal).done(function(vRet) {        
            if(vRet.status){
                $("#loadMoreLKH").show();        
                jQuery.each(vRet.db, function(index, item) {
                    $("#daftarlkh").append(item);
                });                    
            }
            else{
                if(lastid<1){
                    $("#daftarlkh").append(vRet.db[0]);
                }
                $("#loadMoreLKH").hide();        
            }
        });
}

function refreshlkh(){
    let jumlkh = $('.rowlkh').length;
    $("#daftarlkh").empty();
    loadlkh(0,jumlkh);
}

$(document).on("click keypress",".hapus-aktifitas",function(e) {
    let idaktifitas=$(this).data("idaktifitas");
    let formVal={"id":idaktifitas};

    if(confirm("apakah anda yakin?"))
        appAjax("dashboard/hapus_aktifitas", formVal).done(function(vRet) {        
            if(vRet.status){
                refreshlkh();
            }
            showNotification(vRet.status, vRet.pesan);
        });

});

$(document).on("click keypress",".hapus-lampiran",function(e) {
    formVal={
        idupload: $(this).data("idupload"),  
        table:"aktifitas_upload",
    };  

    if(confirm("apakah anda yakin?"))
        appAjax("file/delete", formVal).done(function(vRet) {        
            if(vRet.status){
                refreshlkh();
            }
            showNotification(vRet.status, vRet.pesan);
        });

});
/*
$(document).on("click keypress",".upload-aktifitas",function(e) {
    let idpenempatan=$(this).data("idpenempatan");
    refreshlkh();
});
*/

$(document).on("change",".upload-aktifitas",function(e) {
    let files = $(this).prop('files');
    if(files.length > 0 && confirm("Apakah Anda yakin ingin mengunggah " + files.length + " file tersebut?")){
        let idaktifitas = $(this).data("idaktifitas");
        let idpenempatan = $(this).data("idpenempatan");
        let vurl = vBase_url + "file/upload_aktifitas";
        let uploadCount = 0;
        let successCount = 0;

        for (let i = 0; i < files.length; i++) {
            let file_data = files[i];
            let formVal = new FormData();
            formVal.append('idpenempatan', idpenempatan);
            formVal.append('idaktifitas', idaktifitas);
            formVal.append('file', file_data);

            $.ajax({
                url : vurl,
                type : "post",
                dataType : "json",
                data : formVal,
                cache : false,
                contentType : false,
                processData : false,
                success : function(vRet){
                    uploadCount++;
                    if(vRet.status){
                        successCount++;
                    } else {
                        showNotification(false, vRet.pesan);
                    }
                    if(uploadCount === files.length){
                        if(successCount > 0){
                            refreshlkh();
                        }
                        showNotification(successCount > 0, successCount + " dari " + files.length + " file berhasil diunggah.");
                    }
                }
            });
        }
    }   
});

$(".act-aktifitas").click(function(e){
    e.preventDefault();
    let vtitle=$(this).html();
    let vkategori=$(this).data("kategori");
    $("#modal-aktifitas .modal-title").html($(this).html());
    var myModal = new bootstrap.Modal(document.getElementById('modal-aktifitas'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
    let cari= {
        title:vtitle,
        kategori:vkategori,
        0:{val: $("#idpenempatan").val(),fld: "pm.id",cond: "where"},
    };
    appAjax("api/aktifitas_terbaik", cari).done(function(vRet) {  
        if(vRet.status){
            $(".detail-aktifitas-populer").html(vRet.html);
        }      
    });    
});

function resetformprofil(){
    $("#form-profil-posko")[0].reset();
    $("#proker .ql-editor").html("");
    $("#idposko").val("");
}

$(document).on("click","#baca-semua-notif",function(e){
    e.preventDefault;
    if(confirm("kita yakin tandai semuanya sudah mi dicek?")){
        appAjax("dashboard/update_baca_semua", null).done(function(vRet) {  
            if(vRet.status){
                loadjumnotif();
                loadnotif();
            }      
        });    
    }
});

loadjumnotif();
function loadjumnotif(){
    if(itsme){
        let cari= {
            0:{val: $("#iduser").val(),fld: "n.iduser",cond: "where"},
            1:{val: "0",fld: "n.status",cond: "where"},
        };
        $(".jum-notif").html("0");
        appAjax("api/jum_notif", cari).done(function(vRet) {  
            if(vRet.status){
                $(".jum-notif").html(vRet.db.length);
                //untuk tour notif steps
                steps.push(
                    {
                        element: '.show-notif',
                        title: 'Pemberitahuan',
                        content: 'Tabe\' coba pi kita cek <b>'+vRet.db.length+'</b> pemberitahuanta klik saja itu lonceng, siapatau ada dari DPL. klik tombol <b>"sudah dicek semua!"</b> dipaling bawah daftar norifikasi, supaya dianggap sudah dibaca mi semuanya toh.',
                        placement: 'bottom-start',
                    },
                );
            }
        });   
    }
}

aktifitas_dpl();
function aktifitas_dpl(){
    $(".jum-notif-dpl").html("0");
    $(".detail-notif").html("");
    if(itsme){
        let cari= {
            0:{val: $("#idkelompok").val(),fld: "dpl.idkelompok",cond: "where"},
        };
        appAjax("api/aktifitas_dpl", cari).done(function(vRet) {  
            if(vRet.status){
                $(".jum-notif-dpl").html(vRet.db.length);
                $(".detail-notif").html(vRet.html);
            }
        });
    }
}

$(".aktifitas-dpl").click(function(e){
    if(itsme){
        e.preventDefault();    
        var myModal = new bootstrap.Modal(document.getElementById('modal-notif'), {
            backdrop: 'static',
            keyboard: false,
        });
        aktifitas_dpl();
        myModal.toggle();
    }
});

function loadnotif(){
    if(itsme){
        let cari= {
            0:{val: $("#iduser").val(),fld: "n.iduser",cond: "where"},
        };
        $(".detail-notif").html("");
        appAjax("api/data_notif", cari).done(function(vRet) {  
            if(vRet.status){
                $(".detail-notif").html(vRet.html);
            }
        });    
    }
}

$(".show-notif").click(function(e){
    if(itsme){
        e.preventDefault();    
        var myModal = new bootstrap.Modal(document.getElementById('modal-notif'), {
            backdrop: 'static',
            keyboard: false,
        });
        loadnotif()
        myModal.toggle();
    }
});

$(".profil-posko").click(function(e){
    e.preventDefault();    
    var myModal = new bootstrap.Modal(document.getElementById('modal-profil-posko'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();
    loadprofil($("#idkelompok").val());
});

function formprofil(db){
    resetformprofil();

    $("#idposko").val(db['id']);
    $("#alamat").val(db['alamat']);
    $("#proker .ql-editor").html(db['proker']);
    
    // Set global coordinates from database if they exist
    if(db['latitude'] !== null && db['latitude'] !== "") latitude = db['latitude'];
    if(db['longitude'] !== null && db['longitude'] !== "") longitude = db['longitude'];

    let urlposko=null;
    if(db['path']){
        urlposko=vBase_url+db['path']+"?"+Math.random()*100;
        $("#imgpreview").html("<img src='"+urlposko+"' width='100%'>");
    }

    if(db['latitude'] && db['longitude']){
        let marker = L.marker([db['latitude'], db['longitude']]).addTo(map);
        let caption_marker="<div style='align-text:center;' align='center'><a href='"+vBase_url+"dashboard/kelompok/"+db['idkelompok']+"'><b>Kelompok "+db['namakelompok']+"</b></a><br>";                                
        if(urlposko)
            caption_marker=caption_marker+"<img src='"+urlposko+"' width='100%'>";
        caption_marker=caption_marker+" "+db.provinsi+" "+db.kabupaten+" "+db.kecamatan+" "+db.desa+" ";
        caption_marker=caption_marker+"</div>";
        marker.bindPopup(caption_marker).openPopup();
    }
}

function notifprofil(db){
    //tour notif steps jika ada yang kosong
    if(db['alamat']==null || db['proker']==null || db['latitude']==null || db['longitude']==null){
        steps.push(
            {
                element: '.profil-posko',
                title: 'Pemberitahuan',
                content: 'Silakan periksa profil posko Anda. Apakah program kerja (proker), alamat, koordinat lokasi, dan foto posko sudah benar? Sepertinya masih ada data yang belum lengkap. Silakan berdiskusi dengan kelompok Anda untuk melengkapinya.',
                placement: 'bottom-start',
            },
        );
    }

    if(steps.length>0 && itsme){
        var wt = new WebTour();
        wt.setSteps(steps);
        console.log(steps);
        wt.start();
    }    
}


function loadprofil(idkelompok,statusprofil=true) {
    
    let cari= {
        0:{val: idkelompok,fld: "kl.id",cond: "where"},
    };
    appAjax("api/dataposko", cari).done(function(vRet) {  
        if(vRet.status){
            let db=vRet.db[0];
            loadtestimoni(db.idkelompok);
            if(statusprofil)
                formprofil(db);
            else
                notifprofil(db);
        }
    });

};

loadprofil($("#idkelompok").val(),false);

// Trigger Leaflet invalidateSize when posko modal is shown
$('#modal-profil-posko').on('shown.bs.modal', function () {
    map.invalidateSize();
    if (latitude && longitude) {
        map.setView([latitude, longitude], 13);
    }
});