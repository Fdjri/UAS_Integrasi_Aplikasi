@extends('admin.layout')

@section('content')
<style>
    .dashboard-wrapper {
        display: flex;
        gap: 40px;
        padding-left: 10px;
        padding-right: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .column {
        display: flex;
        flex-direction: column;
        gap: 40px;
        flex: 1 1 300px;
        max-width: 450px;
    }

    .card {
        background: white;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        border-radius: 10px;
        padding: 30px 40px;
        width: 100%;
        text-align: center;
        font-family: 'Georgia', serif;
    }
    .card h2 {
        font-size: 48px;
        margin: 0 0 10px 0;
        color: #6f5846;
    }
    .card p {
        font-size: 18px;
        color: #8a7f6a;
        margin: 0;
    }

    .chart-container {
        background: white;
        border-radius: 10px;
        padding: 20px 30px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        font-family: 'Georgia', serif;
        height: auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
        max-width: 460px;
        margin: 0 auto;
    }
    .chart-container h3 {
        text-align: center;
        margin-bottom: 25px;
        font-weight: 600;
        color: #5c523f;
        font-size: 22px;
    }
    canvas {
        max-width: 100%;
        height: 280px;
        display: block;
    }

    @media (max-width: 900px) {
        .dashboard-wrapper {
            flex-direction: column;
            align-items: center;
        }
        .column {
            max-width: 90%;
        }
    }
</style>

<h1 class="dashboard-title">Dashboard Admin</h1>

<div class="dashboard-wrapper">
    <!-- Column kiri -->
    <div class="column">
        <div class="card">
            <h2>{{ $totalCustomers }}</h2>
            <p>Total User Customer</p>
        </div>
        <div class="chart-container">
            <h3>Status Booking</h3>
            <canvas id="bookingChart"></canvas>
        </div>
    </div>

    <!-- Column kanan -->
    <div class="column">
        <div class="card">
            <h2>{{ $totalServiceProviders }}</h2>
            <p>Total Service Provider</p>
        </div>
        <div class="chart-container">
            <h3>Status Pembayaran</h3>
            <canvas id="paymentChart"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const paymentData = {
        labels: ['Pending', 'Paid', 'Failed', 'Refunded'],
        datasets: [{ 
            label: 'Jumlah Pembayaran',
            data: [
                {{ $paymentsStatus['pending'] ?? 0 }},
                {{ $paymentsStatus['paid'] ?? 0 }},
                {{ $paymentsStatus['failed'] ?? 0 }},
                {{ $paymentsStatus['refunded'] ?? 0 }},
            ],
            backgroundColor: [
                '#d9cbb6',
                '#6f5846',
                '#b85454',
                '#a3a3a3'
            ],
            borderColor: '#6f5846',
            borderWidth: 1,
            hoverOffset: 20,
            cutout: '50%',
            type: 'doughnut'
        }]
    };

    const bookingData = {
        labels: ['Pending', 'Confirmed', 'Cancelled', 'Completed', 'Failed'],
        datasets: [{
            label: 'Jumlah Booking',
            data: [
                {{ $bookingsStatus['pending'] ?? 0 }},
                {{ $bookingsStatus['confirmed'] ?? 0 }},
                {{ $bookingsStatus['cancelled'] ?? 0 }},
                {{ $bookingsStatus['completed'] ?? 0 }},
                {{ $bookingsStatus['failed'] ?? 0 }},
            ],
            backgroundColor: [
                '#d9cbb6',
                '#6f5846',
                '#b85454',
                '#75a661',
                '#a3a3a3'
            ],
            borderColor: '#6f5846',
            borderWidth: 1,
            hoverOffset: 20,
            cutout: '50%',
            type: 'doughnut'
        }]
    };

    window.onload = function() {
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'doughnut',
            data: paymentData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#4a443a',
                            font: { size: 14 }
                        }
                    }
                }
            }
        });

        const bookingCtx = document.getElementById('bookingChart').getContext('2d');
        new Chart(bookingCtx, {
            type: 'doughnut',
            data: bookingData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#4a443a',
                            font: { size: 14 }
                        }
                    }
                }
            }
        });
    }
</script>
@endsection
