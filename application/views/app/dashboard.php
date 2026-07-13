<div class="page-title">
    <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
            <h3>Dashboard KKN</h3>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
            <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?= base_url("app/dashboard") ?>">Dashboard</a></li>
                </ol>
            </nav>
        </div>
    </div>
</div>
<?php if (!empty($peserta_kkn)) { ?>
    <section class="section mb-4">
        <div class="card shadow-sm border">
            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center py-3">
                <h4 class="card-title text-white mb-0" style="font-size: 1.1rem;"><i class="bi bi-patch-check-fill me-2"></i> Jadwal & Penempatan KKN Anda</h4>
                <span class="badge bg-white text-success font-semibold">Aktif</span>
            </div>
            <div class="card-body pt-4">
                <?php foreach ($peserta_kkn as $pk) { ?>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3"><?= $pk['tema'] ?> (<?= $pk['jenis'] ?>)</h5>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt-fill text-danger fs-5 me-2"></i>
                                        <div>
                                            <span class="text-muted d-block" style="font-size: 0.8rem;">Lokasi / Desa</span>
                                            <strong><?= $pk['tempat'] ?><?= !empty($pk['desa']) ? ' (Desa ' . $pk['desa'] . ')' : ' (Belum ada desa)' ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-people-fill text-info fs-5 me-2"></i>
                                        <div>
                                            <span class="text-muted d-block" style="font-size: 0.8rem;">Kelompok</span>
                                            <strong><?= !empty($pk['namakelompok']) ? $pk['namakelompok'] : 'Menunggu pembagian kelompok' ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-person-badge-fill text-warning fs-5 me-2"></i>
                                        <div>
                                            <span class="text-muted d-block" style="font-size: 0.8rem;">Jabatan / Peran</span>
                                            <strong><?= !empty($pk['jabatan']) ? $pk['jabatan'] : 'Menunggu penempatan' ?></strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-check-fill text-success fs-5 me-2"></i>
                                        <div>
                                            <span class="text-muted d-block" style="font-size: 0.8rem;">Pelaksanaan KKN</span>
                                            <strong><?= date('d M Y', strtotime($pk['kknmulai'])) ?> - <?= date('d M Y', strtotime($pk['kknselesai'])) ?></strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                            <div class="d-grid gap-2">
                                <?php if (!empty($pk['idpenempatan'])) { ?>
                                    <a href="<?= base_url('dashboard/personal/' . $pk['idpenempatan']) ?>" class="btn btn-primary btn-sm"><i class="bi bi-journal-album me-1"></i> Dashboard Logbook</a>
                                    <a href="<?= base_url('mahasiswa/lkh/' . $pk['idpenempatan']) ?>" class="btn btn-success btn-sm"><i class="bi bi-book me-1"></i> Data Logbook</a>
                                <?php } else { ?>
                                    <span class="text-muted italic" style="font-size: 0.85rem;"><i class="bi bi-info-circle me-1"></i> Logbook belum tersedia (Menunggu pembagian kelompok & posko)</span>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>

<?php if (isset($is_dpl) && $is_dpl) { ?>
    <section class="section mb-4">
        <div class="card shadow-sm border">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center py-3">
                <h4 class="card-title text-white mb-0" style="font-size: 1.1rem;"><i class="bi bi-person-workspace me-2"></i> Jadwal & Penugasan Bimbingan KKN (DPL)</h4>
                <span class="badge bg-white text-primary font-semibold"><?= !empty($dpl_kkn) ? 'Aktif' : 'Non-Aktif' ?></span>
            </div>
            <div class="card-body pt-4">
                <?php if (!empty($dpl_kkn)) { ?>
                    <?php foreach ($dpl_kkn as $dk) { ?>
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h5 class="text-primary mb-3"><?= $dk['tema'] ?> (<?= $dk['jenis'] ?>)</h5>
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-geo-alt-fill text-danger fs-5 me-2"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.8rem;">Lokasi / Posko</span>
                                                <strong>Desa <?= !empty($dk['desa']) ? $dk['desa'] : 'Belum ditentukan' ?>, Kec. <?= !empty($dk['kecamatan']) ? $dk['kecamatan'] : 'Belum ditentukan' ?>, <?= !empty($dk['kabupaten']) ? $dk['kabupaten'] : 'Belum ditentukan' ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-people-fill text-info fs-5 me-2"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.8rem;">Kelompok Bimbingan</span>
                                                <strong><?= $dk['namakelompok'] ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-calendar-check-fill text-success fs-5 me-2"></i>
                                            <div>
                                                <span class="text-muted d-block" style="font-size: 0.8rem;">Pelaksanaan KKN</span>
                                                <strong><?= date('d M Y', strtotime($dk['kknmulai'])) ?> - <?= date('d M Y', strtotime($dk['kknselesai'])) ?></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <div class="d-grid gap-2">
                                    <a href="<?= base_url('pembimbing/kelompok') ?>" class="btn btn-primary btn-sm"><i class="bi bi-people me-1"></i> Kelompok Bimbingan</a>
                                    <a href="<?= base_url('pembimbing/aktifitas') ?>" class="btn btn-success btn-sm"><i class="bi bi-journal-text me-1"></i> Logbook Mahasiswa</a>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4">
                    <?php } ?>
                <?php } else { ?>
                    <div class="text-center py-4">
                        <i class="bi bi-exclamation-triangle text-warning fs-1"></i>
                        <h5 class="mt-3">Status: Tidak ada penugasan</h5>
                        <p class="text-muted mb-0">Anda saat ini belum ditugaskan sebagai Dosen Pembimbing Lapangan (DPL) pada periode KKN aktif.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>

<?php if (!empty($jadwal_aktif)) { ?>
    <section class="section mb-4">
        <div class="row">
            <?php foreach ($jadwal_aktif as $jadwal) { ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card shadow-sm border mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-<?= $jadwal['badge_color'] ?>"><?= $jadwal['status_label'] ?></span>
                                <small class="text-muted"><?= $jadwal['tahun'] ?> / <?= $jadwal['semester'] == '1' ? 'Ganjil' : 'Genap' ?></small>
                            </div>
                            <h5 class="card-title text-primary" style="font-size: 1.15rem; min-height: 2.8rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;"><?= $jadwal['tema'] ?></h5>
                            <p class="card-text text-muted mb-4" style="font-size: 0.9rem;">
                                <i class="bi bi-geo-alt-fill me-1 text-danger"></i> <?= $jadwal['tempat'] ?><br>
                                <i class="bi bi-calendar-range-fill me-1 text-success"></i> <?= date('d M Y', strtotime($jadwal['kknmulai'])) ?> - <?= date('d M Y', strtotime($jadwal['kknselesai'])) ?>
                            </p>
                            <div class="row text-center border-top pt-3">
                                <div class="col-4 border-end">
                                    <h4 class="mb-0 font-extrabold" style="color: #4f46e5 !important; font-size: 1.4rem;"><?= $jadwal['pendaftar_count'] ?></h4>
                                    <span class="text-muted" style="font-size: 0.72rem; font-weight: 600; display: block; white-space: nowrap;">Pendaftar</span>
                                </div>
                                <div class="col-4 border-end">
                                    <h4 class="mb-0 font-extrabold" style="color: #16a34a !important; font-size: 1.4rem;"><?= $jadwal['lokasi_count'] ?></h4>
                                    <span class="text-muted" style="font-size: 0.72rem; font-weight: 600; display: block; white-space: nowrap;">Lokasi</span>
                                </div>
                                <div class="col-4">
                                    <h4 class="mb-0 font-extrabold" style="color: #d97706 !important; font-size: 1.4rem;"><?= $jadwal['aktifitas_count'] ?></h4>
                                    <span class="text-muted" style="font-size: 0.72rem; font-weight: 600; display: block; white-space: nowrap;">Logbook (Kegiatan)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
<?php } ?>

<section class="section">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Tabel Jadwal KKN</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-sm table-jadwal">
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;" scope="col">No</th>
                            <th style="vertical-align: middle;" scope="col">Tahun</th>
                            <th style="vertical-align: middle;" scope="col">Tema</th>
                            <th style="vertical-align: middle;" scope="col">Jenis</th>
                            <th style="vertical-align: middle;" scope="col">Tempat</th>
                            <th style="vertical-align: middle;" scope="col">Pendaftaran</th>
                            <th style="vertical-align: middle;" scope="col">Pelaksanaan</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.table-jadwal').DataTable({
            "autoWidth": false,
            "processing": false,
            "serverSide": true,
            "ajax": {
                "url": "<?= base_url('app/jadwal/read') ?>",
                "dataType": "json",
                "type": "POST",
                "dataSrc": function(json) {
                    return json.data;
                }
            },
            "columns": [
                { "data": "no", "width": "5%" },
                { "data": "tahun", "width": "10%" },
                { "data": "tema", "width": "30%" },
                { "data": "jenis", "width": "10%" },
                { "data": "tempat", "width": "15%" },
                { "data": "pendaftaran", "orderable": false, "searchable": false, "width": "15%" },
                { "data": "pelaksanaan", "orderable": false, "searchable": false, "width": "15%" }
            ]
        });
    });
</script>