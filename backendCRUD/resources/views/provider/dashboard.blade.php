@extends('provider.layout')

@section('content')
<style>
  #dashboard {
    max-width: 1000px;
    margin: auto;
    font-family: 'Georgia', serif;
  }
  h2 {
    color: #4a403a;
    margin-bottom: 30px;
    text-align: center;
  }
  .charts-row {
    display: flex;
    justify-content: space-between;
    gap: 30px;
    margin-bottom: 40px;
  }
  .chart-container {
    flex: 1;
    background: #f9f7f2;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
  }
  .chart-container canvas {
    max-width: 250px !important;
    max-height: 250px !important;
    margin: auto;
    display: block;
  }
  #monthlySalesChart {
    max-width: 100% !important;
    max-height: 350px !important;
    margin: auto;
    display: block;
  }
</style>

<div id="dashboard">
  <h2>Dashboard Provider</h2>

  <div class="charts-row">
    <div class="chart-container">
      <h4>Status Booking</h4>
      <canvas id="bookingStatusChart"></canvas>
    </div>
    <div class="chart-container">
      <h4>Status Payment</h4>
      <canvas id="paymentStatusChart"></canvas>
    </div>
  </div>

  <div class="chart-container" style="max-width: 900px; margin: auto;">
    <h4>Total Booking dan Payment per Bulan</h4>
    <canvas id="monthlySalesChart" style="width: 100%; height: 350px;"></canvas>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const bookingStatusData = @json($bookingsStatus ?? []);
  const paymentStatusData = @json($paymentsStatus ?? []);
  const monthlyBookingTotals = @json($monthlyBookingTotals ?? []);
  const monthlyPaymentTotals = @json($monthlyPaymentTotals ?? []);

  const monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

  const bookingColors = {
    pending: '#C1A57B',
    confirmed: '#8C6E4A',
    cancelled: '#5A473A',
    completed: '#A1887F',
    failed: '#7F6A5B'
  };

  const paymentColors = {
    pending: '#C1A57B',
    paid: '#8C6E4A',
    failed: '#5A473A',
    refunded: '#A1887F'
  };

  const bookingLabels = Object.keys(bookingStatusData);
  const bookingData = Object.values(bookingStatusData);
  const bookingBgColors = bookingLabels.map(label => bookingColors[label] || '#999999');

  const paymentLabels = Object.keys(paymentStatusData);
  const paymentData = Object.values(paymentStatusData);
  const paymentBgColors = paymentLabels.map(label => paymentColors[label] || '#999999');

  // Pie chart status booking
  const ctxBooking = document.getElementById('bookingStatusChart').getContext('2d');
  new Chart(ctxBooking, {
    type: 'doughnut',
    data: {
      labels: bookingLabels,
      datasets: [{
        data: bookingData,
        backgroundColor: bookingBgColors,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // Pie chart status payment
  const ctxPayment = document.getElementById('paymentStatusChart').getContext('2d');
  new Chart(ctxPayment, {
    type: 'doughnut',
    data: {
      labels: paymentLabels,
      datasets: [{
        data: paymentData,
        backgroundColor: paymentBgColors,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom' } }
    }
  });

  // Bar chart total booking dan payment per bulan
  const ctxMonthly = document.getElementById('monthlySalesChart').getContext('2d');
  new Chart(ctxMonthly, {
    type: 'bar',
    data: {
      labels: monthLabels,
      datasets: [
        {
          label: 'Total Booking',
          data: monthlyBookingTotals,
          backgroundColor: '#6f5846',
          borderRadius: 5,
          barPercentage: 0.5,
          categoryPercentage: 0.6
        },
        {
          label: 'Total Payment',
          data: monthlyPaymentTotals,
          backgroundColor: '#b33527',
          borderRadius: 5,
          barPercentage: 0.5,
          categoryPercentage: 0.6
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      aspectRatio: 2,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Total Penjualan'
          },
          ticks: {
            stepSize: 1,
            maxTicksLimit: 8
          },
          grid: {
            drawBorder: false,
            color: '#e0e0e0'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Bulan'
          },
          grid: {
            display: false
          },
          ticks: {
            maxRotation: 0,
            autoSkip: false,
            font: {
              size: 13,
              family: "'Georgia', serif"
            }
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            boxWidth: 18,
            padding: 12,
            font: {
              family: "'Georgia', serif",
              size: 15
            }
          }
        },
        tooltip: {
          enabled: true,
          mode: 'index',
          intersect: false,
        }
      }
    }
  });
</script>
@endsection
