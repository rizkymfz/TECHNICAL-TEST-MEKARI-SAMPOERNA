<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Topup</div>

                <div class="card-body">
                   <div class="px-4">
                    <form action="{{ route('topup.store') }}">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Topup Amount</label>
                            <input type="number" class="form-control" name="amount" id="amount" aria-describedby="emailHelp">
                            <div id="emailHelp" class="form-text">Enter topup amount.</div>
                          </div>
                        <button type="button" class="btn btn-primary" id="btn-topup">Top up</button>
                    </form>
                   </div>
                </div>
            </div>
        </div>
    </div>
</div>