@if (session('success'))
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-xl bg-forest-50 border border-forest-200 text-forest-900 flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-3">
            <span class="p-2 bg-forest-600 text-white rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </span>
            <div>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        </div>
        <button @click="show = false" class="text-forest-600 hover:text-forest-800 p-1 rounded-md">&times;</button>
    </div>
@endif

@if (session('error'))
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-3">
            <span class="p-2 bg-rose-600 text-white rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </span>
            <div>
                <p class="font-medium text-sm">{{ session('error') }}</p>
            </div>
        </div>
        <button @click="show = false" class="text-rose-600 hover:text-rose-800 p-1 rounded-md">&times;</button>
    </div>
@endif

@if (session('info'))
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-900 flex items-center justify-between shadow-sm">
        <div class="flex items-center space-x-3">
            <span class="p-2 bg-sky-600 text-white rounded-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </span>
            <div>
                <p class="font-medium text-sm">{{ session('info') }}</p>
            </div>
        </div>
        <button @click="show = false" class="text-sky-600 hover:text-sky-800 p-1 rounded-md">&times;</button>
    </div>
@endif

@if ($errors->any())
    <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex items-start space-x-3">
                <span class="p-2 bg-amber-600 text-white rounded-lg mt-0.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </span>
                <div>
                    <h4 class="font-semibold text-sm">Terdapat beberapa kesalahan input:</h4>
                    <ul class="list-disc list-inside text-xs mt-1.5 space-y-1 text-amber-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button @click="show = false" class="text-amber-600 hover:text-amber-800 p-1 rounded-md">&times;</button>
        </div>
    </div>
@endif
