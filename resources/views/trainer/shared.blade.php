@extends('layouts.visitor')

@section('content')
    <section id="content" class="contenttoptouch clearfix">
        <div class="bannerholder">
            <div class="wrapper clearfix">
                <div class="widget">
                    <div class="workouts">
                        <div class="workoutHeader">
                            <div class="workoutHeader_description">
                                <h1>{{ $plan->name }}</h1>
                                <p class="text-blue-600 font-bold text-lg">
                                    ${{ number_format($plan->price, 2) }}/month
                                </p>
                            </div>
                        </div>

                        <div class="bg-white p-4 mt-4 rounded shadow text-sm ck-content-output prose max-w-none">
                            {!! $plan->description !!}
                        </div>

                        <div class="text-gray-600 mt-4 text-sm">
                            Shared with you by <strong>{{ $trainer->firstName }} {{ $trainer->lastName }}</strong>
                        </div>

                        <div class="mt-6">
                            <form action="{{ route('subscribe.plan', $plan->id) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="bg-add text-white px-5 py-2 rounded hover:bg-add/80 text-sm font-medium">
                                    Subscribe to This Plan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
