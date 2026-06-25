

export const initChartOne = () => {
    const chartElement = document.querySelector('#chartOne');
    if (!chartElement) return;

    let seriesData = [168, 385, 201, 298, 187, 195, 291, 110, 215, 390, 280, 112];
    let categories = [
        "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    if (chartElement.hasAttribute('data-series')) {
        seriesData = JSON.parse(chartElement.getAttribute('data-series'));
    }
    if (chartElement.hasAttribute('data-labels')) {
        categories = JSON.parse(chartElement.getAttribute('data-labels'));
    }

    const chartOneOptions = {
        series: [{
            name: "Jumlah",
            data: seriesData,
        },],
        colors: ["#465fff"],
        chart: {
            fontFamily: "Outfit, sans-serif",
            type: "bar",
            height: 180,
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "39%",
                borderRadius: 5,
                borderRadiusApplication: "end",
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 4,
            colors: ["transparent"],
        },
        xaxis: {
            categories: categories,
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        legend: {
            show: true,
            position: "top",
            horizontalAlign: "left",
            fontFamily: "Outfit",
            markers: {
                radius: 99,
            },
        },
        yaxis: {
            title: false,
        },
        grid: {
            yaxis: {
                lines: {
                    show: true,
                },
            },
        },
        fill: {
            opacity: 1,
        },

        tooltip: {
            x: {
                show: false,
            },
            y: {
                formatter: function (val) {
                    return val;
                },
            },
        },
    };

    chartElement.innerHTML = ''; // Prevent duplicates on Livewire navigation
    const chart = new ApexCharts(chartElement, chartOneOptions);
    chart.render();

    return chart;
};

export default initChartOne;
