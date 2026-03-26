<?php
    $routes = subModuleRoute($child);
    $paid_modules = ['Zoom','University','Gmeet','QRCodeAttendance','BBB','ParentRegistration','InAppLiveClass','AiContent','Lms','Certificate','Jitsi','WhatsappSupport','InfixBiometrics'];
    $default_theme  = ['course-heading-update','admin-home-page','custom-links','social-media','From Download','class-exam-routine-page','course-details-heading','news-heading-update','exam-result-page','contact-page','about-page','conpactPage'];
    $edulia_theme = ['home-slider','admin-home-page','pagebuilder','expert-teacher','photo-gallery','video-gallery','front-result','front-class-routine','front-exam-routine','front-academic-calendar', 'class-exam-routine-page'];
    $active_theme = activeTheme();
    $new_fees = ['fees.fees-group','fees.due-fees','fees.fees-type','fees.fine-report','fees','fees.fees-invoice-list','fees.payment-report','fees-invoice-bulk-print','fees.bank-payment','fees.balance-report','fees-invoice-bulk-print-settings','fees_forward','fees.waiver-report'];
    $old_fees = ['fees_statement','balance_fees_report','transaction_report','fine-report','fees-bulk-print', 'fees_group', 'fees_type','fees-master','fees_discount','collect_fees','search_fees_payment','search_fees_due','fees_forward','bank-payment-slip'];      
    $sass_general_setting = ['school-general-settings','saas.custom-domain','administrator-notice'];
    $sass_school_disable = ['update-system','backup-settings','api/permission','cron-job']; 
?>
<li class="<?php echo e(spn_active_link($routes, "mm-active")); ?> <?php echo e($menu->route); ?>">
    <a href="javascript:void(0)" class="has-arrow" aria-expanded="false">
        <div class="nav_icon_small">
            <span class="<?php echo e($child->icon); ?>"></span>
        </div>
        <div class="nav_title">
            <span>  <?php echo e(!empty($child->lang_name) ? __($child->lang_name):$child->name); ?>   </span>
            <?php if(!empty($child->module) && in_array($child->module, $paid_modules) && config('app.app_sync') == true): ?>
              <span class="demo_addons">Addon</span>
            <?php endif; ?>
        </div>
    </a>
    <ul class="mm-collapse">  
        <?php $__currentLoopData = $child->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $third): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>                  
            <?php if(userPermission($third->route) ): ?>
                <?php 
                    $disable_routes = ['class_optional','academic-year']
                ?>
                <?php if(in_array($third->route, $disable_routes)): ?>
                    <?php if(!moduleStatusCheck("University")): ?>
                        <li>
                            <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?>">
                                <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?> 
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                
                    
                                    <?php if(in_array($third->route, $sass_general_setting )): ?>
                                        <?php if(moduleStatusCheck("Saas")): ?>
                                            <li>
                                                <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> ">
                                                    <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php else: ?>   
                                    <?php if($third->route == 'manage-adons'): ?>
                                        <?php if(!moduleStatusCheck("Saas")): ?>
                                            <li>
                                                <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> "> 
                                                    <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php else: ?>   
                                    
                                    <?php if($third->route == 'online_exam'): ?>
                                        <?php if(!moduleStatusCheck("Saas")): ?>
                                            <li>
                                                <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> "> 
                                                    <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php else: ?>   
                                       <?php if(in_array($third->route, [ 'view-teacher-lessonPlan-overview','view-teacher-lessonPlan','teacher_class_routine_report'] )): ?>
                                                <?php if(isTeacher()): ?>
                                                    <li>
                                                        <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> "> 
                                                            <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                        </a>
                                                    </li>
                                                <?php endif; ?>
                                        <?php else: ?> 
                                            <?php if(in_array($third->route,$edulia_theme)): ?>
                                                <?php if($active_theme == 'edulia'): ?>
                                                    <li>
                                                        <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> "> 
                                                            <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                        </a>
                                                    </li> 
                                                <?php endif; ?>
                                            <?php else: ?>   
                                                <?php if(in_array($third->route,$default_theme)): ?>
                                                    <?php if($active_theme == 'default'): ?>
                                                        <li >
                                                            <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> "> 
                                                                <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                            </a>
                                                        </li> 
                                                    <?php endif; ?>
                                                <?php else: ?>   
                                                    <?php if(in_array($third->route,$new_fees) || in_array($third->route,$old_fees)): ?>                                                        
                                                       <?php if(in_array($third->route,$new_fees)  && generalSetting()->fees_status  == 1 ): ?>
                                                            <li class="">
                                                                <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> " > 
                                                                    <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?> 
                                                                </a>
                                                            </li>
                                                       <?php endif; ?>
                                                       
                                                       <?php if(in_array($third->route,$old_fees)  && generalSetting()->fees_status  == 0 ): ?>
                                                            <li class="">
                                                                <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> " > 
                                                                    <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                                </a>
                                                            </li>
                                                       <?php endif; ?>
                                                    <?php else: ?>  
                                                    
                                                        <?php if(in_array($third->route, $sass_school_disable)): ?>
                                                             <?php if(!moduleStatusCheck("Saas")): ?>
                                                                <li>
                                                                    <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> ">
                                                                        <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

                                                                    </a>
                                                                </li>
                                                            <?php endif; ?>
                                                        <?php else: ?>  
                                                        <li>
                                                            <a href="<?php echo e(validRouteUrl($third->route)); ?>" class="<?php echo e(spn_active_link($third->route)); ?> "> 
                                                                <?php echo e(!empty($third->lang_name) ? __($third->lang_name):$third->name); ?>

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
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <li class="main">
            <a href="<?php echo e(route('items.assign')); ?>">
                <div class="nav_icon_small">
                    <span class="fa fa-gift"></span>
                </div>
                <div class="nav_title">
                    <span>Assign Items</span>
                </div>
            </a>
        </li>
    </ul>
</li><?php /**PATH /media/supreme/627ADCCA7ADC9BDB/Users/Ayiik/Documents/Code/MyBidhaa/School Management/resources/views/backEnd/menu/staff_sub_menu.blade.php ENDPATH**/ ?>