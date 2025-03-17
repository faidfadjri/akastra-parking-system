<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="/parkir/tambah_parkir" method="POST" id="parking-form">
                <div class="modal-header border-0">
                    <h5 class="modal-title" id="staticBackdropLabel">Vehicle Info</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body border-0">
                    <!-- Hidden Key Input -->
                    <input type="hidden" name="parking[lokasi]" id="parking-lokasi" class="form-control mb-2" value="<?= $lokasi; ?>" readonly>
                    <input type="hidden" name="parking[grup]" id="parking-grup" class="form-control mb-2" readonly>
                    <input type="hidden" name="parking[position]" id="parking-position" class="form-control mb-2" readonly>
                    <input type="hidden" name="id" id="parking-id" class="form-control mb-2" readonly>
                    <input type="hidden" id="seat-id" class="form-control mb-2" readonly>
                    <input type="text" id="parking-name" name="parking[jenis_parkir]" class="form-control mb-2" readonly>
                    <input type="hidden" name="parking[date]" id="current-date" readonly value="<?= $date; ?>">
                    <!-- End Hidden Key Input -->

                    <div class="mb-3">
                        <label for="parking-license-plate" class="form-label">Nomor Polisi</label>
                        <input type="text" name="license_plate" id="parking-license-plate" class="form-control text-uppercase" placeholder="Ketikan Nomor Polisi" required>
                    </div>
                    <div class="mb-3">
                        <label for="parking-model" class="form-label">Model Kendaraan</label>
                        <select name="parking[model_code]" id="parking-model" class="form-control" required>
                            <option value="">Pilih Model</option>
                            <?php foreach ($model as $row) : ?>
                                <option value="<?= $row['model_code']; ?>"><?= $row['model']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="other-wrap">
                        <label for="parking-other" class="form-label">Others Model</label>
                        <input type="text" name="parking[others]" id="parking-other" class="form-control" placeholder="Ketikan Model">
                    </div>
                    <div class="mb-3">
                        <label for="parking-status" class="form-label">Status Kendaraan</label>
                        <select name="parking[status]" id="parking-status" class="form-control" required>
                            <option value="">Pilih Status Kendaraan</option>
                            <option>TUNGGU PENYERAHAN</option>
                            <option>TUNGGU SPARE PART</option>
                            <option>TUNGGU SPK</option>
                            <option>TUNGGU ASURANSI</option>
                            <option>TUNGGU PERBAIKAN</option>
                            <option>TUNGGU OPL</option>
                            <option>TUNGGU PBT</option>
                            <option>TUNGGU KONFIRMASI CUSTOMER</option>
                            <option>INTERNAL</option>
                            <option>TAMU</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="parking-job" class="form-label">Pekerjaan</label>
                        <select name="parking[category]" id="parking-job" class="form-control" required>
                            <option value="">Pilih Pekerjaan</option>
                            <option>GR</option>
                            <option>BP</option>
                            <option>AKM</option>
                            <option class="none">Tidak Servis</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <?php if ($date == date('Y-m-d')) :  ?>
                        <button type="button" class="btn btn-danger btn-delete d-none shadow d-flex align-items-center justify-content-center gap-2">
                            Hapus
                            <span class="material-icons">
                                remove_circle
                            </span>
                        </button>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary shadow btn-submit d-flex align-items-center justify-content-center gap-2">
                        Simpan
                        <span class="material-icons">
                            save
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>