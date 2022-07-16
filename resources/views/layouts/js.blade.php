<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>

    //toastr option
    toastr.options = {
        "closeButton": true,
        "positionClass": "toast-top-right",
        "progressBar": true
    }

    var hash = 'dashboard'
    $(document).ready(function() {
        $('#'+hash).click()
    })

    // view history
    $(document).on('click', '#dashboard', function() {
        showLoader()
        $('#res').empty()
        hash = 'dashboard'
        $.ajax({
            url: `{{ route('dashboard.history') }}`,
            method: 'get',
            success: function(res) {
                $('#res').html(res)
                hideLoader()
            },
            error: function(res) {
                hideLoader()
                showToast('Error', res.responseJSON.message)
            },
        })
    })

    $(document).on('click', '#topup', function() {
        showLoader()
        $('#res').empty()
        hash = 'topup'
        $.ajax({
            url: `{{ route('topup.create') }}`,
            method: 'get',
            success: function(res) {
                $('#res').html(res)
                hideLoader()
            },
            error: function(res) {
                hideLoader()
                showToast('Error', res.responseJSON.message)
            },
        })
    })

    $(document).on('click', '#transfer', function() {
        showLoader()
        $('#res').empty()
        hash = 'transfer'
        $.ajax({
            url: `{{ route('transfer.create') }}`,
            method: 'get',
            success: function(res) {
                $('#res').html(res)
                hideLoader()
            },
            error: function(res) {
                hideLoader()
                showToast('Error', res.responseJSON.message)
            },
        })
    })

    $(document).on('click', '#btn-topup', function(e) {
        e.stopPropagation()
        var url = $(this).parents('form').attr('action')
        var amount = $('#amount').val()
        showLoader()
        $.ajax({
            url: url,
            method: 'post',
            data: {_token: "{{csrf_token()}}" ,amount: amount},
            success: function(res) {
                if(res.status == true) {
                    hideLoader()
                    toastr.success(res.message)
                    $('#dashboard').click()
                }else{
                    hideLoader()
                    toastr.error(res.message)
                }
            },
        })
    })

    $(document).on('click', '#btn-transfer', function(e) {
        e.stopPropagation()
        var url = $(this).parents('form').attr('action')
        var amount = $('#amount').val()
        var to_id = $('#to_id').val()
        showLoader()
        $.ajax({
            url: url,
            method: 'post',
            data: {
                _token: "{{csrf_token()}}",
                amount: amount,
                to_id: to_id
            },
            success: function(res) {
                if(res.status == true) {
                    hideLoader()
                    toastr.success(res.message)
                    $('#dashboard').click()
                }else{
                    hideLoader()
                    toastr.error(res.message)
                }
            },
        })
    })
</script>

<!-- LOADER -->
<script>
    function showLoader() {
        $('#res').hide()
        $('#loading').addClass('d-flex').removeClass('d-none')
    }

    function hideLoader() {
        $('#res').show()
        $('#loading').addClass('d-none').removeClass('d-flex')
    }
</script>
@stack('js')
