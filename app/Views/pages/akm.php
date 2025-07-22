<?= $this->extend('app'); ?>
<?= $this->section('content'); ?>

<?php
$mobilGrupPos = [
    1 => [1, 2],
    2 => [3, 4],
    3 => [5, 6],
];

$motorGrupPos = [
    1 => [7, 9],
    2 => [10, 12],
];

function renderParkingSpot($controller, $grupP, $position, $isBayangan = false)
{
    $seatClass = $isBayangan ? 'seat' : 'seat-yellow';
    $parkingName = $isBayangan ? 'Parkiran Bayangan AKM' : 'Parkiran AKM';
    $key = $controller->cari_parkir($grupP, $position);
    $content = (!empty($key) || $key === 0) ? $grupP[$key]['model_code'] . " | " . $grupP[$key]['license_plate'] . "<br>" . $grupP[$key]['category'] : "";

    return "<a id='seat-$position' class='$seatClass seat-vertical' grup='P' position='$position' parking-name='$parkingName'>$content</a>";
}
?>

<section class="main-section">
    <?= $this->include('items/zoom-in-out.php'); ?>
    <h1 class="headline">Area Stall BP</h1>
    <div class="main-area">
        <div class="d-flex flex-column align-items-center akm-wrapper">
            <div class="d-flex w-full">
                <div>
                    <div class="akm flex-column gap-3">
                        <p>Stall AKM</p>
                        <?php foreach ($mobilGrupPos as $grup => [$start, $end]) : ?>
                            <div class="d-flex gap-3">
                                <?php
                                for ($position = $start; $position <= $end; $position++) {
                                    echo renderParkingSpot($controller, $grupP, $position, $grup == 3);
                                }
                                ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="teras mb-2">
                        <div class="meja"></div>
                    </div>
                </div>

                <div class="parkiran-motor">
                    <p>Parkiran Motor</p>
                </div>
            </div>

            <div class="d-flex flex-column">
                <?php foreach ($motorGrupPos as $grup => [$start, $end]) : ?>
                    <div class="d-flex gap-3 mb-2 mt-2 w-100 justify-content-center">
                        <?php
                        for ($position = $start; $position <= $end; $position++) {
                            $isBayangan = in_array($position, [9, 12]);
                            echo renderParkingSpot($controller, $grupP, $position, $isBayangan);
                        }
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<nav class="bottom-nav justify-content-center">
    <a class="cancel-button d-flex align-items-center justify-content-center"
        href="/parkir/stall_bp<?= $date != date('Y-m-d') ? "/$date" : '' ?>">
        <span class="material-icons">navigate_before</span>
        Stall BP
    </a>
</nav>

<?= $this->endSection(); ?>