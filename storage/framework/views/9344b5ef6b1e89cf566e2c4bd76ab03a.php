<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row mb-4">
            <div class="col-md-8 mb-2">
                <div class="embed-responsive embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                        src="<?php echo e(asset('/laraview/#../storage/' . $archive->file_path)); ?>"></iframe>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col">
                        <p class="h5 text-body font-weight-bold">
                            <?php echo e($archive->title); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Nomor Arsip</b>
                            <br>
                            <?php echo e($archive->number); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Dibuat Oleh</b>
                            <br>
                            <?php echo e($archive->created_by); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Tanggal Dibuat</b>
                            <br>
                            <?php
                                $created_at = explode(' ', $archive->created_at);
                                $created_at = $created_at[0];
                            ?>
                            <?php echo e($created_at); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <a href="<?php echo e(url('storage/' . $archive->file_path)); ?>" target="_blank"><button type="button" class="btn btn-primary">Cetak Arsip</button></a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\APLIKASI\www\siap\resources\views/pages/archive/detailarchive.blade.php ENDPATH**/ ?>