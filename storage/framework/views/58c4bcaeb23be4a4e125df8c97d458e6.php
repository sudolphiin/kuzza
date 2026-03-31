<?php
    $agent = new \Jenssegers\Agent\Agent();
    $agents = Modules\WhatsappSupport\Entities\Agents::/* take(5)-> */get();
    $ws_setting = Modules\WhatsappSupport\Entities\Settings::first();
?>
<?php if($ws_setting->showing_page == 'all'): ?>
    <?php if(
        ($ws_setting->availability == 'mobile' && $agent->isMobile()) ||
            ($ws_setting->availability == 'desktop' && $agent->isDesktop()) ||
            $ws_setting->availability == 'both'): ?>
        <?php if($ws_setting->disable_for_admin_panel != 1): ?>
            <div class="whatsApp_icon" style="background-color: <?php echo e($ws_setting->color); ?>">
                <i class="fab fa-whatsapp"></i>
            </div>
            <div class="whats_app_popup whatsApp_popup_position <?php echo e($ws_setting->open_popup ? 'active' : ''); ?>"
                id="whatsapp_support_container">
                <span class="whats_app_popup_close d-flex align-items-center justify-content-center" style="background-color: <?php echo e($ws_setting->color); ?>">
                    <i class="fas fa-times"></i>
                </span>
                <div class="whats_app_popup_body">
                    <?php if($ws_setting->layout == 2): ?>
                        <div class="whats_app_popup_thumb">
                            <?php if($ws_setting->bubble_logo == null): ?>
                                <img src="<?php echo e(asset('public/whatsapp-support/demo-avatar.jpg')); ?>"
                                    alt="">
                            <?php else: ?>
                                <img src="<?php echo e(asset($ws_setting->bubble_logo)); ?>" alt="">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="whats_app_popup_msgs">
                        <div class="whats_app_popup_head" style="background-color: <?php echo e($ws_setting->color); ?>">
                            <div class="whats_app_popup_thumb">
                                <?php if($ws_setting->layout == 1): ?>
                                    
                                        <?php if($ws_setting->bubble_logo == null): ?>
                                            <img src="<?php echo e(asset('public/whatsapp-support/demo-avatar.jpg')); ?>"
                                                alt="">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset($ws_setting->bubble_logo)); ?>" alt="">
                                        <?php endif; ?>
                                    
                                <?php endif; ?>
                            </div>
                            <p class="intro_text"><?php echo e($ws_setting->intro_text); ?></p>
                        </div>
                        <div class="whats_app_popup_text">
                            <img src="<?php echo e(asset('public/whatsapp-support/hand.svg')); ?>" alt="">
                            <p> <?php echo e($ws_setting->welcome_message); ?></p>
                        </div>
                        <?php if($ws_setting->agent_type != 'single'): ?>
                            <div class="whats_app_members">
                                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($agent_list->isAvailable() || (!$agent_list->isAvailable() && $ws_setting->show_unavailable_agent)): ?>
                                        <form action="<?php echo e(route('whatsapp-support.message.send')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="agent_number"
                                                value="<?php echo e($agent_list->number); ?>">
                                            <input type="hidden" name="browser" value="<?php echo e($agent->browser()); ?>">
                                            <input type="hidden" name="os" value="<?php echo e($agent->platform()); ?>">
                                            <input type="hidden" name="device_type"
                                                value="<?php echo e($agent->isMobile() ? 'Mobile' : 'Desktop'); ?>">
                                            <div class="single_group_member">
                                                <div class="single_group_member_inner">
                                                    <div class="thumb">
                                                        <?php if(is_null($agent_list->avatar)): ?>
                                                            <img src="<?php echo e(asset('public/whatsapp-support/demo-avatar.jpg')); ?>"
                                                                alt="">
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset($agent_list->avatar)); ?>" alt="">
                                                        <?php endif; ?>
                                                        <?php if($agent_list->isAvailable()): ?>
                                                            <span class="active_badge"></span>
                                                        <?php else: ?>
                                                            <span class="inactive_badge"></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="group_member_meta">
                                                        <h4 class="font_16"><?php echo e(ucfirst($agent_list->name)); ?></h4>
                                                        <span
                                                            class="mb-1 designation_color"><?php echo e(ucfirst($agent_list->designation)); ?></span>
                                                        <?php if($agent_list->isAvailable()): ?>
                                                            <p>Available</p>
                                                        <?php else: ?>
                                                            <p>Unavailable</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="whats_app_popup_input">
                                                    <div class="input-group primary_input_coupon align-items-center">
                                                        <input type="text" name="message" class="primary_input_field"
                                                            placeholder="Type message..."
                                                            aria-label="Recipient's username"
                                                            aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <button class="btn" type="submit"> <svg
                                                                    class="wws-popup__send-btn" version="1.1"
                                                                    id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                                                    y="0px" viewBox="0 0 40 40"
                                                                    style="enable-background:new 0 0 40 40;"
                                                                    xml:space="preserve">
                                                                    <style type="text/css">
                                                                        .wws-lau00001 {
                                                                            fill: <?php echo e($ws_setting->color); ?>80;
                                                                        }

                                                                        .wws-lau00002 {
                                                                            fill: <?php echo e($ws_setting->color); ?>;
                                                                        }
                                                                    </style>
                                                                    <path id="path0_fill" class="wws-lau00001"
                                                                        d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
                                                                    <path id="path0_fill_1_" class="wws-lau00002"
                                                                        d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($ws_setting->isSingle()): ?>
                    <form action="<?php echo e(route('whatsapp-support.message.send')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="browser" value="<?php echo e($agent->browser()); ?>">
                        <input type="hidden" name="os" value="<?php echo e($agent->platform()); ?>">
                        <input type="hidden" name="device_type"
                            value="<?php echo e($agent->isMobile() ? 'Mobile' : 'Desktop'); ?>">
                        <div class="whats_app_popup_input">
                            <div class="input-group primary_input_coupon align-items-center">
                                <input type="text" name="message" class="primary_input_field"
                                    placeholder="Type message...">
                                <div class="input-group-append">
                                    <button class="btn " type="submit">
                                        <svg class="wws-popup__send-btn" version="1.1" id="Layer_1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;"
                                            xml:space="preserve">
                                            <style type="text/css">
                                                .wws-lau00001 {
                                                    fill: <?php echo e($ws_setting->color); ?>80;
                                                }

                                                .wws-lau00002 {
                                                    fill: <?php echo e($ws_setting->color); ?>;
                                                }
                                            </style>
                                            <path id="path0_fill" class="wws-lau00001"
                                                d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
                                            <path id="path0_fill_1_" class="wws-lau00002"
                                                d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php elseif(url()->current() == $ws_setting->homepage_url): ?>
    <?php if(
        ($ws_setting->availability == 'mobile' && $agent->isMobile()) ||
            ($ws_setting->availability == 'desktop' && $agent->isDesktop()) ||
            $ws_setting->availability == 'both'): ?>
        <?php if($ws_setting->disable_for_admin_panel != 1): ?>
            <div class="whatsApp_icon" style="background-color: <?php echo e($ws_setting->color); ?>">
                <i class="fab fa-whatsapp"></i>
            </div>

            <div class="whats_app_popup whatsApp_popup_position <?php echo e($ws_setting->open_popup ? 'active' : ''); ?>"
                id="whatsapp_support_container">
                <span class="whats_app_popup_close" style="background-color: <?php echo e($ws_setting->color); ?>">
                    <i class="fas fa-times"></i>
                </span>
                <div class="whats_app_popup_body">
                    <?php if($ws_setting->layout == 2): ?>
                        <div class="whats_app_popup_thumb">
                            <?php if($ws_setting->bubble_logo == null): ?>
                                <img src="<?php echo e(asset('public/whatsapp-support/demo-avatar.jpg')); ?>"
                                    alt="">
                            <?php else: ?>
                                <img src="<?php echo e(asset($ws_setting->bubble_logo)); ?>" alt="">
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="whats_app_popup_msgs">
                        <div class="whats_app_popup_head" style="background-color: <?php echo e($ws_setting->color); ?>">
                            <div class="whats_app_popup_thumb mb_15">
                                <?php if($ws_setting->layout == 1): ?>
                                    <div class="whats_app_popup_thumb mb_15">
                                        <?php if($ws_setting->bubble_logo == null): ?>
                                            <img src="<?php echo e(asset('public/whatsapp-support/demo-avatar.jpg')); ?>"
                                                alt="">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset($ws_setting->bubble_logo)); ?>" alt="">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <p class="intro_text"><?php echo e($ws_setting->intro_text); ?></p>
                        </div>
                        <div class="whats_app_popup_text">
                            <img src="<?php echo e(asset('public/whatsapp-support/hand.svg')); ?>" alt="">
                            <p> <?php echo e($ws_setting->welcome_message); ?></p>
                        </div>
                        <?php if($ws_setting->agent_type != 'single'): ?>
                            <div class="whats_app_members">
                                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent_list): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php if($agent_list->isAvailable() || (!$agent_list->isAvailable() && $ws_setting->show_unavailable_agent)): ?>
                                        <form action="<?php echo e(route('whatsapp-support.message.send')); ?>" method="post">
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="agent_number"
                                                value="<?php echo e($agent_list->number); ?>">
                                            <input type="hidden" name="browser" value="<?php echo e($agent->browser()); ?>">
                                            <input type="hidden" name="os" value="<?php echo e($agent->platform()); ?>">
                                            <input type="hidden" name="device_type"
                                                value="<?php echo e($agent->isMobile() ? 'Mobile' : 'Desktop'); ?>">
                                            <div class="single_group_member">
                                                <div class="single_group_member_inner">
                                                    <div class="thumb">
                                                        <?php if(is_null($agent_list->avatar)): ?>
                                                            <img src="<?php echo e(asset('public/whatsapp-support/demo-avatar.jpg')); ?>"
                                                                alt="">
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset($agent_list->avatar)); ?>"
                                                                alt="">
                                                        <?php endif; ?>

                                                        <?php if($agent_list->isAvailable()): ?>
                                                            <span class="active_badge"></span>
                                                        <?php else: ?>
                                                            <span class="inactive_badge"></span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="group_member_meta">
                                                        <h4 class="font_16"><?php echo e(ucfirst($agent_list->name)); ?></h4>
                                                        <span
                                                            class="mb-1 designation_color"><?php echo e(ucfirst($agent_list->designation)); ?></span>
                                                        <?php if($agent_list->isAvailable()): ?>
                                                            <p>Available</p>
                                                        <?php else: ?>
                                                            <p>Unavailable</p>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="whats_app_popup_input">
                                                    <div class="input-group primary_input_coupon align-items-center">
                                                        <input type="text" name="message"
                                                            class="primary_input_field" placeholder="Type message..."
                                                            aria-label="Recipient's username"
                                                            aria-describedby="basic-addon2">
                                                        <div class="input-group-append">
                                                            <button class="btn" type="submit"> <svg
                                                                    class="wws-popup__send-btn" version="1.1"
                                                                    id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                                                    y="0px" viewBox="0 0 40 40"
                                                                    style="enable-background:new 0 0 40 40;"
                                                                    xml:space="preserve">
                                                                    <style type="text/css">
                                                                        .wws-lau00001 {
                                                                            fill: <?php echo e($ws_setting->color); ?>80;
                                                                        }

                                                                        .wws-lau00002 {
                                                                            fill: <?php echo e($ws_setting->color); ?>;
                                                                        }
                                                                    </style>
                                                                    <path id="path0_fill" class="wws-lau00001"
                                                                        d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
                                                                    <path id="path0_fill_1_" class="wws-lau00002"
                                                                        d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if($ws_setting->isSingle()): ?>
                    <form action="<?php echo e(route('whatsapp-support.message.send')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="browser" value="<?php echo e($agent->browser()); ?>">
                        <input type="hidden" name="os" value="<?php echo e($agent->platform()); ?>">
                        <input type="hidden" name="device_type"
                            value="<?php echo e($agent->isMobile() ? 'Mobile' : 'Desktop'); ?>">
                        <div class="whats_app_popup_input">
                            <div class="input-group primary_input_coupon align-items-center">
                                <input type="text" name="message" class="primary_input_field"
                                    placeholder="Type message...">
                                <div class="input-group-append">
                                    <button class="btn " type="submit">
                                        <svg class="wws-popup__send-btn" version="1.1" id="Layer_1"
                                            xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                            viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;"
                                            xml:space="preserve">
                                            <style type="text/css">
                                                .wws-lau00001 {
                                                    fill: <?php echo e($ws_setting->color); ?>80;
                                                }

                                                .wws-lau00002 {
                                                    fill: <?php echo e($ws_setting->color); ?>;
                                                }
                                            </style>
                                            <path id="path0_fill" class="wws-lau00001"
                                                d="M38.9,19.8H7.5L2,39L38.9,19.8z"></path>
                                            <path id="path0_fill_1_" class="wws-lau00002"
                                                d="M38.9,19.8H7.5L2,0.7L38.9,19.8z"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endif; ?>
<?php /**PATH /home/joe/Videos/Code/kuzza/kuzza/Modules/WhatsappSupport/Resources/views/partials/_popup.blade.php ENDPATH**/ ?>