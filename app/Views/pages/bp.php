<?= $this->extend('app'); ?>

<?= $this->section('content'); ?>
<section class="main-section">
    <?= $this->include('items/zoom-in-out.php'); ?>
    <h1 class="headline">Area Stall BP</h1>
    <div class="main-area" id="main-area">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex parkir-wrap">
                        <div class="d-flex-column justify-content-end z-index-99" style="width: 777px">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex mt-2 gap-1" style="margin-right: 420px;">
                                    <div class="ruang-foreman" style="margin-right: -20px;">
                                        <span class="material-symbols-outlined">
                                            engineering
                                        </span>
                                        Area Gudang & Foreman
                                    </div>
                                    <div class="d-flex flex-column gap-1 justify-content-start" style="margin-top: 70px;">
                                        <div class="seat-vertical-oven-label">
                                            QC
                                        </div>
                                        <div class="seat-vertical-oven-label">
                                            Pemasangan
                                        </div>
                                        <div class="seat-vertical-oven-label">
                                            Poles
                                        </div>
                                        <div class="seat-vertical-oven-label">
                                            Poles
                                        </div>
                                        <div class="mt-5"></div>
                                        <div class="seat-vertical-wide-label">
                                            Pengecatan
                                        </div>
                                        <div class="mt-5"></div>
                                        <div class="seat-vertical-wide-label mt-4">
                                            Pengecatan
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column gap-1 justify-content-start">
                                        <div class="ruang-sparepart">
                                            <span class="material-symbols-outlined">
                                                stream
                                            </span>
                                            Sparepart GR
                                        </div>
                                        <!-- vertical sparepart GR -->
                                        <div class="d-flex gap-1 flex-column align-items-end">
                                            <?php for ($position = 1; $position <= 10; $position++) : ?>

                                                <?php $seatOrientation = 'seat-horizontal-oven' ?>

                                                <?php if ($position == 7 || $position == 9) {
                                                    $seatOrientation = 'seat-horizontal-wide';
                                                } ?>
                                                <?php if ($position == 6 || $position == 8) : ?>
                                                    <div class="seat seat-horizontal-wide">
                                                        x
                                                    </div>
                                                <?php else : ?>
                                                    <?php $key = $controller->cari_parkir($grupI, $position); ?>
                                                    <a class="seat-yellow <?= $seatOrientation; ?>" grup="I" position="<?= $position; ?>" parking-name="Stall BP" id="<?= rand(time() * $position, time() * 2000); ?>">
                                                        <?= (!empty($key) || $key === 0) ? $grupI[$key]['model_code'] . " | " . $grupI[$key]['license_plate'] . "<br>" . $grupI[$key]['category'] : "" ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <div class="seat-office seat-horizontal-oven text-white">
                                                Area Mixing
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Area Jalan Sebelah Sparepart GR -->
                                    <div class=" d-flex gap-1 ms-3">
                                        <?php for ($grup = 1; $grup <= 3; $grup++) : ?>
                                            <?php
                                            $posStart = 1;
                                            $posEnd   = 7;

                                            if ($grup == 2) {
                                                $posStart = 8;
                                                $posEnd   = 16;
                                            } else if ($grup == 3) {
                                                $posStart = 17;
                                                $posEnd = 23;
                                            }
                                            ?>
                                            <div class="d-flex gap-1 flex-column">
                                                <?php for ($position = $posStart; $position <= $posEnd; $position++) : ?>
                                                    <?php $key = $controller->cari_parkir($grupJ, $position); ?>
                                                    <a class="seat seat-vertical-short" grup="J" position="<?= $position; ?>" parking-name="Parkiran Bayangan BP" id="<?= rand(time() * $position, time() * 2000); ?>">
                                                        <?= (!empty($key) || $key === 0) ? $grupJ[$key]['model_code'] . " | " . $grupJ[$key]['license_plate'] . "<br>" . $grupJ[$key]['category'] : "" ?>
                                                    </a>
                                                <?php endfor; ?>
                                            </div>
                                        <?php endfor; ?>
                                    </div>

                                    <!-- Area Loading/Unloading -->
                                    <div class="d-flex gap-1 ms-3">
                                        <!-- Labels -->
                                        <div class="d-flex gap-1 flex-column">
                                            <div class="mt-5"></div>
                                            <div class="seat-vertical-oven-label">
                                                Panel
                                            </div>
                                            <div class="seat-vertical-oven-label">
                                                Dempul
                                            </div>
                                            <div class="seat-vertical-oven-label">
                                                Dempul
                                            </div>
                                            <div class="seat-vertical-oven-label">
                                                Backup
                                            </div>
                                            <div class="mt-5"></div>
                                            <div class="mt-5"></div>
                                            <div class="mt-1"></div>
                                            <div class="seat-vertical-oven-label">
                                                Rangka
                                            </div>
                                            <div class="seat-vertical-wide-label">
                                                Masking
                                            </div>
                                            <div class="seat-vertical-wide-label">
                                                Cat
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1 flex-column">
                                            <div class="seat-office seat-horizontal-wide text-center font-weight-normal"> Loading / unloading</div>
                                            <?php
                                            $seatOrientation = "seat-horizontal-oven";
                                            $seatColor       = "seat-yellow";
                                            ?>
                                            <?php for ($position = 1; $position <= 9; $position++) : ?>
                                                <?php if ($position == 5 || $position == 6) {
                                                    $seatOrientation = 'seat-horizontal align-self-center';
                                                    $seatColor       = 'seat';
                                                } ?>

                                                <?php if ($position == 7) {
                                                    $seatOrientation = 'seat-horizontal-oven';
                                                    $seatColor       = 'seat-yellow';
                                                } ?>

                                                <?php if ($position == 8 || $position == 9) {
                                                    $seatOrientation = 'seat-horizontal-wide';
                                                    $seatColor       = 'seat-yellow';
                                                } ?>
                                                <?php $key = $controller->cari_parkir($grupK, $position); ?>
                                                <a class="<?= $seatColor . " " . $seatOrientation; ?>" grup="K" position="<?= $position; ?>" parking-name="Stall BP" id="<?= rand(time() * $position, time() * 2000); ?>">
                                                    <?= (!empty($key) || $key === 0) ? $grupK[$key]['model_code'] . " | " . $grupK[$key]['license_plate'] . "<br>" . $grupK[$key]['category'] : "" ?>
                                                </a>
                                            <?php endfor; ?>
                                            <div class="seat-office seat-horizontal-wide text-white">
                                                Ruang Genset
                                            </div>
                                        </div>
                                    </div>


                                    <div class="ms-3">
                                        <div class="d-flex gap-2 align-items-end mb-3">
                                            <div>
                                                <div class="d-flex gap-2">
                                                    <p class="seat-horizontal-label">Perbaikan</p>
                                                    <p class="seat-horizontal-label ms-4">Pelepasan</p>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <?php for ($position = 1; $position <= 2; $position++) : ?>
                                                        <?php $key = $controller->cari_parkir($grupL, $position); ?>
                                                        <a class="seat-yellow seat-vertical-wide" style="height: 180px" grup="L" position="<?= $position; ?>" parking-name="Stall BP" id="<?= rand(time() * $position, time() * 2000); ?>">
                                                            <?= (!empty($key) || $key === 0) ? $grupL[$key]['model_code'] . " | " . $grupL[$key]['license_plate'] . "<br>" . $grupL[$key]['category'] : "" ?>
                                                        </a>
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <div class="gedung-wrap">
                                                <img src="/assets/gedung-akastra.png" class="gedung">
                                            </div>
                                        </div>

                                        <div class="d-flex flex-column ms-2 gap-1">
                                            <div class="d-flex gap-4">
                                                <p class="seat-horizontal-label ms-4">Stall Pendempulan</p>
                                                <div class="ms-5"></div>
                                                <p class="seat-horizontal-label ms-4 ps-2">Stall Cuci</p>
                                            </div>
                                            <div class="d-flex gap-1">
                                                <?php for ($position = 1; $position <= 9; $position++) : ?>
                                                    <?php $class = 'seat-yellow seat-vertical-wide' ?>
                                                    <?php if ($position == 5) : ?>
                                                        <div class="sparepart-bp">
                                                            <span class="material-symbols-outlined">
                                                                stream
                                                            </span>
                                                            Sparepart BP
                                                        </div>
                                                    <?php elseif ($position == 6) : ?>
                                                        <div class="loading-area">
                                                            Sparepart
                                                        </div>
                                                    <?php elseif ($position == 4) : ?>
                                                        <a class="seat-teal seat-vertical" grup="M" position="<?= $position; ?>" parking-name="Stall BP" id="<?= rand(time() * $position, time() * 2000); ?>">
                                                            <?= (!empty($key) || $key === 0) ? $grupM[$key]['model_code'] . " | " . $grupM[$key]['license_plate'] . "<br>" . $grupM[$key]['category'] : "" ?>
                                                        </a>
                                                    <?php else : ?>
                                                        <?php if ($position >= 7) $class = 'seat-yellow seat-vertical'; ?>
                                                        <?php $key = $controller->cari_parkir($grupM, $position); ?>
                                                        <a class="<?= $class; ?>" grup="M" position="<?= $position; ?>" parking-name="Stall BP" id="<?= rand(time() * $position, time() * 2000); ?>">
                                                            <?= (!empty($key) || $key === 0) ? $grupM[$key]['model_code'] . " | " . $grupM[$key]['license_plate'] . "<br>" . $grupM[$key]['category'] : "" ?>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <p class="text-label-vertical">-- Area Finishing --</p>
                                            </div>
                                            <!-- Area Jalan Dekat Gudang Bahan -->
                                            <?php for ($grup = 1; $grup <= 2; $grup++) : ?>
                                                <?php
                                                $posStart = 1;
                                                $posEnd = 8;

                                                if ($grup == 2) {
                                                    $posStart = 9;
                                                    $posEnd   = 11;
                                                }

                                                ?>
                                                <div class="d-flex gap-1 z-index-99">
                                                    <?php for ($position = $posStart; $position <= $posEnd; $position++) : ?>
                                                        <?php if ($position == 1) : ?>
                                                            <div class="seat-horizontal"></div>
                                                        <?php else : ?>
                                                            <?php $key = $controller->cari_parkir($grupN, $position); ?>
                                                            <a class="seat seat-horizontal" grup="N" position="<?= $position; ?>" parking-name="Parkiran Bayangan BP" id="<?= rand(time() * $position, time() * 2000); ?>">
                                                                <?= (!empty($key) || $key === 0) ? $grupN[$key]['model_code'] . " | " . $grupN[$key]['license_plate'] . "<br>" . $grupN[$key]['category'] : "" ?>
                                                            </a>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php endfor; ?>
                                            <div class="d-flex gap-1 justify-content-between">
                                                <div class="office d-flex gap-1">
                                                    <div class="seat-purple seat-horizontal">
                                                        Alat BP
                                                    </div>
                                                    <div class="seat-purple seat-horizontal">
                                                        R. Foreman
                                                    </div>
                                                    <div class="seat-purple seat-horizontal">
                                                        Gudang Bahan
                                                    </div>
                                                    <div class="seat-purple seat-horizontal">
                                                        Part Removal
                                                    </div>
                                                </div>
                                                <div class="d-flex gap-1">
                                                    <?php for ($position = 1; $position <= 7; $position++) : ?>
                                                        <?php $key = $controller->cari_parkir($grupO, $position); ?>
                                                        <a class="seat-yellow seat-vertical" grup="O" position="<?= $position; ?>" parking-name="Stall BP" id="<?= rand(time() * $position, time() * 2000); ?>">
                                                            <?= (!empty($key) || $key === 0) ? $grupO[$key]['model_code'] . " | " . $grupO[$key]['license_plate'] . "<br>" . $grupO[$key]['category'] : "" ?></a>
                                                    <?php endfor; ?>
                                                    <p class="text-label-vertical">-- Stall Delivery --</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<nav class="bottom-nav justify-content-between">
    <?php if ($date != date('Y-m-d')) : ?>
        <a class="cancel-button d-flex align-items-center justify-content-center" href="/parkir/stall_gr/<?= $date; ?>">
            <span class="material-icons">
                navigate_before
            </span>
            Stall GR
        </a>
        <a class="next-button d-flex align-items-center justify-content-center" href="/parkir/akm/<?= $date; ?>">
            AKM
            <span class="material-icons">
                navigate_next
            </span>
        </a>
    <?php else : ?>
        <a class="cancel-button d-flex align-items-center justify-content-center" href="/parkir/stall_gr">
            <span class="material-icons">
                navigate_before
            </span>
            Stall GR
        </a>
        <a class="next-button d-flex align-items-center justify-content-center" href="/parkir/akm">
            AKM
            <span class="material-icons">
                navigate_next
            </span>
        </a>
    <?php endif; ?>
</nav>

<?= $this->endSection(); ?>