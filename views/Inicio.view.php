<h3>Bienvenido <?= $_SESSION['seguridad']['nombres'] ?></h3>
<hr>

<div class="row">
  <div class="col-md-6">
    <canvas id="grafico-1"></canvas>
  </div>
  <div class="col-md-6">
    <canvas id="grafico-2"></canvas>
  </div>
</div>


<!-- ChartJS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    const grafico1 = document.getElementById('grafico-1');
    const grafico2 = document.getElementById('grafico-2');

    const graficoOne = new Chart(grafico1, {
      type: 'bar',
      data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    const graficoTwo = new Chart(grafico2, {
      type: 'pie',
      data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
    
  })
</script>