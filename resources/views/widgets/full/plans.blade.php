@if ($plans->count())
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($plans as $plan)
            <div class="bg-white shadow rounded-2xl p-4 flex flex-col justify-between">
                <div>
                    <h3 class="text-xl font-semibold mb-1">{{ $plan->name }}</h3>
                    <p class="text-lg font-bold text-blue-600">${{ number_format($plan->price, 2) }}/month</p>
                    <div class="text-sm text-gray-600 mt-2 prose max-w-none ck-content-output">
                        {!! $plan->description !!}
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <form action="{{ route('subscribe.plan', $plan->id) }}" method="POST">
                        @csrf
                        <a href="javascript:void(0)" onclick="openPlanShare({{ $plan->id }})"
                            class="bg-add text-white px-4 py-2 rounded hover:bg-add/80 text-sm">Share</a>
                    </form>
                    <a href="{{ url('/Plans/' . $plan->id . '/clients') }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-orange-700 text-sm">
                        Clients
                    </a>


                    <div class="flex gap-2">
                        <a href="javascript:void(0)" onclick="editPlan({{ $plan->id }})"
                            class="bg-edit text-white px-4 py-2 rounded hover:bg-edit/80 text-sm">
                            Edit
                        </a>

                        <form action="{{ route('plans.destroy', $plan->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to delete this plan?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 text-sm">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <p class="text-gray-600">No plans created yet.</p>
@endif
