<div>
    <div class="pagetitle">
        <h1>📊 Dashboard Admin Unmasker</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section dashboard">
        <div class="row">
            
            <!-- Left side columns -->
            <div class="col-lg-12">
                <div class="row">
                    
                    <!-- Stats Cards -->
                    <div class="col-xxl-3 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Users</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $stats['totalUsers'] ?? 0 }}</h6>
                                        <span class="text-success small pt-1 fw-bold">{{ $stats['activeUsers'] ?? 0 }}</span>
                                        <span class="text-muted small pt-2 ps-1">aktif</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Total Articles</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-journal-text"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $stats['totalArticles'] ?? 0 }}</h6>
                                        <span class="text-success small pt-1 fw-bold">{{ $stats['publishedArticles'] ?? 0 }}</span>
                                        <span class="text-muted small pt-2 ps-1">published</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Quiz Levels</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-list-ol"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $stats['totalQuizLevels'] ?? 0 }}</h6>
                                        <span class="text-success small pt-1 fw-bold">{{ $stats['recentActiveUsers'] ?? 0 }}</span>
                                        <span class="text-muted small pt-2 ps-1">active users</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Quiz Attempts</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-check-circle"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6>{{ $stats['totalQuizAttempts'] ?? 0 }}</h6>
                                        <span class="text-success small pt-1 fw-bold">{{ $stats['averageQuizScore'] ?? 0 }}%</span>
                                        <span class="text-muted small pt-2 ps-1">avg score</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">📈 User Statistics</h5>
                                <div id="userStatsChart" style="min-height: 350px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">📊 Content Overview</h5>
                                <div id="contentChart" style="min-height: 350px;"></div>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- End Left side columns -->

        </div>
    </section>

    <!-- Vendor JS Files -->
    <script src="/assets/vendor/apexcharts/apexcharts.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // User Statistics Chart - Bar Chart
        var userStatsOptions = {
            series: [{
                name: 'Count',
                data: [
                    {{ $stats['totalUsers'] ?? 0 }},
                    {{ $stats['activeUsers'] ?? 0 }},
                    {{ $stats['recentActiveUsers'] ?? 0 }},
                    {{ $stats['totalQuizAttempts'] ?? 0 }}
                ]
            }],
            chart: {
                type: 'bar',
                height: 350
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    horizontal: false,
                }
            },
            dataLabels: {
                enabled: false
            },
            colors: ['#4154f1'],
            xaxis: {
                categories: ['Total Users', 'Active Users', 'Recent Active', 'Quiz Attempts']
            },
            yaxis: {
                title: {
                    text: 'Count'
                }
            }
        };

        var userStatsChart = new ApexCharts(document.querySelector("#userStatsChart"), userStatsOptions);
        userStatsChart.render();

        // Content Overview Chart - Donut Chart
        var contentOptions = {
            series: [{{ $stats['totalArticles'] ?? 0 }}, {{ $stats['publishedArticles'] ?? 0 }}, {{ $stats['totalQuizLevels'] ?? 0 }}],
            chart: {
                type: 'donut',
                height: 350
            },
            labels: ['Total Articles', 'Published Articles', 'Quiz Levels'],
            colors: ['#2eca6a', '#4154f1', '#ff771d'],
            legend: {
                position: 'bottom'
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Content',
                                color: '#373d3f'
                            }
                        }
                    }
                }
            }
        };

        var contentChart = new ApexCharts(document.querySelector("#contentChart"), contentOptions);
        contentChart.render();
    });
    </script>
</div>