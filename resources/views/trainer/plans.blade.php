@extends('layouts.trainer')

@section('content')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

    <section id="content" class="contenttoptouch clearfix">
        <div class="bannerholder">
            <div class="wrapper clearfix">
                <div class="widget">
                    <div class="workouts">
                        <div class="workoutHeader">
                            <div class="workoutHeader_description">
                                <h1>{{ Lang::get('content.MyPlans') }}</h1>
                                <p>{{ Lang::get('content.ManageYourPlans') }}</p>
                            </div>
                            <div class="workouts_options workouts_options_down">
                                <a href="javascript:void(0)" class="addElementButton"
                                    id="createPlan">{{ Lang::get('content.CreatePlan') }}</a>
                            </div>
                        </div>

                        <div id="createPlanForm" style="display:none;" class="planOverlay overlayKillParent">
                            <div class="addexercise" style="width: 100%; text-align: -webkit-auto !important;">
                                <h1>{{ Lang::get('content.CreateAPlan') }}</h1>
                                <p class="required">*{{ Lang::get('content.required') }}</p>
                                {{ Form::open(['url' => '/plans/save', 'method' => 'POST', 'id' => 'plan_form']) }}

                                <div class="form-group">
                                    <label for="name">*{{ Lang::get('content.PlanName') }}</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="description">{{ Lang::get('content.PlanDescription') }}</label>
                                    <textarea name="description" class="form-control" id="editor"></textarea>

                                    {{-- <textarea name="description" class="form-control rich-text" rows="4"></textarea> --}}

                                </div>

                                <div class="form-group">
                                    <label for="price">*{{ Lang::get('content.MonthlyPriceUSD') }}</label>
                                    <input type="number" name="price" class="form-control" min="1" required>
                                    <small>{{ Lang::get('content.PriceNote') }}</small>
                                </div>

                                <div class="submit">
                                    <button type="submit"
                                        class="btn btn-primary reBindajaxSave">{{ Lang::get('content.CreatePlan') }}</button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>

                        <div id="w_plans">
                            <!-- /widgets/base/workouts.blade.php -->
                        </div>
                        @include('popups.sharePlan')


                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        callWidget("w_plans").done(function() {});
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.querySelector('#editor');
            if (el) {
                ClassicEditor.create(el, {
                    toolbar: ['bold', 'numberedList', 'bulletedList', '|', 'undo', 'redo']
                }).then(editor => {
                    editor.editing.view.change(writer => {
                        const root = editor.editing.view.document.getRoot();
                        writer.setStyle('color', '#000000', root);
                        writer.setStyle('padding-left', '1.5rem', root); // ✅ spacing for bullets
                        writer.setStyle('margin', '0', root); // ✅ reset any weird margins
                    });
                }).catch(error => {
                    console.error('❌ CKEditor init failed:', error);
                });


            }
        });


        // Show/hide plan form
        $("#createPlan").click(function(e) {
            e.preventDefault();
            $("#createPlanForm").slideToggle();
        });

        // AJAX form submission (same pattern as exercises)
        $("#plan_form").on("submit", function(e) {
            e.preventDefault();
            let $form = $(this);

            $.ajax({
                url: $form.attr("action"),
                type: "POST",
                data: $form.serialize(),
                beforeSend: function() {
                    lightBoxLoadingTwSpinner();
                },
                success: function(response) {
                    closeLoadingOverlay();
                    successMessage("Plan created.");
                    $("#createPlanForm").slideUp();
                    $form.trigger("reset");
                    callWidget("w_plans"); // reload the widget list
                },
                error: function(xhr) {
                    closeLoadingOverlay();
                    errorMessage(xhr.responseText);
                }
            });
        });

        function editPlan(planId) {
            // Fetch plan details using AJAX
            $.ajax({
                url: '/plans/edit/' + planId, // Your backend endpoint to fetch plan details
                type: 'GET',
                success: function(response) {
                    if (response.error) {
                        errorMessage(response.error); // Show error if unauthorized
                    } else {
                        // Prefill the form with plan details
                        $('input[name="name"]').val(response.name);
                        $('textarea[name="description"]').val(response.description);
                        $('input[name="price"]').val(response.price);

                        // Set the form action to update the plan
                        $('#plan_form').attr('action', '/plans/update/' + response.id); // Update route

                        // Show the form for editing
                        $('#createPlanForm').slideDown();
                    }
                },
                error: function(xhr) {
                    errorMessage(xhr.responseText);
                }
            });
        }

        function openPlanShare(planId) {
            var div = $('.shareplanform');
            div.attr("data-plan", planId);

            $(".lightBox").addClass("lightBox-activated");
            $(".popup_container").addClass("popup_container-activated");
            $(".lightbox_mask").addClass("lightbox_mask-activated");
            $("body").addClass('no_scroll_overlay');
        }

        function sharePlanByEmail(el) {
            var div = el.closest('.shareplanform');
            var planId = div.attr('data-plan');
            var email = div.find('input[name=toemail]').val();
            var message = $("#personalizedTxt").val();
            var copyMe = $("#copyMe").prop('checked');

            var preLoad = showLoadWithElement(el, 40, 'center');
            $.ajax({
                url: "/plans/share",
                type: "POST",
                data: {
                    planId: planId,
                    email: email,
                    message: message,
                    copyMe: copyMe
                },
                success: function(data) {
                    parent.successMessage(data);
                    hideLoadWithElement(preLoad);
                    hidelightboxWithoutE();
                },
                error: function(jqXHR) {
                    parent.errorMessage(jqXHR.responseText);
                    hideLoadWithElement(preLoad);
                    hidelightboxWithoutE();
                }
            });
        }
    </script>
@endsection
