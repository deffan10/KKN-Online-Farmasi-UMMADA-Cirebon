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
                                    <span class="text-muted" style="font-size: 0.72rem; font-weight: 600; display: block; white-space: nowrap;">LKH (Kegiatan)</span>
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

        </div>
    </div>

</section>