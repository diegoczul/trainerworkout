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

                        <div id="createPlanForm" style="display:none;"
                            class="shadow-xl rounded-2xl planOverlay overlayKillParent p-5 mb-10">
                            <div class="addexercise" style="width: 100%; text-align: -webkit-auto !important;">
                                <h1>{{ Lang::get('content.CreateAPlan') }}</h1>
                                <p class="required">*{{ Lang::get('content.required') }}</p>
                                {{ Form::open(['url' => '/plans/save', 'method' => 'POST', 'id' => 'plan_form']) }}

                                <div class="form-group">
                                    <label for="name">*{{ Lang::get('content.PlanName') }}</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="form-group mb-4">
                                    <label for="description">{{ Lang::get('content.PlanDescription') }}</label>
                                    <textarea name="description" class="form-control" id="editor"></textarea>

                                    {{-- <textarea name="description" class="form-control rich-text" rows="4"></textarea> --}}

                                </div>

                                <div class="form-group mb-4">
                                    <label for="price">*{{ Lang::get('content.MonthlyPriceUSD') }}</label>
                                    <input type="number" name="price" class="form-control" min="1" required>
                                    <small>{{ Lang::get('content.PriceNote') }}</small>
                                </div>

                                <div class="text-center">
                                    <button type="submit"
                                        class="btn btn-primary reBindajaxSave">{{ Lang::get('content.CreatePlan') }}</button>
                                </div>
                                {{ Form::close() }}
                            </div>
                        </div>



                        <div id="w_plans">
                            <!-- /widgets/base/workouts.blade.php -->
                        </div>
                        <div class="bg-white shadow border border-gray-200 rounded-xl p-6 mb-8 mt-10">
                            <h2 class="text-xl font-bold text-gray-800 mb-4">Earnings & Payouts</h2>


                            <dl class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm text-gray-700 mb-4">
                                <div>
                                    <dt class="font-medium">Total Earned</dt>
                                    <dd class="text-lg font-bold text-green-600">
                                        ${{ number_format($earnings->total_earned, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Total Paid Out</dt>
                                    <dd class="text-lg font-bold text-blue-600">
                                        ${{ number_format($earnings->total_paid, 2) }}</dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Frozen</dt>
                                    <dd class="text-lg font-bold text-yellow-600">
                                        ${{ number_format($earnings->frozen, 2) }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="font-medium">Available Balance</dt>
                                    <dd class="text-lg font-bold text-orange-600">
                                        ${{ number_format($earnings->available, 2) }}</dd>
                                </div>
                            </dl>
                            @if (count($earningsRows))
                                <h3 class="text-md font-semibold text-gray-800 mt-6 mb-2">Earnings History</h3>
                                <div class="overflow-auto">
                                    <table class="min-w-full text-sm text-left text-gray-700 border rounded">
                                        <thead class="bg-gray-800 text-xs uppercase">
                                            <tr>
                                                <th class="px-4 py-2">Amount</th>
                                                <th class="px-4 py-2">Status</th>
                                                <th class="px-4 py-2">Client</th>
                                                <th class="px-4 py-2">Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($earningsRows as $e)
                                                <tr class="border-t">
                                                    <td class="px-4 py-2">${{ number_format($e->amount, 2) }}</td>
                                                    <td class="px-4 py-2">
                                                        <span
                                                            class="inline-block px-2 py-1 text-xs rounded 
                                                            {{ $e->status === 'available' ? 'bg-green-100 text-green-800' : ($e->status === 'frozen' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-800') }}">
                                                            {{ ucfirst($e->status) }}
                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        {{ $e->first_name }} {{ $e->last_name }}<br>
                                                        <small class="text-xs text-gray-500">{{ $e->email }}</small>
                                                    </td>
                                                    <td class="px-4 py-2">
                                                        {{ \Carbon\Carbon::parse($e->created_at)->format('M d, Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            @endif


                            @if ($earnings->available > 0)
                                <form class="mt-2" action="{{ route('trainer.request-payout') }}" method="POST"
                                    onsubmit="return confirm('Confirm payout request?');">
                                    @csrf
                                    <input type="hidden" name="amount" value="{{ $earnings->balance }}">
                                    <button type="submit"
                                        class="bg-blue-600 hover:bg-orange-700 text-white text-sm font-medium px-4 py-2 rounded">
                                        Request Payout
                                    </button>
                                </form>
                            @else
                                <p class="text-gray-500 text-sm">No available balance to request a payout.</p>
                            @endif

                            @if (count($payouts))
                                <h3 class="text-md font-semibold text-gray-800 mt-6 mb-2">Payout History</h3>
                                <div class="overflow-auto">
                                    <table class="min-w-full text-sm text-left text-gray-700 border rounded">
                                        <thead class="bg-gray-800 text-white text-xs uppercase">
                                            <tr>
                                                <th class="px-4 py-2">Date</th>
                                                <th class="px-4 py-2">Amount</th>
                                                <th class="px-4 py-2">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($payouts as $index => $payout)
                                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-50' }} border-t">
                                                    <td class="px-4 py-2">
                                                        {{ \Carbon\Carbon::parse($payout->created_at)->format('M d, Y') }}
                                                    </td>
                                                    <td class="px-4 py-2">${{ number_format($payout->amount, 2) }}</td>
                                                    <td class="px-4 py-2">
                                                        <span
                                                            class="text-xs px-2 py-0.5 rounded 
                                {{ $payout->status === 'completed' ? 'bg-green-100 text-green-700' : ($payout->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-700') }}">
                                                            {{ ucfirst($payout->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif

                        </div>
                        <p class="text-sm text-gray-600">
                            As a trainer, you earn revenue whenever a client subscribes to one of your plans. Each
                            successful payment is tracked and recorded in your earnings dashboard. We retain a 10% platform
                            fee from each client payment. This helps cover payment processing,
                            support, and infrastructure costs.
                            <br /> <br />
                            To ensure payment integrity, all new earnings are initially marked as “frozen.” This buffer
                            accounts for potential issues such as chargebacks or cancellations. After a 7-day clearance
                            period, frozen funds automatically become “available” and can be
                            requested as a payout through your dashboard.
                            <br /> <br />
                            Payouts are processed within 2 weeks of your request. Payments are sent via PayPal or wire
                            transfer depending on your registered payout method.
                        </p>

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
        let ckEditorInstance; // Declare at the top

        document.addEventListener('DOMContentLoaded', function() {
            const el = document.querySelector('#editor');
            if (el) {
                ClassicEditor.create(el, {
                    toolbar: ['bold', 'numberedList', 'bulletedList', '|', 'undo', 'redo']
                }).then(editor => {
                    ckEditorInstance = editor; // ✅ Save to global variable

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
            if (ckEditorInstance) {
                $('textarea[name="description"]').val(ckEditorInstance.getData());
            }

            if (ckEditorInstance) {
                const content = ckEditorInstance.getData();
                alert("CKEditor content:\n\n" + content); // ✅ Debug alert
                $('textarea[name="description"]').val(content);
            } else {
                alert("❌ ckEditorInstance is undefined or not ready");
            }

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
