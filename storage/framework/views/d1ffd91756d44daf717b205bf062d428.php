<?php
    $menus = getMenus("parent");    
?>
<?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $menu): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<span class="menu_seperator" id="seperator_<?php echo e(\Illuminate\Support\str::lower($menu->name)); ?>"  data-section="<?php echo e($menu->route); ?>"><?php echo e(__($menu->lang_name)); ?></span>
    <?php if($menu->childs->count() > 0): ?>      
        <?php $__currentLoopData = $menu->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($child->childs->count() > 0): ?>
                <?php if(userPermission($child->route)): ?>
                    <?php if(!empty($child->module)): ?>
                        <?php if(moduleStatusCheck($child->module)): ?>
                            <?php if ($__env->exists('backEnd.menu.parent_sub_menu',['menu' => $menu,'child' => $child])) echo $__env->make('backEnd.menu.parent_sub_menu',['menu' => $menu,'child' => $child], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                        <?php endif; ?>
                    <?php else: ?>     
                            <?php if ($__env->exists('backEnd.menu.parent_sub_menu',['menu' => $menu,'child' => $child])) echo $__env->make('backEnd.menu.parent_sub_menu',['menu' => $menu,'child' => $child], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

                    <?php endif; ?>                   
                <?php endif; ?>
            <?php else: ?>  
                <?php if(userPermission($child->route)): ?>
                     <?php if($child->route == 'my_children'): ?>
                     
                        <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                     <?php else: ?>   
                         <?php if($child->route == 'fees.student-fees-list'): ?>
                            
                            <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                         <?php else: ?>   
                            <?php if($child->route == 'parent_class_routine'): ?>
                            
                                <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                            <?php else: ?>   
                                <?php if($child->route == 'parent_homework'): ?>
                                    
                                    <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                                <?php else: ?>   
                                    <?php if($child->route == 'parent_attendance'): ?>
                                        
                                        <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                                    <?php else: ?>   
                                        <?php if($child->route == 'parent_subjects'): ?>
                                            
                                            <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                                        <?php else: ?>   
                                            <?php if($child->route == 'parent_teacher_list'): ?>
                                                
                                                <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                                            <?php else: ?>   
                                                <?php if($child->route == 'parent_transport'): ?>
                                                    
                                                    <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                                                <?php else: ?>   
                                                    <?php if($child->route == 'parent_dormitory_list'): ?>
                                                        
                                                        <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                                                    <?php else: ?>   
                                                        <?php if($child->route == 'fees.student-fees-list-parent'): ?>
                                                            
                                                            <?php if ($__env->exists('backEnd.menu.parent_children_menu',compact('children','child'))) echo $__env->make('backEnd.menu.parent_children_menu',compact('children','child'), array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 
                                                        <?php else: ?>   
                                                            <li class="<?php echo e(spn_active_link([$child->route], "mm-active")); ?> <?php echo e($child->route); ?> main">
                                                                <a href="<?php echo e(validRouteUrl($child->route)); ?>">
                                                                    <div class="nav_icon_small">
                                                                        <span class="<?php echo e($child->icon); ?>"></span>
                                                                    </div>
                                                                    <div class="nav_title">
                                                                        <span><?php echo e(!empty($child->lang_name) ?  __($child->lang_name):$child->name); ?> </span>
                                                                    </div>
                                                                </a>
                                                            </li> 
                                                        <?php endif; ?> 
                                                    <?php endif; ?> 
                                                <?php endif; ?> 
                                            <?php endif; ?> 
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php endif; ?> 
                            <?php endif; ?>
                        <?php endif; ?>
                     <?php endif; ?>
                   
                <?php endif; ?>               
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/backEnd/menu/parent.blade.php ENDPATH**/ ?>