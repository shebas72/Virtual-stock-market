<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Complete your payment</h2>
            <a href="{{ route('subscription.show') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Back to subscription</a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-2xl sm:px-6 lg:px-8 space-y-6">
            @if($errors->any())
                <div class="bg-red-100 border border-red-300 text-red-900 px-4 py-3 rounded" role="alert">{{ $errors->first() }}</div>
            @endif

            <section class="rounded-lg border border-gray-200 bg-white p-6 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Gateway</p>
                        <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $payment->gatewayLabel() }}</p>
                    </div>
                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold uppercase text-amber-800 dark:bg-amber-900 dark:text-amber-200">Sandbox</span>
                </div>

                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    {{ $payment->gatewayLabel() }} is running in sandbox mode because no live API keys are configured.
                    Confirming here simulates a successful payment so you can test the full subscription flow.
                </p>

                <dl class="mt-6 space-y-3 border-t border-gray-200 pt-4 text-sm dark:border-gray-700">
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Workspace</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $payment->tenant?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Plan</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $payment->subscriptionPlan?->name ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Billing term</dt>
                        <dd class="font-medium text-gray-900 dark:text-white">{{ $payment->subscriptionPlan?->termLabel() ?? '—' }}</dd>
                    </div>
                    <div class="flex justify-between gap-4">
                        <dt class="text-gray-500 dark:text-gray-400">Amount due</dt>
                        <dd class="text-lg font-semibold text-gray-900 dark:text-white">{{ $payment->currency }} {{ number_format((float) $payment->amount, 2) }}</dd>
                    </div>
                </dl>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <form action="{{ route('subscription.payment.complete', $payment) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full rounded-md bg-teal-700 px-4 py-2 font-medium text-white hover:bg-teal-800">
                            Confirm payment
                        </button>
                    </form>
                    <form action="{{ route('subscription.payment.cancel', $payment) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full rounded-md border border-gray-300 px-4 py-2 font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700">
                            Cancel
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
