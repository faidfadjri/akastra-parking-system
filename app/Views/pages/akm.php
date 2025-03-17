<?= $this->extend('app'); ?>

<?= $this->section('content'); ?>
<section class="main-section">
    <div class="main-area">
        <div class="d-flex flex-column align-items-center">
            <div class="akm flex-column gap-3">
                <p>Stall AKM</p>
                <?php for ($grup = 1; $grup <= 3; $grup++) : ?>

                    <?php $posStart = 1;
                    $posEnd = 2;

                    if ($grup == 2) {
                        $posStart = 3;
                        $posEnd = 4;
                    } else if ($grup == 3) {
                        $posStart = 5;
                        $posEnd = 6;
                    } ?>

                    <div class="d-flex gap-3">
                        <?php for ($position = $posStart; $position <= $posEnd; $position++) : ?>
                            <?php $key = $controller->cari_parkir($grupP, $position); ?>
                            <a id="<?= rand($position * 2000, time()); ?>" class="<?= ($grup == 3) ? "seat" : "seat-yellow"; ?> seat-vertical" grup="P" parking-name="<?= ($grup == 3) ? "Parkiran Bayangan AKM" : "Parkiran AKM"; ?>" position="<?= $position ?>">
                                <?= (!empty($key) || $key === 0) ? $grupP[$key]['model_code'] . " | " . $grupP[$key]['license_plate'] . "<br>" . $grupP[$key]['category'] : "" ?>
                            </a>
                        <?php endfor; ?>
                    </div>
                <?php endfor; ?>
                <div class="pintu"></div>
            </div>

            <div class="teras mb-2">
                <div class="meja"></div>
            </div>

            <?php for ($grup = 1; $grup <= 2; $grup++) : ?>
                <div class="d-flex gap-3 mb-2 mt-2 w-100 justify-content-center">
                    <?php $posStart = 7;
                    $posEnd = 9;

                    if ($grup == 2) {
                        $posStart = 10;
                        $posEnd = 12;
                    } ?>
                    <?php for ($position = $posStart; $position <= $posEnd; $position++) : ?>
                        <?php $seat = "seat-yellow";
                        $parkingName = "Parkiran AKM"; ?>
                        <?php if ($position == 9 || $position == 12) {
                            $seat = "seat";
                            $parkingName = "Parkiran Bayangan AKM";
                        } ?>
                        <?php $key = $controller->cari_parkir($grupP, $position); ?>
                        <a id="<?= rand($position * 2000, time()); ?>" class="<?= $seat; ?> seat-vertical" grup="P" position="<?= $position; ?>" parking-name="<?= $parkingName; ?>">
                            <?= (!empty($key) || $key === 0) ? $grupP[$key]['model_code'] . " | " . $grupP[$key]['license_plate'] . "<br>" . $grupP[$key]['category'] : "" ?>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endfor; ?>


        </div>
        <div class=" parkiran-motor">
            <p>Parkiran Motor</p>
        </div>
    </div>
</section>
<nav class="bottom-nav justify-content-center">
    <?php if ($date != date('Y-m-d')) : ?>
        <a class="cancel-button d-flex align-items-center justify-content-center" href="/parkir/stall_gr/<?= $date; ?>">
            <span class="material-icons">
                navigate_before
            </span>
            Stall BP
        </a>
    <?php else : ?>
        <a class="cancel-button d-flex align-items-center justify-content-center" href="/parkir/stall_gr">
            <span class="material-icons">
                navigate_before
            </span>
            Stall BP
        </a>
    <?php endif; ?>
</nav>
<?= $this->endSection(); ?>