@extends('layouts.trainer')

@section('content')
    <section id="content" class="contenttoptouch clearfix">
        <div class="bannerholder">
            <div class="wrapper clearfix">
                <div class="widget">
                    <section class="py-8 px-4 max-w-6xl mx-auto">
                        <div class="mb-8">

                            <h1 class="text-2xl font-bold text-white-800">Clients for: <span
                                    class="text-blue-600">{{ $plan->name }}</span>
                            </h1>
                            <p class="text-gray-600 mt-1">Below are all users subscribed to this plan.</p>
                        </div>
                        <form method="GET" class="mb-6">
                            <div class="flex gap-4 items-center">
                                <label class="text-sm font-medium text-gray-700">Filter by status:</label>
                                <select name="status" onchange="this.form.submit()"
                                    class="border border-gray-300 text-sm rounded px-3 py-2 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500">
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>
                                        Cancelled</option>
                                    <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </div>
                        </form>
                        @if ($clients->count())
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach ($clients as $client)
                                    <div
                                        class="bg-white border border-gray-200 shadow rounded-xl p-5 flex flex-col justify-between">
                                        <div>
                                            <h2 class="text-lg font-semibold text-gray-800 mb-1">
                                                {{ $client->firstName }} {{ $client->lastName }}
                                            </h2>
                                            <p class="text-sm text-gray-500">{{ $client->email }}</p>

                                            <dl class="mt-4 space-y-1 text-sm text-gray-700">
                                                <div class="flex justify-between">
                                                    <dt class="font-medium">Start Date:</dt>
                                                    <dd>{{ \Carbon\Carbon::parse($client->created_at)->format('M d, Y') }}
                                                    </dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="font-medium">Renewals:</dt>
                                                    <dd>{{ $client->renewals ?? 0 }}</dd>
                                                </div>
                                                <div class="flex justify-between">
                                                    <dt class="font-medium">Price:</dt>
                                                    <dd>${{ number_format($client->price, 2) }}</dd>
                                                </div>
                                            </dl>

                                            <span
                                                class="inline-block mt-3 px-3 py-1 text-xs font-medium rounded-full 
                                                {{ $client->status === 'cancelled' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                {{ ucfirst($client->status) }}
                                            </span>
                                        </div>

                                        <div class="mt-5">
                                            @if ($client->status !== 'cancelled')
                                                <form
                                                    action="{{ route('plans.clients.cancel', [$plan->id, $client->id]) }}"
                                                    method="POST" onsubmit="return confirm('Cancel this client?');">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full bg-red-600 hover:bg-red-700 text-white text-sm py-2 px-4 rounded">
                                                        Cancel Plan
                                                    </button>
                                                </form>
                                            @else
                                                <span class="block text-center text-sm text-gray-400">
                                                    Cancelled</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-white shadow rounded-xl p-6 text-center text-gray-600 border border-gray-200">
                                No clients have subscribed to this plan yet.
                            </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </section>
@endsection
