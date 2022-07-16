<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                   <div class="text-center">
                        <i class="fas fa-user-circle" style="font-size: 94px !important;"></i>
                        <p class="fw-bold mt-4 mb-0">{{Auth::user()->name}}</p>
                        <p class="fw-light mb-3">{{Auth::user()->email}}</p>
                        <p class="fw-light">Balance : <span class="fw-bold">IDR {{ number_format(Auth::user()->wallet->balance) }}</span></p>
                   </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">History</div>

                <div class="card-body">
                   <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Amount</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>Transaction</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transaction->sortByDesc('id') as $item)
                                <tr class="{{ $item->to_id != Auth::user()->wallet->id ? 'text-danger' : 'text-success' }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->to_id != Auth::user()->wallet->id ? '-' : '+' }} {{ number_format($item->amount) }}</td>
                                    <td>{{ $item->from->user->name ?? 'Topup' }}</td>
                                    <td>{{ $item->to->user->name ?? '-' }}</td>
                                    <td>{{ $item->type }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>