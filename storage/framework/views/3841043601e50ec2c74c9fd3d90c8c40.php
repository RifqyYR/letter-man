<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <div class="d-sm-flex align-items-center justify-content-between mb-3">
            <h1 class="h3 mb-0 text-gray-800">Beranda</h1>
        </div>

        <div class="row">
            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Nota Dinas</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($officialMemoTotal); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Total Surat Berita Acara</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($newsTotal); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-4 mb-4">
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Total Surat Keluar</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($outgoingMailTotal); ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Total Surat Kebenaran Dokumen</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($documentAuthorizationLetterTotal); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6 mb-4">
                <div class="card border-left-secondary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                    Total Arsip</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($archiveTotal); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if(Auth::user()->role != 0): ?>
            <div class="d-sm-flex align-items-center justify-content-between mb-3">
                <h1 class="h3 mb-0 text-gray-800">Unduh Rekap</h1>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <a class="btn btn-primary btn-block" data-toggle="modal" data-target="#officialMemoModal">Nota Dinas</a>
                </div>
                <div class="col-md-4 mb-2">
                    <a class="btn btn-success btn-block" data-toggle="modal" data-target="#newsModal">Berita Acara</a>
                </div>
                <div class="col-md-4 mb-2">
                    <a class="btn btn-danger btn-block" data-toggle="modal" data-target="#outgoingMailModal">Surat
                        Keluar</a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <a class="btn btn-warning btn-block" data-toggle="modal"
                        data-target="#documentAuthorizationLetterModal">Kebenaran Dokumen</a>
                </div>
                <div class="col-md-6 mb-2">
                    <a class="btn btn-secondary btn-block" data-toggle="modal" data-target="#archiveModal">Arsip</a>
                </div>
            </div>
        <?php endif; ?>
    </div>

    
    <div class="modal fade" id="officialMemoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/rekap-nota-dinas" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rekap Nota Dinas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAwal">Tanggal Awal</label>
                                    <input type="date" id="tanggalAwal"
                                        class="form-control <?php $__errorArgs = ['tanggalAwal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggalAwal"
                                        autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAwal'];
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAkhir">Tanggal Akhir</label>
                                    <input type="date" id="tanggalAkhir"
                                        class="form-control <?php $__errorArgs = ['tanggalAkhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="tanggalAkhir" autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAkhir'];
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="btn-delete">Batal</button>
                        <button type="submit" class="btn btn-info">Unduh</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/rekap-berita-acara" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rekap Berita Acara</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo csrf_field(); ?>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAwal">Tanggal Awal</label>
                                    <input type="date" id="tanggalAwal"
                                        class="form-control <?php $__errorArgs = ['tanggalAwal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggalAwal"
                                        autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAwal'];
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAkhir">Tanggal Akhir</label>
                                    <input type="date" id="tanggalAkhir"
                                        class="form-control <?php $__errorArgs = ['tanggalAkhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="tanggalAkhir" autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAkhir'];
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="btn-delete">Batal</button>
                        <button type="submit" class="btn btn-info">Unduh</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="outgoingMailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/rekap-surat-keluar" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rekap Surat Keluar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo csrf_field(); ?>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAwal">Tanggal Awal</label>
                                    <input type="date" id="tanggalAwal"
                                        class="form-control <?php $__errorArgs = ['tanggalAwal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggalAwal"
                                        autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAwal'];
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAkhir">Tanggal Akhir</label>
                                    <input type="date" id="tanggalAkhir"
                                        class="form-control <?php $__errorArgs = ['tanggalAkhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="tanggalAkhir" autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAkhir'];
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="btn-delete">Batal</button>
                        <button type="submit" class="btn btn-info">Unduh</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="documentAuthorizationLetterModal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/rekap-kd" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rekap Kebenaran Dokumen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo csrf_field(); ?>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAwal">Tanggal Awal</label>
                                    <input type="date" id="tanggalAwal"
                                        class="form-control <?php $__errorArgs = ['tanggalAwal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggalAwal"
                                        autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAwal'];
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAkhir">Tanggal Akhir</label>
                                    <input type="date" id="tanggalAkhir"
                                        class="form-control <?php $__errorArgs = ['tanggalAkhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="tanggalAkhir" autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAkhir'];
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="btn-delete">Batal</button>
                        <button type="submit" class="btn btn-info">Unduh</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="archiveModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="/rekap-arsip" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Rekap Arsip</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <?php echo csrf_field(); ?>
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAwal">Tanggal Awal</label>
                                    <input type="date" id="tanggalAwal"
                                        class="form-control <?php $__errorArgs = ['tanggalAwal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tanggalAwal"
                                        autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAwal'];
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
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggalAkhir">Tanggal Akhir</label>
                                    <input type="date" id="tanggalAkhir"
                                        class="form-control <?php $__errorArgs = ['tanggalAkhir'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        name="tanggalAkhir" autocomplete="off" value="<?php echo e(now()->toDateString()); ?>" />
                                    <?php $__errorArgs = ['tanggalAkhir'];
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
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            id="btn-delete">Batal</button>
                        <button type="submit" class="btn btn-info">Unduh</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\muham\Documents\Programming\project\pelindo\e-agreement\resources\views/pages/home.blade.php ENDPATH**/ ?>