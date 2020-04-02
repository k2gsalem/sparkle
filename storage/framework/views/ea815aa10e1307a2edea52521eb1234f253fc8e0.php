<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Album - <?php echo e($album['album_name']); ?></div>
                    <?php if(session('error') !== null): ?>                        
                        <div class='alert alert-danger'>
                            <?php echo e(session('error')); ?>

                        </div>
                    <?php endif; ?>
                    <?php if(session('success') !== null): ?>                        
                        <div class='alert alert-success'>
                            <?php echo e(session('success')); ?>

                        </div>
                    <?php endif; ?>
                <div class='card-body'>
                    <img src='/storage/cover_pictures/<?php echo e($album["cover_picture"]); ?>'
                        height="256" width="256"> <br><br>
                    
                    Album Description - <?php echo e($album['album_description']); ?> <br> <br>
                    Privacy - <?php echo e(['','Public','Link Accessible','Private'][$album['privacy']]); ?> <br>
                    <br>
                    Created At - <?php echo e($album['created_at']); ?> <br>
                    Last Modified At - <?php echo e($album['updated_at']); ?> <br>

                    <br> 

                    Likes - <?php echo e(isset($likes)?$likes:0); ?>

                    <?php if(isset($user_status) and $user_status !== -1): ?>
                        <?php if($user_status == 0): ?>
                            <?php echo e(Form::open(['action'=>['Web\AlbumController@like',$album['id']],
                                        'method'=>'put',
                                        'class'=>'pull-right'])); ?>

                                <?php echo e(Form::submit('Like',['class'=>'btn btn-primary pull-right'])); ?>

                            <?php echo e(Form::close()); ?>

                        <?php else: ?>
                            <?php echo e(Form::open(['action'=>['Web\AlbumController@unlike',$album['id']],
                                        'method'=>'put',
                                        'class'=>'pull-right'])); ?>

                                <?php echo e(Form::submit('Unlike',['class'=>'btn btn-primary pull-right'])); ?>

                            <?php echo e(Form::close()); ?>

                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <br>
                <div class="card-header">Photos</div>
                <div class='card-body'>
                    <?php if(isset($photos) and count($photos)>0): ?>
                        <?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <h6><?php echo e($loop->iteration); ?>.  
                                    <a href="/photos/<?php echo e($photo['id']); ?>"><?php echo e($photo['photo']); ?> <?php echo e($photo['id']); ?><a>
                                </h6>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        No photos to show.
                    <?php endif; ?>
                    <br>
                    <?php if(Auth::check() and $album['user_id'] === Auth::user()->id): ?>
                        <a href='/photos/upload/<?php echo e($album["id"]); ?>' class="btn btn-primary" class="row justify-content-right">Add Photo<a>
                    <?php endif; ?>
                </div>
            </div>
            <div class='Buttons'>
            <br>
                <?php if(Auth::check() and $album['user_id'] === Auth::user()->id): ?>
                    <a href="/albums/<?php echo e($album['id']); ?>/edit" class="btn btn-primary" class="row justify-content-left">Edit<a>
                    <br><br>
                    <?php echo e(Form::open(['action'=>['Web\AlbumController@delete',$album['id']],
                                'method'=>'delete',
                                'class'=>'pull-right',
                                'onsubmit' => 'return ConfirmDelete()'])); ?>

                        <?php echo e(Form::submit('Delete',['class'=>'btn btn-danger pull-right'])); ?>

                    <?php echo e(Form::close()); ?>

                    <script>
                        function ConfirmDelete()
                        {
                        var x = confirm("Are you sure you want to delete?");
                        if (x)
                            return true;
                        else
                            return false;
                        }
                    </script>
                <?php endif; ?>
            </div>
        </div> 
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/photoz-restful/resources/views/album/albumpage.blade.php ENDPATH**/ ?>