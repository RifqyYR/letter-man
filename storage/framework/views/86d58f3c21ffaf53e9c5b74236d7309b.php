<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        
        <div class="row">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Tambah Kebenaran Dokumen</h1>
            </div>
        </div>

        
        <div class="card mt-2">
            <div class="card-body">
                <form action="/proses-tambah-kebenaran-dokumen" method="post" enctype="multipart/form-data">
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
                        <label for="tujuan">Tujuan Surat</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioTemplate" value="PJM"
                                id="radioTemplate1" checked>
                            <label class="form-check-label" for="radioTemplate1">
                                PJM
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioTemplate" value="HO"
                                id="radioTemplate2" disabled>
                            <label class="form-check-label" for="radioTemplate2">
                                Head Office
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="namaSurat">Nama Pekerjaan</label>
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
                        <input type="date" id="tanggalPembuatan"
                            class="form-control <?php $__errorArgs = ['tanggalPembuatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggalPembuatan"
                            autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
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
                                name="nomorSurat" id="nomorSurat" value="<?php echo e($documentAuthorizationLetterNumber); ?>" readonly>
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
                        <label for="nomorKontrak">Nomor PAA</label>
                        <div class="input-group">
                            <input type="text" class="form-control <?php $__errorArgs = ['nomorKontrak'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="nomorKontrak" id="nomorKontrak" value="<?php echo e(old('nomorKontrak')); ?>">
                        </div>
                        <?php $__errorArgs = ['nomorKontrak'];
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
                        <label for="namaVendor">Nama Vendor</label>
                        <select class="form-control <?php $__errorArgs = ['namaVendor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="namaVendor"
                            value="<?php echo e(old('namaVendor')); ?>" id="editable-select" style="text-transform: uppercase;">
                            <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($item->id); ?>"><?php echo e($item->name . ' - ' . $item->bank_name . ' - ' . $item->account_number); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['namaVendor'];
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
                        <label for="jumlahPembayaran">Jumlah Pembayaran</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control <?php $__errorArgs = ['jumlahPembayaran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="jumlahPembayaran" id="jumlahPembayaran" value="<?php echo e(old('jumlahPembayaran')); ?>"
                                onkeyup="moneyFormat(this)">
                        </div>
                        <?php $__errorArgs = ['jumlahPembayaran'];
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
                        <label for="bankPenerima">Bank Penerima</label>
                        <div class="input-group">
                            <input type="text" class="form-control <?php $__errorArgs = ['bankPenerima'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="bankPenerima" id="bankPenerima" value="<?php echo e(old('bankPenerima')); ?>">
                        </div>
                        <?php $__errorArgs = ['bankPenerima'];
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
                        <label for="nomorRekening">Nomor Rekening</label>
                        <div class="input-group">
                            <input type="text" class="form-control <?php $__errorArgs = ['nomorRekening'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="nomorRekening" id="nomorRekening" value="<?php echo e(old('nomorRekening')); ?>">
                        </div>
                        <?php $__errorArgs = ['nomorRekening'];
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
                        <label for="fileLampiran">Upload Lampiran</label>
                        <input type="file" accept=".pdf, .txt"
                            class="form-control-file my-pond <?php $__errorArgs = ['fileLampiran'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="fileLampiran" name="fileLampiran[]" multiple data-allow-reorder="true">
                        <?php $__errorArgs = ['fileLampiran'];
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

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\APLIKASI\www\siap\resources\views/pages/documentauthorizationletter/createdocumentauthorizationletter.blade.php ENDPATH**/ ?>