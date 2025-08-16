@extends('admin._partials.app')

@section('content')
    <div class="container-xl px-4 mt-5">
        <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
            <div class="me-4 mb-sm-0">
                <h1 class="mb-0">Dashboard</h1>
                <div class="small">
                    <span class="fw-500 text-primary">{{ date('D', strtotime(now())) }}</span>
                    · {{ Utils::formatDate(now(), true) }}
                </div>
            </div>
            <!-- Date range picker example-->
            {{-- <div class="input-group input-group-joined border-0 shadow" style="width: 16.5rem">
                <span class="input-group-text"><i data-feather="calendar"></i></span>
                <input class="form-control ps-0 pointer" id="litepickerRangePlugin" placeholder="Select date range..." />
            </div> --}}
        </div>
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-lg border-start-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="small fw-bold text-primary mb-1">Total customers</div>
                                <div class="h5">{{ $customer_count }}</div>
                            </div>
                            <div class="ms-2"><i class="fas fa-users fa-2x text-gray-200"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start-lg border-start-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="small fw-bold text-primary mb-1">Total providers</div>
                                <div class="h5">{{ $provider_count }}</div>
                            </div>
                            <div class="ms-2"><i class="fas fa-users fa-2x text-gray-200"></i></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach ($request_counts as $request_count)
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-start-lg border-start-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <div class="small fw-bold text-primary mb-1">{{ Utils::decodeSlug($request_count->status) }} requests</div>
                                    <div class="h5">{{ $request_count->count }}</div>
                                    {{-- <div class="text-xs fw-bold text-success d-inline-flex align-items-center">
                                    <i class="me-1" data-feather="trending-up"></i>
                                    12%
                                </div> --}}
                                </div>
                                <div class="ms-2"><i class="fas fa-briefcase fa-2x text-gray-200"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row mb-5">
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">Service categories summary</div>
                    <div class="card-body">
                        <div class="chart-pie mb-4"><canvas id="categoryChart" width="100%" height="50"></canvas>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach ($requests_per_category as $key => $item)
                                <div
                                    class="list-group-item d-flex align-items-center justify-content-between small px-0 py-2">
                                    <div class="me-3">
                                        <i class="fas fa-circle fa-sm me-1 text-{{ $item['color'] }}"></i>
                                        {{ $item['name'] }}
                                    </div>
                                    <div class="fw-500 text-dark">{{ $item['percentage'] }}%</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header">Service subcategories summary</div>
                    <div class="card-body">
                        <div class="chart-pie mb-4"><canvas id="subcategoryChart" width="100%" height="50"></canvas>
                        </div>
                        <div class="list-group list-group-flush">
                            @foreach ($requests_per_subcategory as $key => $item)
                                <div
                                    class="list-group-item d-flex align-items-center justify-content-between small px-0 py-2">
                                    <div class="me-3">
                                        <i class="fas fa-circle fa-sm me-1 text-{{ $item['color'] }}"></i>
                                        {{ $item['name'] }}
                                    </div>
                                    <div class="fw-500 text-dark">{{ $item['percentage'] }}%</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="pie-labels_category" content="{{ json_encode(array_column($requests_per_category, 'name')) }}"></div>
    <div id="pie-values_category" content="{{ json_encode(array_column($requests_per_category, 'percentage')) }}"></div>
    <div id="pie-colors_category" content="{{ json_encode(array_column($requests_per_category, 'color')) }}"></div>

    <div id="pie-labels_subcategory" content="{{ json_encode(array_column($requests_per_subcategory, 'name')) }}"></div>
    <div id="pie-values_subcategory" content="{{ json_encode(array_column($requests_per_subcategory, 'percentage')) }}">
    </div>
    <div id="pie-colors_subcategory" content="{{ json_encode(array_column($requests_per_subcategory, 'color')) }}"></div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js" crossorigin="anonymous"></script>
    <script>
        var cat_ctx = document.getElementById("categoryChart");
        var options = {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: "#dddfeb",
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10
            },
            legend: {
                display: false
            },
            cutoutPercentage: 80
        }
        const backgroundColor = [
            "rgba(0, 97, 242, 1)",
            "rgba(0, 172, 105, 1)",
            "rgba(88, 0, 232, 1)"
        ];
        const hoverBackgroundColor = [
            "rgba(0, 97, 242, 0.9)",
            "rgba(0, 172, 105, 0.9)",
            "rgba(88, 0, 232, 0.9)"
        ];

        var categoryChart = new Chart(cat_ctx, {
            type: "doughnut",
            data: {
                labels: JSON.parse($('#pie-labels_category').attr('content')),
                datasets: [{
                    data: JSON.parse($('#pie-values_category').attr('content')),
                    backgroundColor: JSON.parse($('#pie-colors_category').attr('content')),
                    //   hoverBackgroundColor: hoverBackgroundColor,
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }]
            },
            options: options
        });

        var subcat_ctx = document.getElementById("subcategoryChart");
        var subcategoryChart = new Chart(subcat_ctx, {
            type: "doughnut",
            data: {
                labels: JSON.parse($('#pie-labels_subcategory').attr('content')),
                datasets: [{
                    data: JSON.parse($('#pie-values_subcategory').attr('content')),
                    backgroundColor: JSON.parse($('#pie-colors_subcategory').attr('content')),
                    hoverBorderColor: "rgba(234, 236, 244, 1)"
                }]
            },
            options: options
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/bundle.js" crossorigin="anonymous"></script>
    <script src="{{ asset('libs/js/custom/litepicker.js') }}"></script>

    {{-- <script src="https://assets.startbootstrap.com/js/sb-customizer.js"></script> --}}
@endsection
