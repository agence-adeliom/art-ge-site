import Chart from 'chart.js/auto';

const radar = document.querySelector('#radar canvas') as HTMLCanvasElement | null;
if (radar) {
    new Chart(radar, {
        type: 'radar',
        data: {
            labels: ['Social', 'Economique', 'Environnemental'],
            datasets: [{
                data: [80, 20, 10],
            }]
        },
        options: {
            scales: {
                r: {
                    beginAtZero: true,
                    angleLines: {
                        display: false
                    },
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            },
        },
    });
}
