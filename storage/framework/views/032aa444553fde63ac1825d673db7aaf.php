

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row mb-4">
            <div class="col-md-8 mb-2">
                <div class="embed-responsive embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                        src="<?php echo e(asset('/laraview/#../storage/' . $outgoingmail->file_path)); ?>"></iframe>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col">
                        <p class="h5 text-body font-weight-bold">
                            <?php echo e($outgoingmail->title); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Nomor Surat Keluar</b>
                            <br>
                            <?php echo e($outgoingmail->number); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Dibuat Oleh</b>
                            <br>
                            <?php echo e($outgoingmail->created_by); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Tanggal Dibuat</b>
                            <br>
                            <?php
                                $created_at = explode(' ', $outgoingmail->created_at);
                                $created_at = $created_at[0];
                            ?>
                            <?php echo e($created_at); ?>

                        </p>
                    </div>
                </div>
                <div class="row">
                    <a href="<?php echo e(url('storage/' . $outgoingmail->file_path)); ?>"><button type="button" class="btn btn-primary">Cetak Nota Dinas</button></a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\muham\Documents\Programming\project\pelindo\e-agreement\resources\views/pages/outgoingmail/detailoutgoingmail.blade.php ENDPATH**/ ?>