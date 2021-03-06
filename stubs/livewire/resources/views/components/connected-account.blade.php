@props(['provider', 'createdAt'])

<div class="px-3 flex items-center justify-between">
    <div class="flex items-center">
        @switch($provider)
            @case('facebook')
                <x-facebook-icon class="h-6 w-6 mr-2" />
                @break
            @case('google')
                <x-google-icon class="h-6 w-6 mr-2" />
                @break
            @case('twitter')
                <x-twitter-icon class="h-6 w-6 mr-2" />
                @break
            @case('linkedin')
                <x-linked-in-icon class="h-6 w-6 mr-2" />
                @break
            @case('github')
                <x-github-icon class="h-6 w-6 mr-2" />
                @break
            @case('gitlab')
                <x-gitlab-icon class="h-6 w-6 mr-2" />
                @break
            @case('bitbucket')
                <x-bitbucket-icon class="h-6 w-6 mr-2" />
                @break
            @default
        @endswitch

        <div>
            <div class="text-sm font-semibold text-gray-600">
                {{ ucfirst($provider) }}
            </div>

            <div class="text-xs text-gray-500">
                {{ $createdAt }}
            </div>
        </div>
    </div>

    <div>
        {{ $action }}
    </div>
</div>
