useraktif();

loadlkh();

function resetform_komentar(){
    $(".komentar").val("");
}

$(document).on("submit",".fkomentar",function(e) {
    e.preventDefault();
    let formVal = $(this).serialize();
    appAjax("dashboard/simpan_komentar", formVal).done(function(vRet) {        
        showNotification(vRet.status, vRet.pesan);
        resetform_komentar();
        loadlkh();
    });
});

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

function loadlkh(){
    let formVal={
        "modeinput":false,
        "idaktifitas":$("#idaktifitas").val(),
    }
    appAjax("dashboard/read_aktifitas", formVal).done(function(vRet) {        
        $("#daftarlkh").html(vRet.html);
    });
}

function refreshlkh(){
    $("#daftarlkh").empty();
    loadlkh();
}
