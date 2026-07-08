<div class="page-heading">
    <div class="row">
        <div class="col-3 col-lg-1 col-md-2">
            <img src="<?= base_url('assets/img/logoapp.png') ?>" width="100%">
        </div>
        <div class="col-9 col-lg-11 col-md-10">
            <h3>Daftar <?= $this->config->item('app_singkatan') ?> Tahun <?= date("Y") ?></h3>
            <div class="buttons mt-3">
                <a href="#" class="btn btn-success rounded-pill act-kelompok" data-kategori="teraktif"><i class="bi bi-arrow-up-right"></i> Kelompok Teraktif</a>
                <!--
                <a href="#" class="btn btn-primary rounded-pill act-aktifitas" data-kategori="trending"><i class="bi bi-star"></i> Aktifitas Trending</a>
                -->
                <a href="#" class="btn btn-info rounded-pill act-aktifitas" data-kategori="best"><i class="bi bi-graph-up-arrow"></i> Aktifitas Terbaik</a>
            </div>
        </div>
    </div>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-9">
            <!-- start daftar kkn -->
            <?php
            $color = array("purple", "blue", "green", "red", "yellow");
            
            $kkn_aktif = [];
            $kkn_riwayat = [];
            $today = date('Y-m-d');
            
            foreach ($kkn['db'] as $dp) {
                if ($today <= $dp['kknselesai'] || $today <= $dp['daftarselesai']) {
                    $kkn_aktif[] = $dp;
                } else {
                    $kkn_riwayat[] = $dp;
                }
            }
            ?>
            
            <?php if (count($kkn_aktif) > 0) { ?>
                <h5 class="mb-3"><i class="bi bi-bookmark-star-fill text-primary"></i> Kegiatan Aktif</h5>
                <div class="row mb-4">
                    <?php foreach ($kkn_aktif as $i => $dp) {
                        $url = base_url('dashboard/kkn/' . $dp['idkkn']);
                        $randcolor = rand(0, 4);
                    ?>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card shadow-sm border-0 mb-3" style="border-radius: 8px;">
                                <a href="<?= $url ?>">
                                    <div class="card-body px-3 py-4-5" title="<?= $dp['keterangan'] ?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon <?= $color[$randcolor] ?>">
                                                    <i class="iconly-boldBookmark"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold mb-0" style="font-size: 0.9rem;"><?= $dp['tema'] ?></h6>
                                                <div class="mb-0 text-muted" style="font-size:11px">Tahun <?= $dp['tahun'] ?></div>
                                                <h6 class="font-extrabold mb-0" style="font-size: 0.85rem;"><i class="bi bi-person-check"></i> <?= $dp['jumlahvalidasi'] ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            
            <?php if (count($kkn_riwayat) > 0) { ?>
                <h5 class="mb-3 text-muted"><i class="bi bi-clock-history"></i> Riwayat Kegiatan</h5>
                <div class="row mb-4">
                    <?php foreach ($kkn_riwayat as $i => $dp) {
                        $url = base_url('dashboard/kkn/' . $dp['idkkn']);
                        $randcolor = rand(0, 4);
                    ?>
                        <div class="col-6 col-lg-3 col-md-6">
                            <div class="card shadow-sm border-0 mb-3" style="border-radius: 8px; opacity: 0.8;">
                                <a href="<?= $url ?>">
                                    <div class="card-body px-3 py-4-5" title="<?= $dp['keterangan'] ?>">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="stats-icon grey">
                                                    <i class="iconly-boldBookmark"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted font-semibold mb-0" style="font-size: 0.9rem;"><?= $dp['tema'] ?></h6>
                                                <div class="mb-0 text-muted" style="font-size:11px">Tahun <?= $dp['tahun'] ?></div>
                                                <h6 class="font-extrabold mb-0" style="font-size: 0.85rem;"><i class="bi bi-person-check"></i> <?= $dp['jumlahvalidasi'] ?></h6>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
            <!-- end daftar kkn -->


            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                        <div class="card-header bg-transparent border-0 pb-0">
                            <h4>Berita Terbaru</h4>
                        </div>
                        <div class="card-body px-4 px-md-5 position-relative">

                            <div id="beritaCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                                <div class="carousel-inner">
                                    <?php foreach ($daftarberita as $i => $dp) {
                                        $detail = strip_tags($dp['detail']);
                                        $berita = explode(" ", $detail);
                                        $urlberita = base_url("web/detailberita/" . $dp['slug']);
                                        $activeClass = ($i === 0) ? 'active' : '';
                                    ?>
                                        <div class="carousel-item <?= $activeClass ?>">
                                            <div class="card mb-0 overflow-hidden border-0" style="background: #fff;">
                                                <div class="row g-0 align-items-center">
                                                    <div class="col-md-5 position-relative" style="min-height: 250px; background: #f8f9fa; border-radius: 8px; overflow: hidden;">
                                                        <?php if ($dp['thumbnail']) { ?>
                                                            <img src="<?= base_url($dp['thumbnail']) ?>" class="w-100 h-100" style="object-fit: cover; position: absolute; top:0; left:0;" alt="Thumbnail">
                                                        <?php } else { ?>
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center text-muted" style="position: absolute; top:0; left:0;">
                                                                <i class="bi bi-image" style="font-size: 3rem;"></i>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="col-md-7 d-flex flex-column justify-content-between p-3 p-md-4">
                                                        <div>
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <span class="text-muted" style="font-size: 11px;"><i class="bi bi-person-circle"></i> <?= $dp['nama'] ?></span>
                                                                <span class="badge bg-secondary" style="font-size: 11px;"><i class="bi bi-clock"></i> <?= waktu_lalu($dp['waktu']) ?></span>
                                                            </div>
                                                            <h4 class="card-title mb-2"><a href="<?= $urlberita ?>" style="color: #03a49b; text-decoration: none; font-weight: 700;"><?= $dp['judul'] ?></a></h4>
                                                            <p class="card-text text-muted" style="font-size: 13px; line-height: 1.6;">
                                                                <?php
                                                                if (count($berita) > 30) {
                                                                    for ($k = 0; $k <= 30; $k++) {
                                                                        echo $berita[$k] . " ";
                                                                    }
                                                                    echo "...";
                                                                } else {
                                                                    echo $detail;
                                                                }
                                                                ?>
                                                            </p>
                                                        </div>
                                                        <div class="mt-3">
                                                            <a href="<?= $urlberita ?>" class="btn btn-sm btn-outline-primary" style="border-radius: 20px; font-weight: 500; padding: 4px 16px;">Baca Selengkapnya <i class="bi bi-arrow-right"></i></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#beritaCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#beritaCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-xl-4">
                    <div class="card">
                        <div class="card-header">
                            <h4>Pengunjung</h4>
                        </div>
                        <div class="card-body">
                            <div class="row" style="text-align:center">
                                <a href="https://info.flagcounter.com/AYeJ"><img src="https://s01.flagcounter.com/count2/AYeJ/bg_FFFFFF/txt_000000/border_CCCCCC/columns_2/maxflags_20/viewers_0/labels_1/pageviews_1/flags_0/percent_0/" alt="Flag Counter" border="0"></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl-8">
                    <h4>Update Kegiatan Terakhir</h4>
                    <div id="daftarlkh"></div>


                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <?php if ($this->session->userdata('iduser')) { ?>
                <div class="card">
                    <div class="card-body py-4 px-5">
                        <div class="d-flex align-items-center">
                            <div class="avatar avatar-xl">
                                <img src="<?= $this->session->userdata('profilpic') ?>" alt="Face 1">
                            </div>
                            <div class="ms-3 name">
                                <h5 class="font-bold"><?= $this->session->userdata('nama') ?></h5>
                                <!--
                            <h6 class="text-muted mb-0"><?= $this->session->userdata('email') ?></h6>
                            -->
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ($lastlogin['status']) { ?>
                <div class="card">
                    <div class="card-header">
                        <h4>Masuk Terakhir</h4>
                    </div>
                    <div class="card-content pb-4">
                        <?php foreach ($lastlogin['db'] as $i => $dp) { ?>
                            <div class="recent-message d-flex px-4 py-3">
                                <div class="avatar avatar-lg">
                                    <img src="<?= base_url($dp['foto']) ?>">
                                </div>
                                <div class="name ms-4">
                                    <h5 class="mb-1"><?= $dp['nama'] ?></h5>
                                    <div class="text-muted mb-0" style="font-size:12px"><span class="badge bg-success"><i class="bi bi-clock"></i> <?= waktu_lalu($dp['lastlogin']) ?></span></div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <div class="card">
                <div class="card-header">
                    <h4>Komentar Terbaru</h4>
                </div>
                <div class="card-body">
                    <?php
                    if (count($daftarkomentar) > 0) {
                        echo "<ul>";
                        foreach ($daftarkomentar as $i => $dp) {
                            echo "  <li>
                                        <div><b>" . $dp['nama'] . "</b></div>
                                        <div style='font-size:12px;'><i class='bi bi-clock'></i> " . waktu_lalu($dp['waktu']) . "</div>
                                        <div class='comment-excerpt'><a href='" . base_url('dashboard/detail_aktifitas/' . $dp['idaktifitas']) . "'>&ldquo;" . $dp['komentar'] . "&rdquo;</a></div>
                                    </li>";
                        }
                        echo "</ul>";
                    } else {
                        echo "Tidak ditemukan";
                    }
                    ?>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h4>User Aktif</h4>
                </div>
                <div class="card-body">
                    <h3 id="jum-user-aktif"></h3>
                </div>
            </div>

        </div>
    </section>
</div>

<!-- Modal web -->
<div class="modal fade text-left w-100" id="modal-aktifitas" tabindex="-1" role="dialog" aria-labelledby="myModalForm" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Aktifitas</h4>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i> x
                </button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <i style="font-size:13px;">Tingkatkan Aktifitas dan Like pada kegiatan anda </i>
                    <div class="detail-aktifitas-populer"></div>
                </div>
            </div>

            <div class=" modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
<!-- full size modal-->