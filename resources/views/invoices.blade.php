<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __('Subscribe') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          <section>
            <h2>Invoices</h2>
            <table width="100%">
              @foreach ($invoices as $invoice)
              <tr>
                <td>{{ $invoice->date()->toFormattedDateString() }}</td>
                <td>
                  @foreach ($invoice->lines->data as $line)
                  {{ $line->description }}<br>
                  @endforeach
                </td>
                <td>{{ $invoice->total() }}</td>
                <td><a href="/user/invoice/{{ $invoice->id }}">Download</a></td>
              </tr>
              @endforeach
            </table>

            <hr>

            <h2 class="mt-3">Upcoming</h2>
            <table width="100%">
              <tr>
                <td>{{ $upcoming->date()->toFormattedDateString() }}</td>
                <td>
                  @foreach ($upcoming->lines->data as $line)
                  {{ $line->description }}<br>
                  @endforeach
                </td>
                <td>{{ $upcoming->total() }}</td>
                <td><a href="/user/upcoming/{{ $upcoming->id }}">Download</a></td>
              </tr>
            </table>
          </section>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
