<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E Parking | Main</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/css/main/home.css">
    <link rel="icon" type="image/webp" href="/assets/logo.webp">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section class="main-section">

        <!-- Header / Topbar -->
        <div class="topbar mb-4">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center topbar-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="logo-box">P</div>
                    <div>
                        <h1 class="title">Parking Mobile Akastra</h1>
                        <p class="subtitle">Sistem Manajemen Parkir Terpadu</p>
                    </div>
                </div>
                <div class="d-flex gap-5 mt-3 mt-md-0">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn-icon btn-history shadow-sm" title="History" data-bs-toggle="modal" data-bs-target="#historyModal">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </button>
                        <a class="btn-icon btn-exit shadow-sm" href="/logout" title="Logout">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="btn-icon btn-profile shadow-sm">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <span class="fw-semibold"><?= ucfirst($user) ?></span>
                    </div>
                </div>
            </div>

            <!-- Footer Section -->
            <div class="d-flex align-items-md-center justify-content-between topbar-footer">
                <div class="d-flex align-items-center gap-2 mb-3 mb-md-0">
                    <div class="status-dot"></div>
                    <?php if (isset($user)) : ?>
                        <p class="last-updated-text">
                            TERAKHIR DIPERBARUI: OLEH <?= strtoupper($user); ?> PADA <?= $lastDate; ?>
                        </p>
                    <?php else: ?>
                        <p class="last-updated-text">
                            TERAKHIR DIPERBARUI: SAAT INI
                        </p>
                    <?php endif; ?>
                </div>

                <form action="/parkir/search_car"
                    method="POST"
                    id="search-form"
                    class="search-wrapper">
                    <div class="input-group">
                        <span class="input-group-text search-icon-bg border border-end-0">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </span>
                        <input type="text"
                            name="keyword"
                            autocomplete="off"
                            class="form-control search-input border border-start-0 shadow-none py-2"
                            placeholder="Cari Kendaraan (Plat Nomor/Model)"
                            id="search-keyword">
                    </div>
                    <ul class="list-group position-absolute w-100 mt-1 d-none bg-white shadow-sm" id="list-wrap"></ul>
                </form>
            </div>
        </div>

        <div class="position-relative p-0">
            <!-- Hidden Input for JS Logic -->
            <input type="hidden" class="form-control" id="usage" value="<?= $exist['total']; ?>">
            <input type="hidden" class="form-control" id="capacity" value="<?= $capacity['total']; ?>">
            <input type="hidden" class="form-control" id="GRcapacity" value="<?= $capacity['GR']; ?>">
            <input type="hidden" class="form-control" id="BPcapacity" value="<?= $capacity['BP']; ?>">
            <input type="hidden" class="form-control" id="AKMcapacity" value="<?= $capacity['AKM']; ?>">
            <input type="hidden" class="form-control" id="GRvehicle" value="<?= $exist['GR']; ?>">
            <input type="hidden" class="form-control" id="BPvehicle" value="<?= $exist['BP']; ?>">
            <input type="hidden" class="form-control" id="AKMvehicle" value="<?= $exist['AKM']; ?>">

            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="row justify-content-center mb-3">
                    <div class="alert alert-danger alert-dismissible fade show text-center mx-auto mb-0 shadow-sm" style="max-width: 500px;" role="alert">
                        <?= session()->getFlashdata('pesan'); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row mb-3">
                <div class="col-12">
                    <div class="parking-card gap-2">
                        <h5 class="card-label global">STATUS KAPASITAS GLOBAL</h5>
                        <div class="card-value"><?= $exist['total']; ?> <span>/ <?= $capacity['total']; ?> Terisi</span></div>
                        <div class="progress-container mb-0">
                            <div class="progress-bar-custom" id="overall-progress" role="progressbar"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-stretch g-3">
                <!-- GR Vehicle -->
                <div class="col-md-4 col-sm-12">
                    <div class="parking-card">
                        <h5 class="card-label">GR Vehicle</h5>
                        <div class="card-value mb-3"><?= $exist['GR'] . ' / ' . $capacity['GR']; ?></div>

                        <div class="progress-container mb-3">
                            <div class="progress-bar-custom progress-bar" id="gr-progress" role="progressbar"></div>
                        </div>

                        <?php if ($GRSummary) : ?>
                            <div class="accordion mb-3" id="accGR">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingGR">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGR">
                                            Detail Status
                                        </button>
                                    </h2>
                                    <div id="collapseGR" class="accordion-collapse collapse" data-bs-parent="#accGR">
                                        <div class="accordion-body px-0 pb-0 pt-3">
                                            <?php $max = max(array_column($GRSummary, 'result')); ?>
                                            <?php foreach ($GRSummary as $row) : ?>
                                                <div class="progress-wrap-detail">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <p class="text-muted fw-semibold"><?= $row['status']; ?></p>
                                                        <p class="fw-bold text-dark"><?= $row['result']; ?></p>
                                                    </div>
                                                    <?php $width = strval((intval($row['result']) / intval($max) * 100)) . "%"; ?>
                                                    <div class="progress bg-light" style="height: 6px;">
                                                        <div class="progress-bar bg-secondary" style="width: <?= $width; ?>;"></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div id="gr-list"></div>

                        <a class="btn-denah mt-auto" href="<?= !$date ? "/parkir/stall_gr" : "/parkir/stall_gr/" . $date; ?>">
                            Lihat Denah <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- BP Vehicle -->
                <div class="col-md-4 col-sm-12">
                    <div class="parking-card">
                        <h5 class="card-label">BP Vehicle</h5>
                        <div class="card-value mb-3"><?= $exist['BP'] . ' / ' . $capacity['BP']; ?></div>

                        <div class="progress-container mb-3">
                            <div class="progress-bar-custom progress-bar" id="bp-progress" role="progressbar"></div>
                        </div>

                        <?php if ($BPSummary) : ?>
                            <div class="accordion mb-3" id="accBP">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingBP">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBP">
                                            Detail Status
                                        </button>
                                    </h2>
                                    <div id="collapseBP" class="accordion-collapse collapse" data-bs-parent="#accBP">
                                        <div class="accordion-body px-0 pb-0 pt-3">
                                            <?php $max = max(array_column($BPSummary, 'result')); ?>
                                            <?php foreach ($BPSummary as $row) : ?>
                                                <div class="progress-wrap-detail">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <p class="text-muted fw-semibold"><?= $row['status']; ?></p>
                                                        <p class="fw-bold text-dark"><?= $row['result']; ?></p>
                                                    </div>
                                                    <?php $width = strval((intval($row['result']) / intval($max) * 100)) . "%"; ?>
                                                    <div class="progress bg-light" style="height: 6px;">
                                                        <div class="progress-bar bg-secondary" style="width: <?= $width; ?>;"></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div id="bp-list"></div>

                        <a class="btn-denah mt-auto" href="<?= !$date ? "/parkir/stall_bp" : "/parkir/stall_bp/" . $date; ?>">
                            Lihat Denah <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <!-- AKM Vehicle -->
                <div class="col-md-4 col-sm-12">
                    <div class="parking-card">
                        <h5 class="card-label">AKM Vehicle</h5>
                        <div class="card-value mb-3"><?= $exist['AKM'] . ' / ' . $capacity['AKM']; ?></div>

                        <div class="progress-container mb-3">
                            <div class="progress-bar-custom progress-bar" id="akm-progress" role="progressbar"></div>
                        </div>

                        <?php if ($AKMSummary) : ?>
                            <div class="accordion mb-3" id="accAKM">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingAKM">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAKM">
                                            Detail Status
                                        </button>
                                    </h2>
                                    <div id="collapseAKM" class="accordion-collapse collapse" data-bs-parent="#accAKM">
                                        <div class="accordion-body px-0 pb-0 pt-3">
                                            <?php $max = max(array_column($AKMSummary, 'result')); ?>
                                            <?php foreach ($AKMSummary as $row) : ?>
                                                <div class="progress-wrap-detail">
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <p class="text-muted fw-semibold"><?= $row['status']; ?></p>
                                                        <p class="fw-bold text-dark"><?= $row['result']; ?></p>
                                                    </div>
                                                    <?php $width = strval((intval($row['result']) / intval($max) * 100)) . "%"; ?>
                                                    <div class="progress bg-light" style="height: 6px;">
                                                        <div class="progress-bar bg-secondary" style="width: <?= $width; ?>;"></div>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div id="akm-list"></div>

                        <a class="btn-denah mt-auto" href="<?= !$date ? "/parkir/akm" : "/parkir/akm/" . $date; ?>">
                            Lihat Denah <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bottom-bar">
            <a class="btn-mulai" href="<?= $date == date('Y-m-d') ? "/parkir/depan" : "/parkir/depan/" . $date; ?>">
                Mulai Pengecekan
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
    </section>

    <!-- History Modal -->
    <div class="modal fade" id="historyModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                <div class="modal-header bg-light border-0 pb-3">
                    <h5 class="modal-title fw-bold" id="staticBackdropLabel">Riwayat Parkir</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0 p-4">
                    <form action="/summary" method="GET">
                        <div class="mb-4">
                            <label class="form-label fw-semibold text-muted mb-2">Pilih Tanggal Riwayat</label>
                            <input type="date" class="form-control" name="date" style="border-radius: 8px; padding: 0.8rem;" required>
                        </div>
                        <div class="text-center d-grid">
                            <button type="submit" class="btn btn-primary shadow-sm" style="border-radius: 8px; padding: 0.8rem; font-weight: 600; background-color: var(--primary); border-color: var(--primary);">Cari Riwayat</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-A3rJD856KowSb7dwlZdYEkO39Gagi7vIsF0jrRAoQmDKKtQBHUuLZ9AsSv4jD4Xa" crossorigin="anonymous"></script>
    <script src="/js/home.js"></script>
</body>

</html>