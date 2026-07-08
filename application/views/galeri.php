<div class="page-heading">
    <h3>Galeri Kegiatan <?= $this->config->item('app_singkatan') ?></h3>
    <p class="text-muted" style="font-size: 14px;">Koleksi dokumentasi foto kegiatan KKN mahasiswa di lapangan.</p>
</div>

<div class="page-content">
    <section class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
                <div class="card-body p-4 p-md-5">
                    <?php if (count($images) > 0) { ?>
                        <div class="row g-2 justify-content-center">
                            <div class="col-12 col-md-10 col-lg-8">
                                <div class="row g-2">
                                    <?php foreach ($images as $img) { ?>
                                        <div class="col-4 mb-2">
                                            <div class="ratio ratio-1x1 overflow-hidden rounded shadow-sm hover-gallery-container" style="background: #f8f9fa;">
                                                <img src="<?= base_url($img['path']) ?>" class="img-fluid object-fit-cover click-gallery-img" style="cursor: pointer; transition: transform 0.2s;" alt="Foto Kegiatan">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-images" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="mt-3">Belum ada dokumentasi foto kegiatan yang diunggah.</p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.hover-gallery-container {
    border: 1px solid #eee;
}
.hover-gallery-container img:hover {
    transform: scale(1.05);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    $(document).on('click', '.click-gallery-img', function() {
        var src = $(this).prop('src');
        var modalHtml = `
        <div class="modal fade" id="galleryPreviewModal" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content" style="background: transparent; border: none; box-shadow: none;">
                    <div class="modal-body p-0 text-center position-relative">
                        <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5)); z-index: 10;"></button>
                        <img src="${src}" class="img-fluid rounded" style="max-height: 85vh; object-fit: contain; box-shadow: 0 4px 24px rgba(0,0,0,0.25);">
                    </div>
                </div>
            </div>
        </div>`;
        $('#galleryPreviewModal').remove();
        $('body').append(modalHtml);
        var myModal = new bootstrap.Modal(document.getElementById('galleryPreviewModal'));
        myModal.show();
    });
});
</script>
