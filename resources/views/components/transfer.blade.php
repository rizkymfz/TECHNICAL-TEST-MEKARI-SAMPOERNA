<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transfer</div>

                <div class="card-body">
                   <div class="px-4">
                    <form action="{{ route('transfer.store') }}">
                        <div class="mb-3">
                            <label for="to_id" class="form-label">Transfer to</label>
                            <select class="form-select" id="to_id">
                                <option value="" disabled selected>--select user--</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount">
                        </div>
                        <button type="button" class="btn btn-primary" id="btn-transfer">Transfer</button>
                    </form>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>