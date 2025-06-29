<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("View your account's basic profile details.") }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        <div>
            <x-input-label :value="__('First Name')" />
            <p class="mt-1 text-sm text-gray-900">{{ $user->first_name }}</p>
        </div>

        <div>
            <x-input-label :value="__('Last Name')" />
            <p class="mt-1 text-sm text-gray-900">{{ $user->last_name ?? 'N/A' }}</p>
        </div>

        <div>
            <x-input-label :value="__('Email')" />
            <p class="mt-1 text-sm text-gray-900">{{ $user->email }}</p>
        </div>

        <div>
            <x-input-label :value="__('Phone')" />
            <p class="mt-1 text-sm text-gray-900">{{ $user->phone ?? 'N/A' }}</p>
        </div>

        <div>
            <x-input-label :value="__('Role')" />
            <p class="mt-1 text-sm text-gray-900">
                @foreach ($user->getRoleNames() as $role)
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ str_replace('_', ' ', Str::title($role)) }}
                    </span>
                @endforeach
            </p>
        </div>
    </div>
</section>