@extends('layouts.visitor')

@section('content')
    <section class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
            <h1 class="text-xl font-semibold text-gray-800 mb-4">Subscribe to {{ $plan->name }}</h1>

            <div class="mb-6">
                <p class="text-gray-600 text-sm">Plan Price:</p>
                <p class="text-lg font-bold text-blue-600">${{ number_format($plan->price, 2) }}/month</p>
            </div>

            {{ Form::open(['url' => route('process-plan-subscription-payment'), 'id' => 'payment-form']) }}
            <input type="hidden" name="plan_id" value="{{ $plan->id }}" />

            <div class="space-y-4">
                <div class="payFormFull">
                    <fieldset class="payForm payFormHalf">
                        <label for="firstName">First Name</label>
                        <input id="firstName" name="first_name" type="text"
                            value="{{ old('first_name', Auth::user()->firstName ?? '') }}"
                            class="payFormInput validate[required]" required>
                    </fieldset>
                    <fieldset class="payForm payFormHalf payFormRight">
                        <label for="lastName">Last Name</label>
                        <input id="lastName" name="last_name" type="text"
                            value="{{ old('last_name', Auth::user()->lastName ?? '') }}"
                            class="payFormInput validate[required]" required>
                    </fieldset>
                </div>

                <div class="payFormFull">
                    <fieldset class="payForm payFormHalf">
                        <label for="email">Email</label>
                        <input id="email" name="email" type="email"
                            value="{{ old('email', Auth::user()->email ?? '') }}" class="payFormInput validate[required]"
                            required>
                    </fieldset>
                    <fieldset class="payForm payFormHalf payFormRight">
                        <label for="phone">Phone Number</label>
                        <input id="phone" name="phone" type="text"
                            value="{{ old('phone', Auth::user()->phone ?? '') }}" class="payFormInput validate[required]"
                            required>
                    </fieldset>
                </div>

                <div>
                    <label for="biStreet" class="block text-sm font-medium text-gray-700">Street</label>
                    <input type="text" name="street" id="biStreet"
                        value="{{ old('street', Auth::user()->street ?? '') }}"
                        class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="biCity" class="block text-sm font-medium text-gray-700">City</label>
                        <input type="text" name="city" id="biCity"
                            value="{{ old('city', Auth::user()->city ?? '') }}"
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" />
                    </div>
                    <div>
                        <label for="biPostCode" class="block text-sm font-medium text-gray-700">Postal Code</label>
                        <input type="text" name="postal" id="biPostCode"
                            value="{{ old('postal', Auth::user()->postalcode ?? '') }}"
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" />
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="countrySelect" class="block text-sm font-medium text-gray-700">Country</label>
                        <select name="country" class="payFormInput Gselect validate[required]" id="countrySelect">
                            <option value="">{{ __('content.country') }}...</option>
                            @php $selectedCountry = old('country', Auth::user()->country ?? '') @endphp
                            @foreach ([
            'United States of America',
            'Canada',
            'United Kingdom',
            'Australia',
            '--------------',
            'Afghanistan',
            'Albania',
            'Algeria',
            'American Samoa',
            'Andorra',
            'Angola',
            'Anguilla',
            'Antigua & Barbuda',
            'Argentina',
            'Armenia',
            'Aruba',
            'Austria',
            'Azerbaijan',
            'Bahamas',
            'Bahrain',
            'Bangladesh',
            'Barbados',
            'Belarus',
            'Belgium',
            'Belize',
            'Benin',
            'Bermuda',
            'Bhutan',
            'Bolivia',
            'Bonaire',
            'Bosnia & Herzegovina',
            'Botswana',
            'Brazil',
            'British Indian Ocean Ter',
            'Brunei',
            'Bulgaria',
            'Burkina Faso',
            'Burundi',
            'Cambodia',
            'Cameroon',
            'Canary Islands',
            'Cape Verde',
            'Cayman Islands',
            'Central African Republic',
            'Chad',
            'Channel Islands',
            'Chile',
            'China',
            'Christmas Island',
            'Cocos Island',
            'Colombia',
            'Comoros',
            'Congo',
            'Cook Islands',
            'Costa Rica',
            'Cote D\'Ivoire',
            'Croatia',
            'Cuba',
            'Curacao',
            'Cyprus',
            'Czech Republic',
            'Denmark',
            'Djibouti',
            'Dominica',
            'Dominican Republic',
            'East Timor',
            'Ecuador',
            'Egypt',
            'El Salvador',
            'Equatorial Guinea',
            'Eritrea',
            'Estonia',
            'Ethiopia',
            'Falkland Islands',
            'Faroe Islands',
            'Fiji',
            'Finland',
            'France',
            'French Guiana',
            'French Polynesia',
            'French Southern Ter',
            'Gabon',
            'Gambia',
            'Georgia',
            'Germany',
            'Ghana',
            'Gibraltar',
            'Great Britain',
            'Greece',
            'Greenland',
            'Grenada',
            'Guadeloupe',
            'Guam',
            'Guatemala',
            'Guinea',
            'Guyana',
            'Haiti',
            'Hawaii',
            'Honduras',
            'Hong Kong',
            'Hungary',
            'Iceland',
            'India',
            'Indonesia',
            'Iran',
            'Iraq',
            'Ireland',
            'Isle of Man',
            'Israel',
            'Italy',
            'Jamaica',
            'Japan',
            'Jordan',
            'Kazakhstan',
            'Kenya',
            'Kiribati',
            'Korea North',
            'Korea South',
            'Kuwait',
            'Kyrgyzstan',
            'Laos',
            'Latvia',
            'Lebanon',
            'Lesotho',
            'Liberia',
            'Libya',
            'Liechtenstein',
            'Lithuania',
            'Luxembourg',
            'Macau',
            'Macedonia',
            'Madagascar',
            'Malaysia',
            'Malawi',
            'Maldives',
            'Mali',
            'Malta',
            'Marshall Islands',
            'Martinique',
            'Mauritania',
            'Mauritius',
            'Mayotte',
            'Mexico',
            'Midway Islands',
            'Moldova',
            'Monaco',
            'Mongolia',
            'Montserrat',
            'Morocco',
            'Mozambique',
            'Myanmar',
            'Nambia',
            'Nauru',
            'Nepal',
            'Netherland Antilles',
            'Netherlands',
            'Nevis',
            'New Caledonia',
            'New Zealand',
            'Nicaragua',
            'Niger',
            'Nigeria',
            'Niue',
            'Norfolk Island',
            'Norway',
            'Oman',
            'Pakistan',
            'Palau Island',
            'Palestine',
            'Panama',
            'Papua New Guinea',
            'Paraguay',
            'Peru',
            'Philippines',
            'Pitcairn Island',
            'Poland',
            'Portugal',
            'Puerto Rico',
            'Qatar',
            'Republic of Montenegro',
            'Republic of Serbia',
            'Reunion',
            'Romania',
            'Russia',
            'Rwanda',
            'St Barthelemy',
            'St Eustatius',
            'St Helena',
            'St Kitts-Nevis',
            'St Lucia',
            'St Maarten',
            'St Pierre & Miquelon',
            'St Vincent & Grenadines',
            'Saipan',
            'Samoa',
            'Samoa American',
            'San Marino',
            'Sao Tome & Principe',
            'Saudi Arabia',
            'Senegal',
            'Serbia',
            'Seychelles',
            'Sierra Leone',
            'Singapore',
            'Slovakia',
            'Slovenia',
            'Solomon Islands',
            'Somalia',
            'South Africa',
            'Spain',
            'Sri Lanka',
            'Sudan',
            'Suriname',
            'Swaziland',
            'Sweden',
            'Switzerland',
            'Syria',
            'Tahiti',
            'Taiwan',
            'Tajikistan',
            'Tanzania',
            'Thailand',
            'Togo',
            'Tokelau',
            'Tonga',
            'Trinidad & Tobago',
            'Tunisia',
            'Turkey',
            'Turkmenistan',
            'Turks & Caicos Is',
            'Tuvalu',
            'Uganda',
            'Ukraine',
            'United Arab Emirates',
            'Uruguay',
            'Uzbekistan',
            'Vanuatu',
            'Vatican City State',
            'Venezuela',
            'Vietnam',
            'Virgin Islands (Brit)',
            'Virgin Islands (USA)',
            'Wake Island',
            'Wallis & Futuna Is',
            'Yemen',
            'Zaire',
            'Zambia',
            'Zimbabwe',
        ] as $country)
                                <option value="{{ $country }}" {{ $selectedCountry === $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="stateSelect" class="block text-sm font-medium text-gray-700">State/Province</label>
                        <input type="text" name="province" id="stateSelect"
                            value="{{ old('province', Auth::user()->province ?? '') }}"
                            class="mt-1 block w-full rounded border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" />
                    </div>
                </div>


                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Credit Card</label>
                    <div id="payInfoContainer"
                        class="mt-4 border border-gray-300 rounded px-4 py-3 bg-white shadow-sm relative z-10"></div>
                </div>

                <div>
                    <button type="submit" id="payform_submit"
                        class="w-full bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded hover:bg-blue-700 focus:outline-none">
                        Complete Subscription
                    </button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </section>
@endsection
@section('scripts')
    {{ HTML::script('https://js.stripe.com/v3/') }}
    <script>
        const stripe = Stripe(
            "{{ config('app.debug') ? config('constants.STRIPETestpublishable_key') : config('constants.STRIPEpublishable_key') }}"
        );
        const elements = stripe.elements();
        const card = elements.create('card');
        card.mount('#payInfoContainer');

        document.getElementById('payment-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const btn = document.getElementById('payform_submit');
            btn.disabled = true;
            btn.innerHTML =
                `<span class="spinner-border spinner-border-sm mr-2" role="status" aria-hidden="true"></span> Processing...`;

            try {
                const result = await stripe.createPaymentMethod({
                    type: 'card',
                    card: card,
                    billing_details: {
                        name: document.getElementById('firstName').value + ' ' + document
                            .getElementById('lastName').value,
                        email: document.getElementById('email').value,
                    },

                });

                if (result.error) {
                    errorMessage(result.error.message);
                    btn.disabled = false;
                    btn.innerHTML = 'Complete Subscription';
                    return;
                }

                const form = document.getElementById('payment-form');
                const formData = new FormData(form);
                formData.append('stripeToken', result.paymentMethod.id);

                const res = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'Accept': 'application/json',
                    }
                });

                const data = await res.json();

                if (data.status && data.data.clientSecret) {
                    const confirmation = await stripe.confirmCardPayment(data.data.clientSecret);
                    if (confirmation.error) {
                        errorMessage(confirmation.error.message);
                        btn.disabled = false;
                        btn.innerHTML = 'Complete Subscription';
                    } else if (confirmation.paymentIntent.status === 'succeeded') {
                        successMessage('Subscription successful.');
                        window.location.href = "{{ route('subscription-success-plan') }}?payment_intent_id=" +
                            confirmation.paymentIntent.id;
                    }
                } else {
                    errorMessage(data.message || "Something went wrong.");
                    btn.disabled = false;
                    btn.innerHTML = 'Complete Subscription';
                }

            } catch (err) {
                errorMessage(err.message);
                btn.disabled = false;
                btn.innerHTML = 'Complete Subscription';
            }
        });
    </script>
@endsection
