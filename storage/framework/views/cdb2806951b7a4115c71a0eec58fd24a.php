

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row mb-4">
            <div class="col-md-8 mb-2">
                <div class="embed-responsive embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                        src="<?php echo e(url('/laraview/#../storage/files/kebenaran-dokumen/' . $documentAuthorizationLetter->file_path)); ?>"></iframe>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col">
                        <p class="h5 text-body font-weight-bold">
                            <?php echo e($documentAuthorizationLetter->title); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Nomor Kebenaran Dokumen</b>
                            <br>
                            <?php echo e($documentAuthorizationLetter->number); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Dibuat Oleh</b>
                            <br>
                            <?php echo e($documentAuthorizationLetter->created_by); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Tanggal Dibuat</b>
                            <br>
                            <?php
                                $created_at = explode(' ', $documentAuthorizationLetter->created_at);
                                $created_at = $created_at[0];
                            ?>
                            <?php echo e($created_at); ?>

                        </p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <a href="<?php echo e(url('/kebenaran-dokumen/dokumen/' . $documentAuthorizationLetter->id)); ?>" target="_blank"><button type="button" class="btn btn-info">Cetak KD</button></a>
                        <a href="<?php echo e(url('storage/files/kebenaran-dokumen/' . $documentAuthorizationLetter->file_path)); ?>" target="_blank"><button type="button" class="btn btn-primary">Cetak
                                All</button></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\muham\Documents\Programming\project\pelindo\e-agreement\resources\views/pages/documentauthorizationletter/detaildocumentauthorizationletter.blade.php ENDPATH**/ ?>