<link rel="stylesheet" href="<?php echo e(asset('public\backEnd\vendors\editor\summernote-bs4.css')); ?>">
<link rel="stylesheet" href="<?php echo e(asset('Modules\AiContent\Resources\assets\css\ai_content.css')); ?>">
<?php
    $datas['title'] = 'AI Content Settings';
    $datas['ai_models'] = Modules\AiContent\Entities\AiModels::OPEN_AI_MODELS;
    $datas['ai_tones'] = Modules\AiContent\Entities\AiModels::AI_TONES;
    $datas['ai_creativity'] = Modules\AiContent\Entities\AiModels::AI_CREATIVITY;
    $datas['languages'] = Modules\AiContent\Entities\AiModels::SUPPORTED_LANGUAGES;
    $datas['settings'] = Modules\AiContent\Entities\AiContentSetting::first();
    $datas['templates'] = Modules\AiContent\Entities\AiTemplate::get();
?>
<div class="modal fade admin-query" id="ai_text_generation_modal">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo e(__('aicontent::aicontent.ai_text_generation')); ?></h4>
                <button type="button" class="close " data-dismiss="modal">
                    <i class="ti-close "></i>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo e(route('ai-content.generate_text')); ?>" method="POST" id="text_generator_form"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label"
                                    for=""><?php echo e(__('aicontent::aicontent.language')); ?>

                                    <strong class="text-danger">*</strong>
                                </label>
                                <select class="primary_select" name="language" id="language">
                                    <option
                                        data-display="<?php echo e(__('aicontent::aicontent.select')); ?> <?php echo e(__('aicontent::aicontent.language')); ?>"
                                        value=""><?php echo e(__('aicontent::aicontent.select')); ?>

                                        <?php echo e(__('aicontent::aicontent.language')); ?> </option>
                                    <?php $__currentLoopData = $datas['languages']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e($datas['settings']->ai_default_language == $key ? 'selected' : ''); ?>>
                                            <?php echo e($language); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-danger" id="language_error_message"></small>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label"
                                    for=""><?php echo e(__('aicontent::aicontent.template')); ?>

                                    <strong class="text-danger">*</strong>
                                </label>
                                <select class="primary_select" name="template_id" id="ai_template">
                                    <option
                                        data-display="<?php echo e(__('aicontent::aicontent.select')); ?> <?php echo e(__('aicontent::aicontent.template')); ?>"
                                        value=""><?php echo e(__('aicontent::aicontent.select')); ?>

                                        <?php echo e(__('aicontent::aicontent.template')); ?>

                                    </option>
                                    <?php $__currentLoopData = $datas['templates']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $template): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($template->id); ?>"><?php echo e($template->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-danger" id="template_error_message"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row collapse mt-20" id="AdvanceOption">
                        <div class="col-lg-6">
                            <div class="primary_input mb-35">
                                <label class="primary_input_label"
                                    for=""><?php echo e(__('aicontent::aicontent.tone')); ?><strong
                                        class="text-danger">*</strong></label>
                                <select class="primary_select" name="tone" id="tone">
                                    <option
                                        data-display="<?php echo e(__('aicontent::aicontent.select')); ?> <?php echo e(__('aicontent::aicontent.tone')); ?>"
                                        value=""><?php echo e(__('aicontent::aicontent.select')); ?>

                                        <?php echo e(__('aicontent::aicontent.tone')); ?>

                                    </option>
                                    <?php $__currentLoopData = $datas['ai_tones']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ai_model): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e($datas['settings']->ai_default_tone == $key ? 'selected' : ''); ?>>
                                            <?php echo e($ai_model); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <small class="text-danger" id="tone_error_message"></small>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for=""><?php echo e(__('aicontent::aicontent.ai_creativity')); ?></label>
                                <select class="primary_select mb-25" name="creativity" id="creativity">
                                    <option
                                        data-display="<?php echo e(__('aicontent::aicontent.select')); ?> <?php echo e(__('aicontent::aicontent.creativity')); ?>"
                                        value=""><?php echo e(__('aicontent::aicontent.select')); ?>

                                        <?php echo e(__('aicontent::aicontent.creativity')); ?>

                                    </option>
                                    <?php $__currentLoopData = $datas['ai_creativity']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $ai_creativity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($key); ?>"
                                            <?php echo e($datas['settings']->ai_default_creativity == $key ? 'selected' : ''); ?>>
                                            <?php echo e($ai_creativity); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for=""><?php echo e(__('aicontent::aicontent.number_of_results')); ?></label>
                                <select class="primary_select mb-25" name="number_of_result" id="number_of_result">
                                    <?php for($i = 1; $i < 11; $i++): ?>
                                        <option><?php echo e($i); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for=""><?php echo e(__('aicontent::aicontent.max_result_length')); ?>

                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="max_result_length" id="max_result_length"
                                    placeholder="<?php echo e(__('aicontent::aicontent.max_result')); ?>"
                                    value="<?php echo e($datas['settings']->ai_max_result_length); ?>" min="10"
                                    max="<?php echo e($datas['settings']->ai_max_result_length); ?>" type="number">
                                <small class="text-danger" id="max_result_error_message"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for=""><?php echo e(__('aicontent::aicontent.keyword')); ?>

                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="keyword" id="keyword"
                                    placeholder="<?php echo e(__('aicontent::aicontent.keyword')); ?>" value=""
                                    type="text">
                                <small><?php echo e(__('aicontent::aicontent.you_can_add_multiple_keywords')); ?></small>
                                <small class="text-danger" id="keyword_error_message"></small>
                            </div>
                        </div>
                        <div class="col-lg-12 d-none" id="titleDiv">
                            <div class="primary_input mb-25">
                                <label class="primary_input_label"
                                    for=""><?php echo e(__('aicontent::aicontent.title')); ?>

                                    <strong class="text-danger">*</strong></label>
                                <input class="primary_input_field" name="title" id="title"
                                    placeholder="<?php echo e(__('aicontent::aicontent.title')); ?>" value=""
                                    type="text">
                                <small class="text-danger" id="title_error_message"></small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 d-flex justify-content-end">
                            <?php echo e(__('aicontent::aicontent.show_advanced_options')); ?>

                            <button class="primary-btn radius_30px ml-10 fix-gr-bg extraBtn"
                                id="ai_advance_section_collapse" type="button" data-toggle="collapse"
                                data-target="#AdvanceOption" aria-expanded="false" aria-controls="AdvanceOption">
                                <i class="fas fa-angle-down" id="ai_advance_icon"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center pt_15">
                        <div class="d-flex justify-content-center">
                            <button class="primary-btn semi_large2  fix-gr-bg" id="generate_content" type="button">
                                <i class="fas fa-robot" id="show_ai_icon"></i>
                                <?php echo e(__('aicontent::aicontent.generate')); ?>

                            </button>
                        </div>
                    </div>
                </form>
                <div class="row align-center">
                    <div class="col-lg-12">
                        <span class="text-danger" id="error_message">
                        </span>
                    </div>
                    <div class="col-lg-12" id="generation_result" style="display: block">
                        <div class="primary_input mb-25">
                            <div class="" id="generated_result">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('public/backEnd/vendors/editor/summernote-bs4.js')); ?>"></script>
    <script src="<?php echo e(asset('Modules/AiContent/Resources/assets/js/ai_content.js')); ?>"></script>
    <script>
        $(document).on('click', '#show_ai_text_generator', function() {
            var selected_template = $(this).data('selected_template');
            var ai_template = $('#ai_template');
            if (selected_template) {
                ai_template.val(selected_template);
                $('#ai_template').niceSelect('update');
            }
            $("#ai_text_generation_modal").modal('show');
        });

        $(document).on('change', '#ai_template', function(e) {
            console.log($(this).val());
            let templateId = $(this).val();
            if (templateId == 1 || templateId == 11) {
                $('#titleDiv').addClass('d-none');
            } else {
                $('#titleDiv').removeClass('d-none');
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH /home/joe/Videos/Code/kuzza/kuzza/Modules/AiContent/Resources/views/content_generator_modal.blade.php ENDPATH**/ ?>