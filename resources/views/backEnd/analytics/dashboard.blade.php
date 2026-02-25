@extends('backEnd.layouts.master')
@section('title', 'Visitor Reports')
@section('css')
    <!-- Plugins css -->
    <link href="{{ asset('public/backEnd/') }}/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/backEnd/') }}/assets/libs/selectize/css/selectize.bootstrap3.css" rel="stylesheet"
        type="text/css" />
        
    <style>
        a.canvasjs-chart-credit {
            display: none !important;
        }
        .graph-pie{
            background:#fff;
            margin-bottom:20px;
        }
        .des-item h5 {
            color: #979797;
        }
        .des-item h2 {
            font-weight: 800;
            color: #6a6a6a;
        }
        .chart-des {
            padding-top: 50px;
        }
        .inner-chart {
            position: absolute;
            top: 25%;
            left: 34%;
            opacity: 1;
            z-index: 999;
            text-align: center;
        }
        .inner-chart h5 {
            text-transform: capitalize;
        }
        .main-Pie{
            position:relative;
        }
        .ex-pro {
            margin-top: 14px;
            margin-left: 8px;
        }
        </style>
@endsection
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">

                    </div>
                    <h4 class="page-title">Website Visitor Reports</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="border-2  card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-1 text-truncate">Active Visitor</p>
                                <h3 class="text-dark my-1 "><span data-plugin="counterup">{{ $activeVisitors }}</span></h3>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->
            <div class="col-md-6 col-xl-3">
                <div class="border-2  card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-1 text-truncate">Today Visitor</p>
                                <h3 class="text-dark my-1 "><span data-plugin="counterup">{{ $todayVisitors }}</span></h3>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->
            <div class="col-md-6 col-xl-3">
                <div class="border-2  card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-1 text-truncate">Yesterday Visitor</p>
                                <h3 class="text-dark my-1 "><span data-plugin="counterup">{{ $yesterdayVisitors }}</span></h3>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->
            <div class="col-md-6 col-xl-3">
                <div class="border-2  card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-1 text-truncate">This Week Visitor</p>
                                <h3 class="text-dark my-1 "><span data-plugin="counterup">{{ $thisWeekVisitors }}</span></h3>
                            </div>
                           
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->
            <div class="col-md-6 col-xl-3">
                <div class="border-2 card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-1 text-truncate">Last Week Visitor</p>
                                <h3 class="text-dark my-1 "><span data-plugin="counterup">{{ $lastWeekVisitors }}</span></h3>
                            </div>
                        </div>
                    </div>
                </div><!-- end widget-rounded-circle-->
            </div> <!-- end col-->
            
            <div class="col-md-6 col-xl-3">
                <div class="border-2  card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-1 text-truncate"/>This Month Visitor</p>
                                <h3 class="text-dark my-1"><span data-plugin="counterup">{{ $thisMonthVisitors }} </span></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->
            <div class="col-md-6 col-xl-3">
                <div class="border-2  card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-1 text-truncate">Last Month Visitor</p>
                                <h3 class="text-dark my-1"><span data-plugin="counterup">{{ $lastMonthVisitors }}</span></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->
            <div class="col-md-6 col-xl-3">
                <div class="border-2  card rounded-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 text-center">
                                <p class="text-muted mb-1 text-truncate">Total Visitor</p>
                                <h3 class="text-dark my-1"><span data-plugin="counterup">{{ $totalVisitors }}</span></h3>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div> <!-- end col-->
            
        </div>
        <!-- end row-->

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title mb-0">Last 30 days sales reports</h4>
                        <canvas id="visitorChart" width="600" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    <!--graph chart end -->

    </div> <!-- container -->
@endsection
@section('script')
    <!-- Plugins js-->
    <script src="{{ asset('public/backEnd/') }}/assets/libs/flatpickr/flatpickr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('visitorChart').getContext('2d');
        const visitorChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [{
                    label: 'Visitors',
                    data: {!! json_encode($visitors) !!},
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 2
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: { autoSkip: true, maxTicksLimit: 15 }
                    },
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
    <script src="https://cdn.canvasjs.com/jquery.canvasjs.min.js"></script>
     <script type="text/javascript">
        $(document).ready(function () {
            flatpickr(".flatdate", {});
        });
    </script>
@endsection

