<?php $__env->startSection('content'); ?>
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row mb-4">
            <div class="card">
                <div class="card-body">
                    <p class="h5 font-weight-bold">
                        <?php echo e($documentAuthorizationLetter->title); ?>

                    </p>
                    <hr class="my-2 border-black">
                    <div class="row">
                        <div class="col-6">
                            <p><b>Nomor Kebenaran Dokumen</b>
                                <br>
                                <?php echo e($documentAuthorizationLetter->number); ?>

                            </p>
                        </div>
                        <div class="col-6">
                            <p><b>Nomor Nota Dinas Pembayaran</b>
                                <br>
                                <?php echo e($documentAuthorizationLetter->payment_number); ?>

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p><b>Nomor PAA</b>
                                <br>
                                <?php echo e($documentAuthorizationLetter->contract_number); ?>

                            </p>
                        </div>
                        <div class="col-6">
                            <p><b>Total Pembayaran</b>
                                <br>
                                Rp. <?php echo e($documentAuthorizationLetter->payment_total); ?>

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p><b>Nama Vendor</b>
                                <br>
                                <?php echo e($documentAuthorizationLetter->vendor_name); ?>

                            </p>
                        </div>
                        <div class="col-6">
                            <p><b>Nomor Rekening Vendor</b>
                                <br>
                                <?php echo e($documentAuthorizationLetter->account_number); ?>

                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p><b>Tanggal Dibuat</b>
                                <br>
                                <?php
                                    $created_at = explode(' ', $documentAuthorizationLetter->created_at);
                                    $created_at = $created_at[0];
                                ?>
                                <?php echo e($created_at); ?>

                            </p>
                        </div>
                        <div class="col-6">
                            <p><b>Dibuat Oleh</b>
                                <br>
                                <?php echo e($documentAuthorizationLetter->created_by); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\APLIKASI\www\siap\resources\views/pages/documentauthorizationletter/detaildocumentauthorizationletter.blade.php ENDPATH**/ ?>