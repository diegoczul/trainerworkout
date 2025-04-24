@extends('layouts.trainee')


@section('content')
    <section id="content" class="contenttoptouch clearfix">
        <div class="bannerholder">
            <div class="wrapper clearfix">
                <div class="widget">
                    <div class="max-w-6xl mx-auto px-4">
                        <h1 class="text-2xl font-bold mb-6">My Subscriptions</h1>

                        @if ($subscriptions->count())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($subscriptions as $sub)
                                    <div
                                        class="bg-white border border-gray-200 shadow rounded-xl p-5 flex flex-col justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-gray-800">
                                                {{ $sub->trainer_first_name }} {{ $sub->trainer_last_name }}
                                            </h2>
                                            <p class="text-sm text-gray-500 mb-2">Trainer</p>

                                            <dl class="space-y-1 text-sm text-gray-700">
                                                <div class="flex justify-between">
                                                    <dt class="font-medium">Plan:</dt>
                                                    <dd>{{ $sub->plan_name }}</dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="font-medium">Price:</dt>
                                                    <dd>${{ number_format($sub->price, 2) }}</dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="font-medium">Start Date:</dt>
                                                    <dd>{{ \Carbon\Carbon::parse($sub->created_at)->format('M d, Y') }}</dd>
                                                </div>
                                            </dl>

                                            <span
                                                class="inline-block mt-3 px-3 py-1 text-xs font-medium rounded-full 
                                {{ $sub->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                        </div>

                                        <div class="mt-4">
                                            @if ($sub->status !== 'cancelled')
                                                <form action="{{ route('plans.cancel.self', $sub->plan_id) }}"
                                                    method="POST" onsubmit="return confirm('Cancel this subscription?');">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 px-4 rounded">
                                                        Cancel Plan
                                                    </button>
                                                </form>
                                            @else
                                                <p class="text-center text-sm text-gray-400">Cancelled</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white shadow rounded-xl p-6 text-center text-gray-600 border border-gray-200">
                                You don’t have any subscriptions yet.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
