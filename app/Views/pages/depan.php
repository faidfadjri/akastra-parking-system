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
        <h1 class="headline">Area Parkir</h1>
    </div>

    <div class="main-area" id="main-area">
        <div class="container mt-5">
            <div class="row">
                <div class="col-12">
                    <div class="d-flex w-100 parkir-wrap align-items-center justify-content-between">

                        <div class="gedung-wrap">
                            <img src="/assets/gedung-akastra.png" class="gedung">
                        </div>

                        <!-- Area Parkir Depan -->
                        <div class="d-flex flex-column justify-content-between align-items-center" style="padding-right: 20px;">

                            <!-- Parkiran GR  AKA Grup A-->
                            <div class="d-flex gap-1 w-100 justify-content-end mb-3">
                                <?php for ($position = 1; $position <= 9; $position++) : ?>
                                    <?php $key = $controller->cari_parkir($grupA, $position); ?>
                                    <a class="seat-blue seat-vertical text-white" grup="A" id="<?= rand($position * time(), 10 * time()); ?>" position="<?= $position; ?>" parking-name="Parkiran GR">
                                        <?= (!empty($key) || $key === 0) ? $grupA[$key]['model_code'] . " | " . $grupA[$key]['license_plate'] . "<br>" . $grupA[$key]['category'] : "" ?>
                                    </a>
                                <?php endfor; ?>
                                <div class="garis-vertical"></div>
                            </div>
                            <!-- Batas Parkiran GR -->

                            <!-- Jalan Parkiran Bayangan GR AKA Grup B -->
                            <div class="d-flex-column w-100 justify-content-center mb-3 me-5">
                                <?php for ($i = 1; $i <= 2; $i++) : ?>
                                    <?php
                                    $positionStart     = 1;
                                    $positionEnd       = 4;

                                    if ($i == 2) {
                                        $positionStart = 5;
                                        $positionEnd   = 8;
                                    }
                                    ?>

                                    <div class="d-flex justify-content-end gap-2 my-2">
                                        <?php for ($position = $positionStart; $position <= $positionEnd; $position++) : ?>
                                            <?php $key = $controller->cari_parkir($grupB, $position); ?>
                                            <a class="seat seat-horizontal text-dark" grup="B" id="<?= rand($i * time(), 10 * time()); ?>" position="<?= $position; ?>" parking-name="Parkiran Bayangan GR">
                                                <?= (!empty($key) || $key === 0) ? $grupB[$key]['model_code'] . " | " . $grupB[$key]['license_plate'] . "<br>" . $grupB[$key]['category'] : "" ?>
                                            </a>
                                        <?php endfor; ?>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <!-- Batas Jalan Parkiran Bayangan GR -->

                            <!-- Parkiran Dekat Satpam AKA Grup C -->
                            <div class="d-flex flex-column gap-1 w-100 mb-3 me-5">
                                <?php for ($i = 1; $i <= 2; $i++) : ?>

                                    <?php
                                    $positionStart     = 1;
                                    $positionEnd       = 5;

                                    if ($i == 2) {
                                        $positionStart = 6;
                                        $positionEnd   = 10;
                                    }
                                    ?>

                                    <div class="d-flex gap-2 justify-content-end">
                                        <?php for ($position = $positionStart; $position <= $positionEnd; $position++) : ?>
                                            <?php $key = $controller->cari_parkir($grupC, $position); ?>
                                            <?php $seat = 'seat-blue text-white';
                                            $name = 'Parkiran GR'; ?>

                                            <?php if ($position == 1) {
                                                $seat = 'seat text-dark';
                                                $name = 'Parkiran Bayangan GR';
                                            } else if ($position == 6) {
                                                $seat = 'seat text-dark';
                                                $name = 'Parkiran Bayangan BP';
                                            } else if ($position >= 7) {
                                                $seat = 'seat-default text-dark';
                                                $name = 'Parkiran Bayangan BP';
                                            } else {
                                                $name = 'Parkiran GR';
                                            }; ?>
                                            <a class="<?= $seat; ?> seat-vertical" id="<?= rand($i * time(), 10 * time()); ?>" grup="C" position="<?= $position; ?>" parking-name="<?= $name; ?>">
                                                <?= (!empty($key) || $key === 0) ? $grupC[$key]['model_code'] . " | " . $grupC[$key]['license_plate'] . "<br>" . $grupC[$key]['category'] : "" ?>
                                            </a>
                                        <?php endfor; ?>

                                        <!-- Area Satpam & Parkiran -->
                                        <div class="security-office">
                                            <?= ($i == 1) ? 'Pos Satpam' : "Parkir Motor"; ?>
                                        </div>
                                    </div>
                                <?php endfor; ?>
                            </div>
                            <!-- Batas Parkiran Dekat Satpam -->


                            <!-- Parkiran Bayangan BP AKA Grup D -->
                            <div class="d-flex w-100 align-items-center justify-content-end gap-1 mb-3 me-5">
                                <?php $key = $controller->cari_parkir($grupD, 1); ?>
                                <a class="seat seat-vertical mt-2 me-2" position="1" grup="D" id="<?= rand(1 * time(), 10 * time()); ?>" parking-name="Parkiran Bayangan BP">
                                    <?= (!empty($key) || $key === 0) ? $grupD[$key]['model_code'] . " | " . $grupD[$key]['license_plate'] . "<br>" . $grupD[$key]['category'] : "" ?>
                                </a>
                                <div class="d-flex flex-column">
                                    <?php for ($i = 1; $i <= 2; $i++) : ?>

                                        <?php
                                        $positionStart     = 2;
                                        $positionEnd       = 4;

                                        if ($i == 2) {
                                            $positionStart = 5;
                                            $positionEnd   = 7;
                                        }
                                        ?>
                                        <div class="d-flex mt-1 gap-1 justify-content-end">
                                            <?php for ($position = $positionStart; $position <= $positionEnd; $position++) : ?>
                                                <?php $key = $controller->cari_parkir($grupD, $position); ?>
                                                <a class="seat seat-horizontal" grup="D" position="<?= $position; ?>" id="<?= rand($i * time(), 10 * time()); ?>" parking-name="Parkiran Bayangan BP">
                                                    <?= (!empty($key) || $key === 0) ? $grupD[$key]['model_code'] . " | " . $grupD[$key]['license_plate'] . "<br>" . $grupD[$key]['category'] : "" ?>
                                                </a>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <!-- Batas Parkiran Bayangan BP -->

                            <!-- Parkiran BP AKA Grup E -->
                            <div class="d-flex mt-2 gap-2 justify-content-end w-100 mb-3 me-5">
                                <p class="text-label-vertical-reverse">- Area Parkir BP -</p>
                                <?php for ($position = 1; $position <= 6; $position++) : ?>
                                    <?php
                                    $seat = 'seat-default text-dark';
                                    $name = 'Parkiran BP';
                                    $marginRight = '0px'; // default harus dalam format CSS

                                    if ($position == 1) {
                                        $seat = 'seat text-dark';
                                        $name = 'Parkiran Bayangan BP';
                                        $marginRight = '65px';
                                    }

                                    $key = $controller->cari_parkir($grupE, $position);
                                    ?>
                                    <a
                                        class="<?= $seat; ?> seat-vertical"
                                        grup="E"
                                        position="<?= $position; ?>"
                                        id="<?= rand($position * time(), 10 * time()); ?>"
                                        parking-name="<?= $name; ?>"
                                        style="margin-right: <?= $marginRight; ?>;">
                                        <?= (!empty($key) || $key === 0) ? $grupE[$key]['model_code'] . " | " . $grupE[$key]['license_plate'] . "<br>" . $grupE[$key]['category'] : "" ?>
                                    </a>
                                <?php endfor; ?>

                            </div>
                            <!-- Batas Parkiran BP -->


                            <!-- Parkiran Bayangan BP AKA Grup F-->
                            <div class="d-flex w-100 justify-content-end me-5 mb-3 gap-1">
                                <?php $key = $controller->cari_parkir($grupF, 1); ?>
                                <a class="seat seat-vertical mt-1 me-2" grup="F" position="1" id="<?= rand($position * time(), 10 * time()); ?>" parking-name="Parkiran Bayangan BP">
                                    <?= (!empty($key) || $key === 0) ? $grupF[$key]['model_code'] . " | " . $grupF[$key]['license_plate'] . "<br>" . $grupF[$key]['category'] : "" ?>
                                </a>
                                <div class="d-flex flex-column">
                                    <?php for ($i = 1; $i <= 2; $i++) : ?>
                                        <?php
                                        $positionStart     = 2;
                                        $positionEnd       = 4;

                                        if ($i == 2) {
                                            $positionStart = 5;
                                            $positionEnd   = 7;
                                        }
                                        ?>
                                        <div class="d-flex mt-1 gap-1">
                                            <?php for ($position = $positionStart; $position <= $positionEnd; $position++) : ?>
                                                <?php $key = $controller->cari_parkir($grupF, $position); ?>
                                                <a class="seat seat-horizontal text-dark" grup="F" position="<?= $position; ?>" id="<?= rand($position * time(), 10 * time()); ?>" parking-name="Parkiran Bayangan BP">
                                                    <?= (!empty($key) || $key === 0) ? $grupF[$key]['model_code'] . " | " . $grupF[$key]['license_plate'] . "<br>" . $grupF[$key]['category'] : "" ?>
                                                </a>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <!-- Batas Parkiran Bayangan BP -->

                            <!-- Parkiran Pojok BP -->
                            <div class="d-flex mt-2 gap-1 justify-content-end w-100 mb-5 me-5 gap-2">
                                <?php for ($position = 8; $position <= 15; $position++) : ?>
                                    <?php $seat = 'seat-default'; ?>
                                    <?php if ($position == 8) {
                                        $seat = 'seat text-dark';
                                    } else {
                                        $seat = 'seat-default text-dark';
                                    } ?>
                                    <?php $key = $controller->cari_parkir($grupF, $position); ?>
                                    <a class="<?= $seat; ?> seat-vertical" grup="F" position="<?= $position; ?>" id="<?= rand($position * time(), 10 * time()); ?>" parking-name="Parkiran BP">
                                        <?= (!empty($key) || $key === 0) ? $grupF[$key]['model_code'] . " | " . $grupF[$key]['license_plate'] . "<br>" . $grupF[$key]['category'] : "" ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                            <!-- Batas Parkiran Pojok BP -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="empty"></div>
    <div class="wrapper"></div>
    <nav class="bottom-nav justify-content-between">
        <a href="/" class="cancel-button d-flex">
            <span class="material-icons">
                navigate_before
            </span>
            Summary
        </a>
        <?php if ($date != date('Y-m-d')) : ?>
            <a href="/parkir/stall_gr/<?= $date; ?>" class="next-button d-flex">
                Stall GR
                <span class="material-icons">
                    navigate_next
                </span>
            </a>
        <?php else : ?>
            <a href="/parkir/stall_gr" class="next-button d-flex">
                Stall GR
                <span class="material-icons">
                    navigate_next
                </span>
            </a>
        <?php endif; ?>
    </nav>
</section>
<?= $this->endSection(); ?>