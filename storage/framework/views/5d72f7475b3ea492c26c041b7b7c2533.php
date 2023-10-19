

<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-body font-weight-bold"><?php echo e($officialMemo->title); ?></h1>
        </div>
        <div class="row mb-4">
            <div class="col-md-7">
                <div class="embed-responsive embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                        src="<?php echo e(asset('/laraview/#../storage/' . $officialMemo->file_path)); ?>"></iframe>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\muham\Documents\Programming\project\pelindo\e-agreement\resources\views/pages/officialmemo/detailofficialmemo.blade.php ENDPATH**/ ?>