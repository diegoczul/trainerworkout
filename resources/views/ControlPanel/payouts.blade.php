@extends('layouts.controlpanel')

@section('content')
    <div class="col-lg-12">
        <h1 class="page-header">Payouts Management</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="payoutsTable">
                            <thead>
                                <tr>
                                    <th>Trainer Name</th>
                                    <th>Email</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Mark as Paid / Paid At</th>
                                </tr>
                            </thead>


                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let dtPayouts;
        $(document).ready(function() {
            dtPayouts = $("#payoutsTable").DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "/ControlPanel/Payouts/Data",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    }
                },
                columns: [{
                        data: "trainer_name",
                        title: "Trainer Name"
                    },
                    {
                        data: "email",
                        title: "Email"
                    },
                    {
                        data: "amount",
                        title: "Amount"
                    },
                    {
                        data: "status",
                        title: "Status"
                    },
                    {
                        data: "created_at",
                        title: "Created At"
                    },
                    {
                        data: "mark_paid",
                        title: "Mark as Paid / Paid At",
                        orderable: false,
                        searchable: false
                    }
                ]


            });
        });

        function markAsPaid(id) {
            if (!confirm("Mark this payout as paid?")) return;
            $.post("/ControlPanel/Payouts/MarkAsPaid/" + id, {
                _token: "{{ csrf_token() }}"
            }, function(res) {
                if (res.success) {
                    dtPayouts.ajax.reload();
                    successMessage("Marked as paid.");
                } else {
                    errorMessage("Something went wrong.");
                }
            });
        }
    </script>
@endsection
