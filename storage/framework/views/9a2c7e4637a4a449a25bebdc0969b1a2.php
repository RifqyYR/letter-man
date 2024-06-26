<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><?php echo e(__('Edit User')); ?></div>

                    <div class="card-body">
                        <form method="POST" action="/proses-edit-user">
                            <?php echo csrf_field(); ?>

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end"><?php echo e(__('Name')); ?></label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="name"
                                        value="<?php echo e($user->name); ?>" required autocomplete="name" autofocus>

                                    <?php $__errorArgs = ['name'];
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

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('NRP / NIPP')); ?></label>

                                <div class="col-md-6">
                                    <input id="email" type="text"
                                        class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email"
                                        value="<?php echo e($user->email); ?>" required autocomplete="email">

                                    <?php $__errorArgs = ['email'];
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

                            <div class="row mb-3">
                                <label for="role"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Admin')); ?></label>

                                <div class="col-md-6">
                                    <select class="form-select" name="role">
                                        <option <?php echo e($user->role == 0 ? 'selected' : ''); ?> value="0">User Biasa</option>
                                        <option <?php echo e($user->role == 1 ? 'selected' : ''); ?> value="1">Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="work_unit"
                                    class="col-md-4 col-form-label text-md-end"><?php echo e(__('Unit Kerja')); ?></label>

                                <div class="col-md-6">
                                    <select class="form-select" name="work_unit">
                                        <option <?php echo e($user->work_unit == 'WIL4' ? 'selected' : ''); ?> value="WIL4">Wilayah 4</option>
                                        <option <?php echo e($user->work_unit == 'KAL1' ? 'selected' : ''); ?> value="KAL1">Kalimantan 1</option>
                                        <option <?php echo e($user->work_unit == 'KAL2' ? 'selected' : ''); ?> value="KAL2">Kalimantan 2</option>
                                        <option <?php echo e($user->work_unit == 'SUL1' ? 'selected' : ''); ?> value="SUL1">Sulawesi 1</option>
                                        <option <?php echo e($user->work_unit == 'SUL2' ? 'selected' : ''); ?> value="SUL2">Sulawesi 2</option>
                                        <option <?php echo e($user->work_unit == 'MAPA' ? 'selected' : ''); ?> value="MAPA">Maluku dan Papua</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\APLIKASI\www\siap\resources\views/pages/user/editUser.blade.php ENDPATH**/ ?>