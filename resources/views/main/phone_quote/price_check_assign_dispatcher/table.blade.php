@include('partials.mainsite_pages.return_function')
<?php
$respn = trim("$_SERVER[REQUEST_URI]", '/');
if (isset($_GET['titlee'])) {
    $respn = $_GET['titlee'];
}
?>
<style>
    .table {
        /*color: rgb(0 0 0);*/
        width: 100%;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .table>thead>tr>td,
    .table>thead>tr>th {
        font-weight: 400;
        -webkit-transition: all .3s ease;
        font-size: 18px;
        color: rgb(0 0 0);
    }

    .table-data-align {
        display: flex;
        align-items: flex-end;
    }

    .table-btn-style {}

    .bg-white th {
        border: 1px solid #000000 !important;
    }

    .bg-white td {
        border: 1px solid #000000 !important;
    }
</style>
<div class="table-responsive tableResponsiveNew">
    {{-- resources/views/main/phone_quote/price_check_assign_dispatcher/table.blade.php --}}
    <table class="table table-bordered table-sm text-center">
        <thead class="bg-white">
        <tr>
            <th width="15%">ID</th>
            <th width="35%">User Name</th>
            <th width="20%">Status</th>
            <th width="30%">Action</th>
        </tr>
        </thead>
        <tbody>
        @forelse($data as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->name ?? $u->email }}</td>
                <td class="js-status">
                    @if($u->is_allow_price_check)
                        <span class="badge badge-success">Allowed</span>
                    @else
                        <span class="badge badge-danger">Blocked</span>
                    @endif
                </td>
                <td>
                    <button
                        class="btn btn-sm {{ $u->is_allow_price_check ? 'btn-danger' : 'btn-success' }} js-toggle-allow"
                        data-url="{{ route('pricecheck.dispatchers.toggle', $u) }}"
                        data-current="{{ (int) $u->is_allow_price_check }}"
                    >
                        {{ $u->is_allow_price_check ? 'Disable' : 'Enable' }}
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted">No users found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center">
        <div class="text-secondary my-2">
            Showing {{ $data->firstItem() ?? 0 }} to {{ $data->lastItem() ?? 0 }} from total {{ $data->total() }} entries
        </div>
        <div class="my-2">
            {{ $data->links() }}
        </div>
    </div>


</div>


<style>
    .tx-white {
        color: white !important;
    }

    .badge-orange {
        color: #212529;
        background-color: #F49917;
    }
</style>

<script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

<script src="{{ url('assets/js/jquery-3.5.1.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.all.min.js"></script>
<script src="{{ url('assets/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<script>



</script>




