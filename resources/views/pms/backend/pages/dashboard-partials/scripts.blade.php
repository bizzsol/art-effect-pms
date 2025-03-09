@section('page-script')
<script src='{{ asset('assets/js/Chart.bundle.min.js') }}'></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/waypoints/2.0.3/waypoints.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.counterup/1.0/jquery.counterup.min.js"></script>

<script>
$(document).ready(function () {
    // Call the charts with a delay
    setTimeout(() => {
        $('.charts').each(function () {
            loadChart(
                $(this),
                $(this).data('chart'),
                $(this).data('labels').split(','),
                $(this).data('data').split(','),
                $(this).data('legend-position'),
                $(this).data('title-text')
            );
        });

        $('.bar-charts').each(function () {
            loadBarChart(
                $(this),
                $(this).data('labels'),
                $(this).data('data'),
                $(this).data('title-text'),
                $(this).data('legend-position')
            );
        });
    }, 2000); // 2-second delay

    // Load the Charts
    function loadChart(element, type, labels, data, legendPosition, titleText, responsive = true, titleDisplay = true) {
        const chart = new Chart(element, {
            type: type,
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: randomColors(labels.length),
                    borderColor: '#ccc',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: responsive,
                plugins: {
                    legend: {
                        position: legendPosition,
                    }
                },
                title: {
                    display: titleDisplay,
                    text: titleText
                },
                animation: {
                    onComplete: function () {
                        addDownloadOptions(chart, element);
                    }
                }
            }
        });
    }

    function loadBarChart(element, labels, data, titleText, legendPosition, responsive = true, beginAtZero = true, titleDisplay = false) {
        const titleTextArray = titleText.split(',');
        const dataArray = data.split('|');
        const datasets = titleTextArray.map((item, index) => ({
            label: item,
            data: dataArray[index].split(','),
            backgroundColor: randomColors(titleTextArray.length)[index],
            borderColor: '#fff',
            borderWidth: 1
        }));

        const barChartContext = document.getElementById(element.attr('id')).getContext('2d');
        const chart = new Chart(barChartContext, {
            type: 'bar',
            data: {
                labels: labels.split(','),
                datasets: datasets
            },
            options: {
                responsive: responsive,
                scales: {
                    y: {
                        beginAtZero: beginAtZero
                    }
                },
                plugins: {
                    legend: {
                        position: legendPosition,
                    }
                },
                title: {
                    display: titleDisplay,
                    text: titleText.split(',')
                },
                animation: {
                    onComplete: function () {
                        addDownloadOptions(chart, element);
                    }
                }
            }
        });
    }

    function addDownloadOptions(chart, element) {
        const parent = element.parent();
        parent.find('img').remove();
        parent.append(`<img src="${chart.toBase64Image()}" class="d-none"/>`);

        const header = parent.parent().find('.iq-card-header, .project-card-header');
        if (header.length) {
            header.find('.download-button').remove();
            header.append(`
                <a class="btn btn-xs btn-primary download-button pull-right" style="margin-top: 0px !important" href="${chart.toBase64Image()}" 
                download="${parent.parent().find('.card-title, h5').text()}">
                    <i class="la la-download"></i>
                </a>
            `);
        }
    }

    // Chart Options
    function randomColors(count) {
        return Array.from({ length: count }, (_, i) => {
            const bank = colorBank();
            return bank[i] || randomBackgroundColor().color;
        });
    }

    function colorBank() {
        return [
            'rgb(11,173,191)',
            'rgb(60, 179, 113)',
            'rgb(238, 130, 238)',
            'rgb(255, 165, 0)',
            'rgb(106, 90, 205)',
            'rgb(60, 60, 60)',
            'rgba(255, 99, 71, 1)'
        ];
    }

    function randomBackgroundColor() {
        const r = Math.floor(Math.random() * 256);
        const g = 100 + Math.floor(Math.random() * 256);
        const b = 50 + Math.floor(Math.random() * 256);
        return { color: `rgb(${r},${g},${b})` };
    }

    // Counter animation
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });
});

</script>
@endsection