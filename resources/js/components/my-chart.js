import Chart from 'chart.js/auto';

let myChartInstance = null;

export const initMyChart = () => {
    const canvas = document.getElementById('myChart');
    if (!canvas) return;

    let seriesData = [0, 0, 0];
    let categories = ["Dokumen", "Kategori", "Jenis Dokumen"];

    if (canvas.hasAttribute('data-series')) {
        seriesData = JSON.parse(canvas.getAttribute('data-series'));
    }
    if (canvas.hasAttribute('data-labels')) {
        categories = JSON.parse(canvas.getAttribute('data-labels'));
    }

    // Destroy existing chart to prevent duplication on Livewire navigation
    if (myChartInstance) {
        myChartInstance.destroy();
    }

    myChartInstance = new Chart(canvas, {
        type: 'bar',
        data: {
            labels: categories,
            datasets: [{
                label: 'Jumlah',
                data: seriesData,
                backgroundColor: ['#3b82f6', '#10b981', '#a855f7', '#f97316'],
                borderRadius: 4,
                borderWidth: 0,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        color: '#9ca3af'
                    },
                    grid: {
                        color: document.documentElement.classList.contains('dark') ? '#374151' : '#e5e7eb',
                        borderDash: [4, 4]
                    }
                },
                x: {
                    ticks: {
                        color: '#9ca3af'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
};
