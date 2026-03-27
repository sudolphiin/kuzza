<?php
    $routes = subModuleRoute($child);
?>

<?php
    $icon = null;
    $all_modules = ['g-meet','zoom','bbb','jitsi'];
    if(in_array($child->route, $all_modules)){
        $icon = 'fas fa-video';
    }
?>

<li class="<?php echo e(spn_active_link($routes, "mm-active")); ?>  main ">
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
        <div class="nav_icon_small">
            <span class="<?php echo e(!empty($child->icon) ? $child->icon:$icon); ?>"></span>
        </div>
        <div class="nav_title">
             <span>  <?php echo e(!empty($child->lang_name) ? __($child->lang_name):$child->name); ?> </span>
        </div>
    </a>

    

    <ul class="mm-collapse">  

        <?php if($child->route == 'download-center'): ?>
            
            <?php if(userPermission("download-center.parent-content-share-list")): ?>
                <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="sub">
                        <a href="<?php echo e(validRouteUrl('download-center.parent-content-share-list', $c->id)); ?>"
                           class="<?php echo e(spn_active_link('download-center.parent-content-share-list')); ?>">
                            <?php echo e(__('downloadCenter.content_list')); ?> - <?php echo e($c->full_name); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
            <?php if(userPermission("download-center.parent-video-list")): ?>
                <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="sub">
                        <a href="<?php echo e(validRouteUrl('download-center.parent-video-list', $c->id)); ?>"
                           class="<?php echo e(spn_active_link('download-center.parent-video-list')); ?>">
                            <?php echo e(__('downloadCenter.video_list')); ?> - <?php echo e($c->full_name); ?>

                        </a>
                    </li>

                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        <?php else: ?>    
            <?php if($child->route == 'lesson-plan'): ?>
                
                 <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if(userPermission($third->route)): ?>
                        <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="sub">
                                <a href="<?php echo e(validRouteUrl($third->route, $c->id)); ?>"
                                class="<?php echo e(spn_active_link($third->route)); ?>">
                                    <?php echo e(__($third->lang_name)); ?> - <?php echo e($c->full_name); ?>

                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php endif; ?>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>    
                <?php if($child->route == 'exam'): ?>
                    
                    <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if(userPermission($third->route)): ?>
                            <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="sub">
                                    <a href="<?php echo e(validRouteUrl($third->route, $c->id)); ?>"
                                    class="<?php echo e(spn_active_link($third->route)); ?>">  <?php echo e(__($third->lang_name)); ?> - <?php echo e($c->full_name); ?> </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>    
                    <?php if($child->route == 'g-meet'): ?>
                        
                        <?php if(userPermission("g-meet.virtual-meeting.index")): ?>
                            <li class="sub">
                                <a href="<?php echo e(validRouteUrl('g-meet.virtual-meeting.index')); ?>"
                                class="<?php echo e(spn_active_link('g-meet.virtual-meeting.index')); ?>">
                                    <?php echo e(__('common.virtual_meeting')); ?>

                                </a>
                            </li>
                        <?php endif; ?>
                        <?php if(userPermission("g-meet.parent.virtual-class")): ?>
                            <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="sub">
                                    <a href="<?php echo e(validRouteUrl('g-meet.virtual-class.parent.virtual-class', $c->id)); ?>" class="<?php echo e(spn_active_link('g-meet.virtual-class.parent.virtual-class')); ?>">
                                        <?php echo e(__('common.virtual_class')); ?> - <?php echo e($c->full_name); ?>

                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    <?php else: ?>    
                        <?php if($child->route == 'jitsi'): ?>
                        
                                <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>     

                                    <?php if($third->route == 'jitsi.parent.virtual-class'): ?>
                                         <?php if(userPermission($third->route)): ?>
                                            <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="sub">
                                                    <a href="<?php echo e(validRouteUrl($third->route,$c->id)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> <?php echo e($third->route); ?>">                                                               
                                                        <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>-<?php echo e($c->full_name); ?>

                                                    </a>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php else: ?> 

                                        <?php if(userPermission($third->route)): ?>
                                            <li class="sub">
                                                <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> <?php echo e($third->route); ?>">                                                               
                                                    <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>

                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                       
                        <?php else: ?>    
                            <?php if($child->route == 'bbb'): ?>
                                <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                  
                                    <?php if(userPermission($third->route)): ?>
                                        <?php if($third->route == 'bbb.parent.virtual-class' || $third->route == 'bbb.parent.class.recording.list'): ?>
                                             <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li class="sub">
                                                    <a href="<?php echo e(validRouteUrl($third->route,$c->id)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> ">                                                               
                                                        <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?> - <?php echo e($c->full_name); ?>

                                                    </a>
                                                </li>
                                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>    
                                            <li class="sub">
                                                <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?>">                                                               
                                                    <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>              
                            <?php else: ?> 
                                <?php if($child->route == 'zoom'): ?>
                                    <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                  
                                        <?php if(userPermission($third->route)): ?>
                                            <?php if($third->route == 'zoom.parent.virtual-class'): ?>
                                                <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="sub">
                                                        <a href="<?php echo e(validRouteUrl($third->route,$c->id)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> ">                                                               
                                                            <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?> - <?php echo e($c->full_name); ?>

                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php else: ?>     
                                                <li class="sub">
                                                    <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> " >                                                               
                                                        <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                    </a>
                                                </li>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>  
                                    <?php if($child->route == 'lms_menu'): ?>
                                            <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                  
                                                <?php if(userPermission($third->route)): ?>
                                                    <?php if($third->route == 'lms.enrolledCourse' || $third->route == 'lms.student.purchaseLog'): ?>
                                                        <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li class="sub">
                                                                <a href="<?php echo e(validRouteUrl($third->route,$c->id)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> ">                                                               
                                                                    <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?> - <?php echo e($c->full_name); ?>

                                                                </a>
                                                            </li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    <?php else: ?>    
                                                        <li class="sub">
                                                            <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> ">                                                               
                                                                <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                            </a>
                                                        </li>
                                                    <?php endif; ?>

                                                    
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>   

                                        <?php if($child->route == 'online_exam'): ?>
                                            <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php $__currentLoopData = $children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $c): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li class="sub">
                                                        <a href="<?php echo e(validRouteUrl($third->route,$c->id)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> ">                                                               
                                                            <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?> - <?php echo e($c->full_name); ?>

                                                        </a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>    
                                            <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                  
                                                <?php if(userPermission($third->route)): ?>
                                                    <li class="sub">
                                                        <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?>">                                                               
                                                            <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    <?php endif; ?>

                                <?php endif; ?>
                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
        
    </ul>





</li><?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/backEnd/menu/parent_sub_menu.blade.php ENDPATH**/ ?>