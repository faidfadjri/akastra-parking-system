<?= $this->extend('app'); ?>

<?= $this->section('content'); ?>
<section class="main-section">
    <?= $this->include('items/zoom-in-out.php'); ?>

    <div class="headline-wrapper">
        <a href="/" class="back-button">
            <span class="material-symbols-outlined mb-2">
                arrow_back
            </span>
        </a>
        <h1 class="headline">Area Stall GR</h1>
    </div>

    <div class="main-area" id="main-area">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex w-100 parkir-wrap">
                        <div class="d-flex">
                            <div class="office-wrap">
                                <div class="ruang-mesin">
                                    Area Kantin & Loker
                                </div>
                            </div>

                            <div class="d-flex flex-column">
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex flex-column gap-0 me-5">
                                        <!-- Labels -->
                                        <div class="d-flex" style="gap: 31px; margin-inline-start: 12px;">
                                            <?php for ($index = 0; $index < sizeof($labels); $index++) : ?>
                                                <p class="label-seat-vertical text-center"><?= $labels[$index]; ?></p>
                                            <?php endfor; ?>
                                        </div>
                                        <!-- Stall GR -->
                                        <div class="d-flex gap-2">
                                            <?php $key = $controller->cari_parkir($grupG, 1); ?>
                                            <a class="seat-blue seat-vertical" position="1" parking-name="Stall GR" grup="G" id="<?= rand(1 * time(), 100 * time()); ?>">
                                                <?= (!empty($key) || $key === 0) ? $grupG[$key]['model_code'] . " | " . $grupG[$key]['license_plate'] . "<br>" . $grupG[$key]['category'] : "" ?>
                                            </a>
                                            <?php for ($position = 2; $position <= 16; $position++) : ?>
                                                <?php $key = $controller->cari_parkir($grupG, $position); ?>
                                                <a class=" seat-green seat-vertical" position="<?= $position; ?>" parking-name="Stall GR" grup="G" id="<?= rand($position * time(), 100 * time()); ?>">
                                                    <?= (!empty($key) || $key === 0) ? $grupG[$key]['model_code'] . " | " . $grupG[$key]['license_plate'] . "<br>" . $grupG[$key]['category'] : "" ?>
                                                </a>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <!-- Labels -->
                                        <div class="d-flex gap-1 text-center align-items-center justify-content-center">
                                            <p class="label-seat-vertical w-100">STALL PENERIMAAN</p>
                                        </div>
                                        <div class="d-flex gap-1 justify-content-end me-2">
                                            <?php for ($position = 17; $position <= 21; $position++) : ?>
                                                <?php $key = $controller->cari_parkir($grupG, $position); ?>
                                                <a class="seat-green seat-vertical-short" position="<?= $position; ?>" parking-name="Stall GR" grup="G" id="<?= rand($position * time(), 100 * time()); ?>">
                                                    <?= (!empty($key) || $key === 0) ? $grupG[$key]['model_code'] . " | " . $grupG[$key]['license_plate'] . "<br>" . $grupG[$key]['category'] : "" ?>
                                                </a>
                                            <?php endfor; ?>
                                            <div class="garis-vertical"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Area Jalan Stall GR -->

                                <div class="d-flex">
                                    <div class="d-flex-column justify-content-end w-100">

                                        <?php for ($group = 1; $group <= 2; $group++) : ?>
                                            <div class="d-flex mt-2 me-2 gap-1 justify-content-end">
                                                <?php
                                                $posStart = 1;
                                                $posEnd = 12;
                                                ?>

                                                <?php if ($group == 2) {
                                                    $posStart = 13;
                                                    $posEnd   = 24;
                                                } ?>

                                                <?php for ($position = $posStart; $position <= $posEnd; $position++) : ?>
                                                    <?php $key = $controller->cari_parkir($grupH, $position); ?>
                                                    <a class="seat seat-horizontal" position="<?= $position; ?>" parking-name="Parkiran Bayangan GR" grup="H" id="<?= rand($position * time(), 100 * time()); ?>">
                                                        <?= (!empty($key) || $key === 0) ? $grupH[$key]['model_code'] . " | " . $grupH[$key]['license_plate'] . "<br>" . $grupH[$key]['category'] : "" ?>
                                                    </a>
                                                <?php endfor; ?>
                                                <div class="garis-vertical"></div>
                                            </div>
                                        <?php endfor; ?>
                                        <div class="d-flex mt-2 gap-1 justify-content-between">
                                            <div class="office d-flex gap-1">
                                                <div class="ruang-foreman">
                                                    <span class="material-symbols-outlined">
                                                        engineering
                                                    </span>
                                                    R. Kompresor
                                                </div>
                                                <div class="ruang-foreman">
                                                    <span class="material-symbols-outlined">
                                                        engineering
                                                    </span>
                                                    R. TWC
                                                </div>
                                                <div class="ruang-foreman">
                                                    <span class="material-symbols-outlined">
                                                        engineering
                                                    </span>
                                                    R. Foreman
                                                </div>
                                                <div class="ruang-sparepart">
                                                    <span class="material-symbols-outlined">
                                                        engineering
                                                    </span>
                                                    R. Sparepart GR
                                                </div>
                                            </div>
                                            <div class="office d-flex gap-1" style="margin-right: 345px; z-index: 99;">
                                                <div class="seat-office text-white border-0 seat-horizontal">
                                                    Loading / Unloading
                                                </div>
                                                <?php for ($position = 25; $position <= 27; $position++) : ?>
                                                    <?php $key = $controller->cari_parkir($grupH, $position); ?>
                                                    <a class="seat seat-horizontal" position="<?= $position; ?>" parking-name="Parkiran Bayangan GR" grup="H" id="<?= rand($position * time(), 100 * time()); ?>">
                                                        <?= (!empty($key) || $key === 0) ? $grupH[$key]['model_code'] . " | " . $grupH[$key]['license_plate'] . "<br>" . $grupH[$key]['category'] : "" ?>
                                                    </a>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="gedung-wrap">
                                    <img src="/assets/gedung-akastra.png" class="gedung">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <nav class="bottom-nav">
        <?php if ($date != date('Y-m-d')) : ?>
            <a class="cancel-button d-flex align-items-center" href="/parkir/depan/<?= $date; ?>">
                <span class="material-icons">
                    navigate_before
                </span>
                Area Parkiran
            </a>
            <a class="next-button d-flex" href="/parkir/stall_bp/<?= $date; ?>">
                Stall BP
                <span class="material-icons">
                    navigate_next
                </span>
            </a>
        <?php else : ?>
            <a class="cancel-button d-flex align-items-center" href="/parkir/depan">
                <span class="material-icons">
                    navigate_before
                </span>
                Area Parkiran
            </a>
            <a class="next-button d-flex" href="/parkir/stall_bp">
                Stall BP
                <span class="material-icons">
                    navigate_next
                </span>
            </a>
        <?php endif; ?>
    </nav>
</section>
<?= $this->endSection(); ?>