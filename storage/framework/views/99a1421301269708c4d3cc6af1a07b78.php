<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        
        <div class="row">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Tambah Nota Dinas</h1>
            </div>
        </div>

        
        <div class="card mt-2">
            <div class="card-body">
                <form action="/proses-tambah-nota-dinas" method="post" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="unitKerja">Unit Kerja</label><br>
                        <select class="form-select" aria-label="Default select example" name="unitKerja" id="unitKerja">
                            <option value="wil4" selected>Wilayah 4</option>
                            <option value="kal1">Kalimantan 1</option>
                            <option value="kal2">Kalimantan 2</option>
                            <option value="sul1">Sulawesi 1</option>
                            <option value="sul2">Sulawesi 2</option>
                            <option value="mapa">Maluku dan Papua</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="namaSurat">Judul</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['namaSurat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="namaSurat"
                            value="<?php echo e(old('namaSurat')); ?>">
                        <?php $__errorArgs = ['namaSurat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="tanggalPembuatan">Tanggal Pembuatan</label>
                        <input type="date" id="tanggalPembuatan" required
                            class="form-control <?php $__errorArgs = ['tanggalPembuatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggalPembuatan"
                            autocomplete="off" value="<?php echo e(old('tanggalPembuatan')); ?>" />
                        <?php $__errorArgs = ['tanggalPembuatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="nomorSurat">Nomor Surat</label>
                        <div class="input-group">
                            <input type="text" class="form-control <?php $__errorArgs = ['nomorSurat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="nomorSurat" id="nomorSurat" value="<?php echo e($officialMemoNumber); ?>" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-info" id="btnSetReadonly">Ubah Nomor</button>
                            </div>
                        </div>
                        <?php $__errorArgs = ['nomorSurat'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label for="fileNotaDinas">Upload Nota Dinas</label>
                        <input type="file" class="form-control-file <?php $__errorArgs = ['fileNotaDinas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="fileNotaDinas" name="fileNotaDinas" accept=".pdf">
                        <?php $__errorArgs = ['fileNotaDinas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="invalid-feedback" role="alert">
                                <strong><?php echo e($message); ?></strong>
                            </span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group mt-5">
                        <input type="submit" class="btn btn-info" value="Tambah">
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\APLIKASI\www\siap\resources\views/pages/officialmemo/createofficialmemo.blade.php ENDPATH**/ ?>