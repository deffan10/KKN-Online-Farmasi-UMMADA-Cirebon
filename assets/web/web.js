var waktu = new Date();
var tahun = waktu.getFullYear();

loadlkh();

function loadlkh(){
    $("#daftarlkh").empty();
    appAjax("dashboard/loadlkh", { mode: 'public' }).done(function(vRet) {        
        if(vRet.status){
            jQuery.each(vRet.db, function(index, item) {
                $("#daftarlkh").append(item);
            });                    
        }else{
            if($("#daftarlkh").html()!="")
                alert("data LKH lainnya sudah tidak ditemukan lagi gaess");
        }
    });
}

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

function refreshlkh(){
    let jumlkh = $('.rowlkh').length;
    $("#daftarlkh").empty();
    loadlkh(0,jumlkh);
}

function resetform_komentar(){
    $(".komentar").val("");
}



$(".act-aktifitas").click(function(e){
    e.preventDefault();
    let vtitle=$(this).html();
    let vkategori=$(this).data("kategori");
    $("#modal-aktifitas .modal-title").html($(this).html());

    // var myModal = new bootstrap.Modal('#modal-aktifitas', {
    //     backdrop: 'static',
    //     keyboard: false,
    // });
    // myModal.toggle();

    var myModal = new bootstrap.Modal(document.getElementById('modal-aktifitas'))
    myModal.show()    

    let cari= {
        title:vtitle,
        kategori:vkategori,
        0:{val: tahun,fld: "k.tahun",cond: "where"},
    };
    appAjax("api/aktifitas_terbaik", cari).done(function(vRet) {  
        if(vRet.status){
            $(".detail-aktifitas-populer").html(vRet.html);
        }      
    });    
});

$(".act-kelompok").click(function(e){
    e.preventDefault();
    let vtitle="Kelompok Teraktif";

    $("#modal-aktifitas .modal-title").html($(this).html());
    var myModal = new bootstrap.Modal(document.getElementById('modal-aktifitas'), {
        backdrop: 'static',
        keyboard: false,
    });
    myModal.toggle();

    let cari= {
        title:vtitle,
        0:{val: tahun,fld: "k.tahun",cond: "where"},
    };
    appAjax("api/kelompok_terkaktif", cari).done(function(vRet) {  
        if(vRet.status){
            $(".detail-aktifitas-populer").html(vRet.html);
        }      
    });    
});

useraktif();

$(document).on("submit",".fkomentar",function(e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    appAjax("dashboard/simpan_komentar", formVal).done(function(vRet) {        
        showNotification(vRet.status, vRet.pesan);
        resetform_komentar();
        refreshlkh();
    });
});
