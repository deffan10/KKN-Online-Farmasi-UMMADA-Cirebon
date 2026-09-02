<section class="section">
    <input type="hidden" id="idkkn" name="idkkn" value="<?= $idkkn ?>">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title d-flex">
                <i class="bx bx-check font-medium-5 pl-25 pr-75"></i>
                DAFTAR DPL <?= $this->config->item('app_singkatan') ?>
                <?= $datakkn['tema'] . " (" . $datakkn['jenis'] . ")" ?>
            </h4>

            <div class="list-inline d-flex">
                <div class="buttons">
                    <a href="#" class="btn icon btn-success refreshData"><i class="bi bi-arrow-clockwise"></i></a>
                </div>
            </div>

        </div>

        <div class="card-body">
            <div class="table-responsive">
                <a href="<?= base_url('dashboard/kkn/' . $idkkn) ?>" class="btn btn-primary rounded-pill"><i class="bi bi-stack"></i> Dashboard <?= $this->config->item('app_singkatan') ?></a>
                <i class="bi bi-pin-map-fill"></i> <?= $datakkn['tempat'] ?>

                <table class="table table-striped table-sm table-data">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">DPL</th>
                            <th style="vertical-align: middle;" scope="col">Jenis Kelamin</th>
                            <th style="vertical-align: middle;" scope="col">Kelompok</th>
                            <th style="vertical-align: middle;" scope="col"></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Detail Aktivitas DPL -->
    <div class="modal fade" id="modalAktifitasDpl" tabindex="-1" aria-labelledby="modalAktifitasDplLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAktifitasDplLabel"><i class="bi bi-journal-text me-2"></i> Aktivitas DPL</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <div>
                            <h6 class="mb-0 text-primary" id="modalDplNama">-</h6>
                            <small class="text-muted" id="modalDplKelompok">-</small>
                        </div>
                        <span class="badge bg-primary" id="modalTotalAktifitas">0 Aktivitas</span>
                    </div>
                    <div id="modalLoading" class="text-center py-4 d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat data aktivitas...</p>
                    </div>
                    <div id="modalKontenAktifitas">
                        <!-- Konten aktivitas di-render via JS -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
</section>